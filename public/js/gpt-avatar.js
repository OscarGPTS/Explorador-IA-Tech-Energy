/* ============================================================
   GPT Avatar — robot corporativo GPT Services
   Módulo reutilizable para los servicios de Explorador IA.

   Uso:
     GPTAvatar.mount(el)                     // monta una instancia en el contenedor
       - el contenedor puede definir data-avatar-mode="full" (cuerpo completo)
         o "head" (solo cabeza, default) y data-fallback-src (imagen si falla WebGL)
     GPTAvatar.setState('idle'|'listening'|'thinking'|'speaking')
     GPTAvatar.setProfile('field'|'exec')    // casco EPP u oficina (persiste en localStorage)
     GPTAvatar.onStateChange(cb)             // cb(state) al cambiar de estado
     GPTAvatar.speak(texto, { onend })       // TTS del navegador, boca sincronizada
     GPTAvatar.playAudio(src, { onend })     // audio del backend, boca por amplitud real
     GPTAvatar.stop()

   Todas las instancias de la página comparten estado y perfil:
   representan al mismo asistente (EVIA).
   ============================================================ */
(function (global) {
  'use strict';

  const THREE_CDN = 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js';
  const PROFILE_KEY = 'gpt-avatar-profile';

  // Colores de lámpara alineados a la paleta EVIA (oro/rojo institucional)
  const STATE_META = {
    idle:      { lamp: 0xFBBF24 },
    listening: { lamp: 0xEF4444 },
    thinking:  { lamp: 0xFCD34D },
    speaking:  { lamp: 0xFB923C }
  };

  const instances = [];
  const stateListeners = [];
  let currentState = 'idle';
  let currentProfile = 'field';
  try { currentProfile = localStorage.getItem(PROFILE_KEY) || 'field'; } catch (e) {}

  let targetLevel = 0; // nivel de boca compartido (lo escriben speak/playAudio)
  const pointer = { x: 0, y: 0 };

  global.addEventListener('pointermove', function (e) {
    pointer.x = (e.clientX / global.innerWidth) * 2 - 1;
    pointer.y = (e.clientY / global.innerHeight) * 2 - 1;
  });

  /* ---------- carga diferida de three.js ---------- */
  let threePromise = null;
  function loadThree() {
    if (global.THREE) return Promise.resolve();
    if (!threePromise) {
      threePromise = new Promise(function (resolve, reject) {
        const s = document.createElement('script');
        s.src = THREE_CDN;
        s.onload = resolve;
        s.onerror = function () { reject(new Error('No se pudo cargar three.js')); };
        document.head.appendChild(s);
      });
    }
    return threePromise;
  }

  /* ---------- geometría ---------- */
  function roundedBoxGeo(THREE, w, h, d, r) {
    r = Math.min(r, w / 2 - 0.001, h / 2 - 0.001, d / 2 - 0.001);
    const hw = w / 2 - r, hh = h / 2 - r;
    const s = new THREE.Shape();
    s.moveTo(-hw, -h / 2);
    s.lineTo(hw, -h / 2);
    s.quadraticCurveTo(w / 2, -h / 2, w / 2, -hh);
    s.lineTo(w / 2, hh);
    s.quadraticCurveTo(w / 2, h / 2, hw, h / 2);
    s.lineTo(-hw, h / 2);
    s.quadraticCurveTo(-w / 2, h / 2, -w / 2, hh);
    s.lineTo(-w / 2, -hh);
    s.quadraticCurveTo(-w / 2, -h / 2, -hw, -h / 2);
    const g = new THREE.ExtrudeGeometry(s, {
      depth: d - 2 * r, bevelEnabled: true,
      bevelThickness: r, bevelSize: r - 0.001,
      bevelSegments: 4, curveSegments: 5
    });
    g.translate(0, 0, -(d - 2 * r) / 2);
    return g;
  }

  // parche con el logo GPT Services (textura canvas)
  function makeLogoTexture(THREE) {
    const c = document.createElement('canvas');
    c.width = 256; c.height = 140;
    const g = c.getContext('2d');
    g.fillStyle = '#ffffff';
    g.fillRect(0, 0, 256, 140);
    g.strokeStyle = '#d8d2c8'; g.lineWidth = 6;
    g.strokeRect(3, 3, 250, 134);
    g.textAlign = 'center';
    g.fillStyle = '#cf0a2c';
    g.font = '900 62px Arial';
    g.fillText('GPT', 128, 70);
    g.fillStyle = '#e6a400';
    g.font = '700 24px Arial';
    g.fillText('SERVICES', 128, 108);
    const tx = new THREE.CanvasTexture(c);
    tx.anisotropy = 4;
    return tx;
  }

  /* ---------- construcción de una instancia ---------- */
  function buildInstance(el, mode) {
    const THREE = global.THREE;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(50, 1, 0.01, 100);
    if (mode === 'full') {
      // encuadre de cuerpo completo, pensado para contenedores circulares pequeños
      camera.position.set(0, 1.0, 5.5);
      camera.lookAt(0, 0.72, 0);
    } else {
      // encuadre de cabeza pequeña para contenedores circulares (40px, 84px)
      camera.position.set(0, 1.65, 2.1);
      camera.lookAt(0, 1.65, 0);
    }

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, preserveDrawingBuffer: true });
    renderer.setPixelRatio(Math.min(global.devicePixelRatio || 1, 2));
    renderer.setClearColor(0x000000, 0);
    renderer.domElement.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;display:block;';
    el.appendChild(renderer.domElement);

    scene.add(new THREE.AmbientLight(0xb8b2aa, 0.62));
    const key = new THREE.DirectionalLight(0xfff0dd, 1.0);
    key.position.set(3.5, 5, 4);
    scene.add(key);
    const rim = new THREE.DirectionalLight(0xb91c1c, 0.4);
    rim.position.set(-4, 2.5, -3);
    scene.add(rim);

    /* ---------- materiales ---------- */
    const matWhite   = new THREE.MeshStandardMaterial({ color: 0xf3efe8, roughness: 0.45, metalness: 0.05 });
    const matBlack   = new THREE.MeshStandardMaterial({ color: 0x141417, roughness: 0.32, metalness: 0.4 });
    const matRed     = new THREE.MeshStandardMaterial({ color: 0xb91c1c, roughness: 0.4, metalness: 0.12 });
    const matCoverall = new THREE.MeshStandardMaterial({ color: 0xf25c19, roughness: 0.6, metalness: 0.02 });
    const matHelmet  = new THREE.MeshStandardMaterial({ color: 0xf5f2ec, roughness: 0.3, metalness: 0.1 });
    const matReflSil = new THREE.MeshStandardMaterial({ color: 0xd9dce0, roughness: 0.25, metalness: 0.3, emissive: 0x44484c, emissiveIntensity: 0.35 });
    const matReflYel = new THREE.MeshStandardMaterial({ color: 0xe8e84a, roughness: 0.3, metalness: 0.1, emissive: 0x666a10, emissiveIntensity: 0.4 });
    const matPants   = new THREE.MeshStandardMaterial({ color: 0x2a2e36, roughness: 0.7 });
    const matEye     = new THREE.MeshBasicMaterial({ color: 0xffb000 });
    const matMouth   = new THREE.MeshBasicMaterial({ color: 0xff2247 });
    const matLogo    = new THREE.MeshBasicMaterial({ map: makeLogoTexture(THREE) });

    /* ---------- robot ---------- */
    const robot = new THREE.Group();
    scene.add(robot);

    // --- cabeza ---
    const headGroup = new THREE.Group();
    headGroup.position.y = 1.62;
    robot.add(headGroup);

    const head = new THREE.Mesh(roundedBoxGeo(THREE, 1.15, 0.95, 0.9, 0.2), matWhite);
    headGroup.add(head);

    const visor = new THREE.Mesh(roundedBoxGeo(THREE, 0.92, 0.56, 0.12, 0.13), matBlack);
    visor.position.set(0, -0.02, 0.40);
    headGroup.add(visor);

    function makeEye() {
      const g = new THREE.Group();
      g.add(new THREE.Mesh(new THREE.CircleGeometry(0.105, 24), matEye));
      const shine = new THREE.Mesh(new THREE.CircleGeometry(0.034, 12),
        new THREE.MeshBasicMaterial({ color: 0xffffff }));
      shine.position.set(0.035, 0.04, 0.004);
      g.add(shine);
      return g;
    }
    const eyeL = makeEye(); eyeL.position.set(-0.23, 0.09, 0.475);
    const eyeR = makeEye(); eyeR.position.set(0.23, 0.09, 0.475);
    headGroup.add(eyeL, eyeR);

    const cheekMat = new THREE.MeshBasicMaterial({ color: 0xff8a50, transparent: true, opacity: 0.5 });
    const cheekL = new THREE.Mesh(new THREE.CircleGeometry(0.055, 14), cheekMat);
    cheekL.position.set(-0.39, -0.04, 0.474);
    const cheekR = cheekL.clone(); cheekR.position.x = 0.39;
    headGroup.add(cheekL, cheekR);

    const smileArc = Math.PI * 0.8;
    const smile = new THREE.Mesh(new THREE.TorusGeometry(0.13, 0.026, 10, 28, smileArc), matMouth);
    smile.rotation.z = Math.PI + (Math.PI - smileArc) / 2;
    smile.position.set(0, -0.1, 0.475);
    headGroup.add(smile);

    const mouthBars = [];
    for (let i = 0; i < 5; i++) {
      const bar = new THREE.Mesh(new THREE.PlaneGeometry(0.06, 0.045), matMouth);
      bar.position.set((i - 2) * 0.09, -0.17, 0.475);
      bar.visible = false;
      headGroup.add(bar);
      mouthBars.push(bar);
    }

    // cuello
    const neck = new THREE.Mesh(new THREE.CylinderGeometry(0.17, 0.2, 0.32, 18), matBlack);
    neck.position.y = 1.0;
    robot.add(neck);

    // brazos compartidos (cada outfit usa su material)
    const armGeo = roundedBoxGeo(THREE, 0.3, 0.68, 0.3, 0.12);
    const handGeo = roundedBoxGeo(THREE, 0.32, 0.24, 0.32, 0.1);

    /* ---------- OUTFIT CAMPO (EPP) ---------- */
    const fieldOutfit = new THREE.Group();
    robot.add(fieldOutfit);

    const fBody = new THREE.Mesh(roundedBoxGeo(THREE, 1.15, 1.5, 0.85, 0.22), matCoverall);
    fBody.position.y = 0.1;
    fieldOutfit.add(fBody);

    [-0.26, 0.26].forEach(function (x) {
      const tape = new THREE.Mesh(roundedBoxGeo(THREE, 0.1, 0.95, 0.87, 0.05), matReflSil);
      tape.position.set(x, 0.37, 0);
      fieldOutfit.add(tape);
    });
    const waist = new THREE.Mesh(roundedBoxGeo(THREE, 1.18, 0.12, 0.9, 0.05), matReflYel);
    waist.position.y = -0.34;
    fieldOutfit.add(waist);

    const fPatch = new THREE.Mesh(new THREE.PlaneGeometry(0.42, 0.23), matLogo);
    fPatch.position.set(0, 0.48, 0.445);
    fieldOutfit.add(fPatch);

    const fArmL = new THREE.Mesh(armGeo, matCoverall);
    fArmL.position.set(-0.82, 0.18, 0);
    const fArmR = fArmL.clone(); fArmR.position.x = 0.82;
    fieldOutfit.add(fArmL, fArmR);
    [-0.82, 0.82].forEach(function (x) {
      const band = new THREE.Mesh(roundedBoxGeo(THREE, 0.32, 0.09, 0.32, 0.04), matReflSil);
      band.position.set(x, 0.1, 0);
      fieldOutfit.add(band);
    });
    const fHandL = new THREE.Mesh(handGeo, matRed);
    fHandL.position.set(-0.82, -0.32, 0);
    const fHandR = fHandL.clone(); fHandR.position.x = 0.82;
    fieldOutfit.add(fHandL, fHandR);

    // casco blanco con lámpara de estado
    const hat = new THREE.Group();
    hat.position.y = 0.50;
    headGroup.add(hat);
    const brim = new THREE.Mesh(new THREE.CylinderGeometry(0.66, 0.7, 0.06, 26), matHelmet);
    brim.scale.z = 1.08;
    brim.position.y = 0.03;
    hat.add(brim);
    const dome = new THREE.Mesh(
      new THREE.SphereGeometry(0.56, 26, 16, 0, Math.PI * 2, 0, Math.PI / 2), matHelmet);
    dome.scale.set(1.0, 0.72, 0.95);
    dome.position.y = 0.05;
    hat.add(dome);
    const ridge = new THREE.Mesh(roundedBoxGeo(THREE, 0.13, 0.08, 1.0, 0.04), matHelmet);
    ridge.position.y = 0.42;
    hat.add(ridge);
    const hatBand = new THREE.Mesh(new THREE.CylinderGeometry(0.575, 0.585, 0.09, 26), matRed);
    hatBand.scale.z = 0.97;
    hatBand.position.y = 0.12;
    hat.add(hatBand);
    const lampBase = new THREE.Mesh(new THREE.CylinderGeometry(0.1, 0.1, 0.09, 16), matBlack);
    lampBase.rotation.x = Math.PI / 2;
    lampBase.position.set(0, 0.16, 0.56);
    hat.add(lampBase);
    const fieldLamp = new THREE.Mesh(new THREE.CircleGeometry(0.08, 20),
      new THREE.MeshBasicMaterial({ color: STATE_META.idle.lamp }));
    fieldLamp.position.set(0, 0.16, 0.615);
    hat.add(fieldLamp);

    // protectores auditivos
    const earGeo = new THREE.CylinderGeometry(0.16, 0.16, 0.12, 16);
    const earL = new THREE.Mesh(earGeo, matRed);
    earL.rotation.z = Math.PI / 2; earL.position.set(-0.64, -0.02, 0);
    const earR = earL.clone(); earR.position.x = 0.64;
    const earsGroup = new THREE.Group();
    earsGroup.add(earL, earR);
    headGroup.add(earsGroup);

    /* ---------- OUTFIT EJECUTIVO ---------- */
    const execOutfit = new THREE.Group();
    robot.add(execOutfit);

    const eShirt = new THREE.Mesh(roundedBoxGeo(THREE, 1.15, 1.05, 0.85, 0.2), matWhite);
    eShirt.position.y = 0.33;
    execOutfit.add(eShirt);
    const ePants = new THREE.Mesh(roundedBoxGeo(THREE, 1.16, 0.62, 0.86, 0.18), matPants);
    ePants.position.y = -0.46;
    execOutfit.add(ePants);
    const belt = new THREE.Mesh(roundedBoxGeo(THREE, 1.17, 0.09, 0.87, 0.04), matBlack);
    belt.position.y = -0.16;
    execOutfit.add(belt);
    [-1, 1].forEach(function (s) {
      const col = new THREE.Mesh(roundedBoxGeo(THREE, 0.26, 0.18, 0.1, 0.04), matWhite);
      col.position.set(s * 0.16, 0.82, 0.36);
      col.rotation.z = s * -0.5;
      execOutfit.add(col);
    });
    [0.62, 0.42, 0.22].forEach(function (y) {
      const btn = new THREE.Mesh(new THREE.CircleGeometry(0.025, 12), matBlack);
      btn.position.set(0, y, 0.446);
      execOutfit.add(btn);
    });
    const pocket = new THREE.Mesh(new THREE.PlaneGeometry(0.3, 0.17), matLogo);
    pocket.position.set(-0.28, 0.55, 0.446);
    execOutfit.add(pocket);
    const pocketLine = new THREE.Mesh(new THREE.PlaneGeometry(0.32, 0.012),
      new THREE.MeshBasicMaterial({ color: 0xd8d2c8 }));
    pocketLine.position.set(-0.28, 0.645, 0.446);
    execOutfit.add(pocketLine);

    const eArmL = new THREE.Mesh(armGeo, matWhite);
    eArmL.position.set(-0.82, 0.18, 0);
    const eArmR = eArmL.clone(); eArmR.position.x = 0.82;
    execOutfit.add(eArmL, eArmR);
    const eHandL = new THREE.Mesh(handGeo, matBlack);
    eHandL.position.set(-0.82, -0.32, 0);
    const eHandR = eHandL.clone(); eHandR.position.x = 0.82;
    execOutfit.add(eHandL, eHandR);

    // antena con lámpara de estado (ejecutivo)
    const antGroup = new THREE.Group();
    headGroup.add(antGroup);
    const stick = new THREE.Mesh(new THREE.CylinderGeometry(0.022, 0.022, 0.24, 8), matBlack);
    stick.position.y = 0.58;
    antGroup.add(stick);
    const execLamp = new THREE.Mesh(new THREE.SphereGeometry(0.07, 16, 16),
      new THREE.MeshBasicMaterial({ color: STATE_META.idle.lamp }));
    execLamp.position.y = 0.72;
    antGroup.add(execLamp);

    // sombra en el piso (solo cuerpo completo)
    let shadow = null;
    if (mode === 'full') {
      shadow = new THREE.Mesh(
        new THREE.CircleGeometry(0.95, 32),
        new THREE.MeshBasicMaterial({ color: 0x0f1419, transparent: true, opacity: 0.16 }));
      shadow.rotation.x = -Math.PI / 2;
      shadow.position.y = -1.15;
      scene.add(shadow);
    }

    /* ---------- estado de la instancia ---------- */
    const inst = {
      el: el,
      renderer: renderer,
      scene: scene,
      camera: camera,
      mouthLevel: 0,
      blinkT: 0,
      nextBlink: 2 + Math.random() * 3,
      w: 0, h: 0,
      applyProfile: function (p) {
        const isField = p === 'field';
        fieldOutfit.visible = isField;
        hat.visible = isField;
        earsGroup.visible = isField;
        execOutfit.visible = !isField;
        antGroup.visible = !isField;
      },
      applyState: function (s) {
        const meta = STATE_META[s] || STATE_META.idle;
        fieldLamp.material.color.setHex(meta.lamp);
        execLamp.material.color.setHex(meta.lamp);
      }
    };

    function resize() {
      inst.w = el.clientWidth;
      inst.h = el.clientHeight;
      if (!inst.w || !inst.h) return;
      renderer.setSize(inst.w, inst.h, false);
      camera.aspect = inst.w / inst.h;
      camera.updateProjectionMatrix();
    }
    if (global.ResizeObserver) new ResizeObserver(resize).observe(el);
    resize();
    // Retry si el contenedor aún no tiene dimensiones al montar (timing Livewire)
    if (!inst.w || !inst.h) {
      setTimeout(resize, 80);
      setTimeout(resize, 350);
    }

    const clock = new THREE.Clock();
    let t = 0;
    function tick() {
      requestAnimationFrame(tick);
      const dt = Math.min(clock.getDelta() || 0.016, 0.05);
      t += dt;

      // flotación
      const bob = mode === 'full' ? 0.06 : 0.03;
      robot.position.y = Math.sin(t * 1.3) * bob;
      if (shadow) shadow.scale.setScalar(1 - Math.sin(t * 1.3) * 0.05);

      // cabeza sigue al cursor
      let lookX = pointer.x * 0.32, lookY = -pointer.y * 0.16;
      if (currentState === 'thinking') { lookX = 0.4; lookY = 0.26; }
      headGroup.rotation.y += (lookX - headGroup.rotation.y) * 0.06;
      headGroup.rotation.x += (-lookY - headGroup.rotation.x) * 0.06;
      robot.rotation.y = headGroup.rotation.y * 0.25;

      // parpadeo
      inst.blinkT += dt;
      let eyeScaleY = 1;
      if (inst.blinkT > inst.nextBlink) {
        const p = (inst.blinkT - inst.nextBlink) / 0.18;
        if (p >= 1) { inst.blinkT = 0; inst.nextBlink = 2 + Math.random() * 3.5; }
        else eyeScaleY = Math.abs(1 - p * 2) * 0.9 + 0.1;
      }
      eyeL.scale.y = eyeR.scale.y = eyeScaleY;

      // lámpara y nivel de boca según estado
      const lamp = currentProfile === 'field' ? fieldLamp : execLamp;
      let lvl = targetLevel;
      if (currentState === 'listening') {
        lamp.scale.setScalar(1 + Math.sin(t * 6) * 0.22);
        lvl = 0.06;
      } else if (currentState === 'thinking') {
        lamp.scale.setScalar(1 + Math.sin(t * 9) * 0.16);
        lvl = 0.04;
      } else if (currentState !== 'speaking') {
        fieldLamp.scale.setScalar(1);
        execLamp.scale.setScalar(1);
        lvl = 0.05 + Math.sin(t * 2) * 0.02;
      }

      const talking = currentState === 'speaking';
      smile.visible = !talking;
      inst.mouthLevel += (lvl - inst.mouthLevel) * 0.35;
      mouthBars.forEach(function (bar, i) {
        bar.visible = talking;
        if (talking) {
          const jitter = 0.4 + Math.abs(Math.sin(t * (9 + i * 2.3) + i)) * 0.6;
          bar.scale.y = (0.04 + inst.mouthLevel * 0.34 * jitter) / 0.045;
        }
      });

      if (inst.w > 0 && inst.h > 0) renderer.render(scene, camera);
    }
    tick();

    inst.applyProfile(currentProfile);
    inst.applyState(currentState);
    return inst;
  }

  /* ---------- montaje ---------- */
  function fallback(el) {
    const src = el.getAttribute('data-fallback-src');
    if (src) {
      el.style.backgroundImage = "url('" + src + "')";
      el.style.backgroundSize = 'cover';
      el.style.backgroundPosition = 'center';
    }
  }

  function mount(el) {
    // Doble guarda: propiedad JS + canvas ya presente (sobrevive a morphdom de Livewire)
    if (!el || el.__gptAvatarMounted || el.querySelector('canvas')) return;
    el.__gptAvatarMounted = true;
    const mode = el.getAttribute('data-avatar-mode') === 'full' ? 'full' : 'head';
    loadThree().then(function () {
      // requestAnimationFrame garantiza que el layout ya aplicó los tamaños
      requestAnimationFrame(function () {
        if (el.querySelector('canvas')) return; // montado en el ínterin
        try {
          instances.push(buildInstance(el, mode));
          // Captura el primer snapshot cuando el robot ya está visible
          setTimeout(updateSnapshot, 600);
        } catch (e) {
          console.error('GPTAvatar:', e);
          fallback(el);
        }
      });
    }).catch(function () { fallback(el); });
  }

  /* ---------- estado / perfil globales ---------- */
  function setState(s) {
    if (!STATE_META[s]) return;
    currentState = s;
    instances.forEach(function (i) { i.applyState(s); });
    stateListeners.forEach(function (cb) {
      try { cb(s); } catch (e) { console.error('GPTAvatar listener:', e); }
    });
  }

  function setProfile(p) {
    currentProfile = (p === 'exec') ? 'exec' : 'field';
    try { localStorage.setItem(PROFILE_KEY, currentProfile); } catch (e) {}
    instances.forEach(function (i) { i.applyProfile(currentProfile); });
    setTimeout(updateSnapshot, 180); // recaptura tras el render del nuevo perfil
  }

  /* ---------- voz: TTS del navegador ---------- */
  const synth = global.speechSynthesis;
  let voices = [];
  function refreshVoices() { voices = synth ? synth.getVoices() : []; }
  if (synth) { refreshVoices(); synth.onvoiceschanged = refreshVoices; }

  function pickVoice() {
    const es = voices.filter(function (v) { return /^es/i.test(v.lang); });
    return es.find(function (v) { return /mx|mexic/i.test(v.lang + v.name); }) || es[0] || null;
  }

  let speakTimer = null;
  let onEndCb = null;

  function endSpeech() {
    if (speakTimer) { clearInterval(speakTimer); speakTimer = null; }
    targetLevel = 0;
    setState('idle');
    if (onEndCb) { const cb = onEndCb; onEndCb = null; cb(); }
  }

  function speak(text, opts) {
    opts = opts || {};
    stop();
    if (!synth || !text) { if (opts.onend) opts.onend(); return; }
    const u = new SpeechSynthesisUtterance(text);
    const v = pickVoice();
    if (v) { u.voice = v; u.lang = v.lang; } else { u.lang = 'es-MX'; }
    u.rate = 1; u.pitch = 1.05;
    onEndCb = opts.onend || null;
    u.onstart = function () {
      setState('speaking');
      speakTimer = setInterval(function () { targetLevel = 0.25 + Math.random() * 0.6; }, 90);
    };
    u.onboundary = function () { targetLevel = 0.45 + Math.random() * 0.5; };
    u.onend = u.onerror = function () { endSpeech(); };
    synth.speak(u);
  }

  /* ---------- voz: audio del backend (boca por amplitud real) ---------- */
  let audioCtx = null, audioEl = null, rafAudio = null;
  function playAudio(src, opts) {
    opts = opts || {};
    stop();
    onEndCb = opts.onend || null;
    audioCtx = audioCtx || new (global.AudioContext || global.webkitAudioContext)();
    audioEl = new Audio(src);
    audioEl.crossOrigin = 'anonymous';
    const node = audioCtx.createMediaElementSource(audioEl);
    const analyser = audioCtx.createAnalyser();
    analyser.fftSize = 256;
    node.connect(analyser);
    analyser.connect(audioCtx.destination);
    const data = new Uint8Array(analyser.frequencyBinCount);
    function tickAudio() {
      analyser.getByteFrequencyData(data);
      let sum = 0;
      for (let i = 2; i < 40; i++) sum += data[i];
      targetLevel = Math.min(1, (sum / 38) / 110);
      rafAudio = requestAnimationFrame(tickAudio);
    }
    audioEl.onplay = function () { setState('speaking'); tickAudio(); };
    audioEl.onended = audioEl.onerror = function () {
      if (rafAudio) cancelAnimationFrame(rafAudio);
      audioEl = null;
      endSpeech();
    };
    audioEl.play();
  }

  function stop() {
    if (synth) synth.cancel();
    if (audioEl) { audioEl.pause(); audioEl = null; }
    if (rafAudio) { cancelAnimationFrame(rafAudio); rafAudio = null; }
    endSpeech();
  }

  /* ---------- snapshot: captura un frame del canvas y propaga a .evia-avatar-snapshot ---------- */
  var _snapshotUrl = null;

  function updateSnapshot() {
    // Usa la instancia más grande disponible (mejor calidad)
    var best = null;
    instances.forEach(function (i) {
      if (i.w > 0 && (!best || i.w > best.w)) best = i;
    });
    if (!best) return;
    try {
      best.renderer.render(best.scene, best.camera); // fuerza render antes de toDataURL
      _snapshotUrl = best.renderer.domElement.toDataURL('image/png');
      document.querySelectorAll('.evia-avatar-snapshot').forEach(function (img) {
        img.src = _snapshotUrl;
      });
    } catch (e) { /* cross-origin u otro error — silencioso */ }
  }

  /* ---------- API pública ---------- */
  global.GPTAvatar = {
    mount: mount,
    setState: setState,
    setProfile: setProfile,
    getProfile: function () { return currentProfile; },
    getState: function () { return currentState; },
    onStateChange: function (cb) { if (typeof cb === 'function') stateListeners.push(cb); },
    speak: speak,
    playAudio: playAudio,
    stop: stop,
    updateSnapshot: updateSnapshot,
    getSnapshotUrl: function () { return _snapshotUrl; }
  };
})(window);
