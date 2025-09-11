@extends('layouts.guest')

@section('content')
<div class="flex justify-center">

    <div class="card bg-white rounded-xl shadow bg-opacity-95" style="width: 400px; height: 300px; margin-top: 10%;">

        <div class="flex justify-between items-center p-8">
            <h1 class="text-xl font-bold">¡Bienvenido!</h1>
            <img src="{{ asset('storage/img/logo.png') }}" alt="Logo" style="width: 80px;">
        </div>

        
        <div class="card-body px-8">

            <p>Para acceder, requerimos que te identifques con tu cuenta empresarial de Google.</p>
            <br>
            <a href="" class="text-center border-solid border-2 border-stone-500 p-2 rounded-full flex">             
                <img src="{{ asset('storage/img/google.png') }}" alt="Logo google" style="width: 30px;"> <p class="ml-10 mt-0.5">Iniciar sesión con Google </p>
            </a>

            <div class="flex justify-end mt-4">
                <a href="" class="underline">¿No cuentas con acceso?</a>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
              
                <br>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" class="form-control form-control-xl  @error('email') is-invalid @enderror"
                        name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email"
                        autofocus>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                        placeholder="Password" name="password" required autocomplete="current-password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }} id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                <button type="submit" class="boton btn-block btn-lg shadow-lg mt-5"> {{ __('Login') }}</button>
                {{-- @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif --}}
            </form>
        </div>
    </div>
</div>
@endsection