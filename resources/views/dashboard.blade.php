@extends('layouts.app')

@section('content')
      
<div class="px-4 pt-6">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[60%_40%] gap-4 p-4">
    <div class="border-2 border-solid rounded-lg p-4">
      <div class="flex ml-4">
        <div class="w-2 h-6 bg-red-500"></div>
        <p class="font-bold ml-2 text-lg"> Mis aplicaciones</p>
      </div>

       {{-- Se debe iterar por cada elemento en el futuro --}}
        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 gap-4 p-4">
          <a href="{{  route('chat.index') }}">
            <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
              <img src="{{  asset('storage/img/buscador.png') }}" alt="buscador" style="width: 120px; height: 120px; object-fit:contain" class="p-4">
              <p  class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Buscador Inteligente</p>
            </div>
          </a>
          

          <a href="{{ route('recommendations.index') }}">
            <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
              <img src="{{  asset('storage/img/recomendaciones.png') }}" alt="buscador" style="width: 120px; height: 120px; object-fit:contain;" class="p-4">
              <p class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Recomendaciones</p>
            </div>
          </a> 

          <a href="{{ route('news.index') }}">
            <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
              <img src="{{  asset('storage/img/noticias.png') }}" alt="buscador" style="width: 120px; height: 120px; object-fit:contain;" class="p-4">
              <p class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Noticias</p>
            </div>
          </a>
          

          <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
            <p class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Proximamente</p>
          </div>

          <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
            <p class="text-white font-semibold mt-4">Proximamente</p>
          </div>

          <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
            <p class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Proximamente</p>
          </div>

          <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
            <p class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Proximamente</p>
          </div>

          <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
            <p class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Proximamente</p>
          </div>

          <div class="bg-[#2F5249] rounded-lg flex flex-col items-center justify-center p-6" style="min-height: 200px">
            <p class="text-white font-semibold mt-4 text-sm sm:text-base md:text-lg lg:text-xl text-center">Proximamente</p>
          </div>


      </div>
      
    </div>
    <div class="">
      <div class="border-2 border-solid rounded-lg p-4 mb-4">
          <p class="font-bold ml-2 text-lg">Novedades</p>

        </div>
        <div class="border-2 border-solid rounded-lg p-4 mb-4">
                      <p class="font-bold ml-2 text-lg">Eventos</p>

        </div>
       
    </div>
  </div>
</div>


@endsection