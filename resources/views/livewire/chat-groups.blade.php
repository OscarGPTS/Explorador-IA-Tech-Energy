<div class="p-4">
    <h2 class="font-bold mb-2">Tus conversaciones</h2>
    <ul>
        @if($groups)
            @foreach($groups as $g)
                <li><a href="" class="text-blue-600">{{ $g->name ?? 'Sin nombre' }} — {{ $g->created_at->diffForHumans() }}</a></li>
            @endforeach
        @endif
       
    </ul>

    <div class="mt-4">
        <input wire:model="name" placeholder="Nombre del hilo" class="border p-2 rounded"/>
        <button wire:click="create" class="ml-2 bg-blue-600 text-white px-3 py-1 rounded">Crear</button>
    </div>
</div>