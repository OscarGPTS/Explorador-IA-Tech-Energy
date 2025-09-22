@extends('layouts.app')

@section('content')
<div class="px-4 pt-10">

    <div class="flex justify-between items-center ml-4">
        <p class="font-bold ml-2 text-lg flex"> 
            <a href="/" class="mr-2 flex items-center justify-center">
                <svg width="22px" height="22px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                    <path fill="#000000" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                </svg>
            </a>
            Novedades y Noticias
        </p>

        <div class="mr-4">
            <button data-modal-target="top-right-modal" data-modal-toggle="top-right-modal" 
                class="flex block w-full md:w-auto bg-[#FFDE72] hover:bg-[#FFD03A] 
                       focus:ring-4 focus:outline-none focus:ring-yellow-300 
                       font-medium rounded-full text-sm px-5 py-2.5 text-center" 
                type="button">
                <p class="mr-4 text-base">Personalizar</p> 
                <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.2788 2.15224C13.9085 2 13.439 2 12.5 2C11.561 2 11.0915 2 10.7212 2.15224C10.2274 2.35523 9.83509 2.74458 9.63056 3.23463C9.53719 3.45834 9.50065 3.7185 9.48635 4.09799C9.46534 4.65568 9.17716 5.17189 8.69017 5.45093C8.20318 5.72996 7.60864 5.71954 7.11149 5.45876C6.77318 5.2813 6.52789 5.18262 6.28599 5.15102C5.75609 5.08178 5.22018 5.22429 4.79616 5.5472C4.47814 5.78938 4.24339 6.1929 3.7739 6.99993C3.30441 7.80697 3.06967 8.21048 3.01735 8.60491C2.94758 9.1308 3.09118 9.66266 3.41655 10.0835C3.56506 10.2756 3.77377 10.437 4.0977 10.639C4.57391 10.936 4.88032 11.4419 4.88029 12C4.88026 12.5581 4.57386 13.0639 4.0977 13.3608C3.77372 13.5629 3.56497 13.7244 3.41645 13.9165C3.09108 14.3373 2.94749 14.8691 3.01725 15.395C3.06957 15.7894 3.30432 16.193 3.7738 17C4.24329 17.807 4.47804 18.2106 4.79606 18.4527C5.22008 18.7756 5.75599 18.9181 6.28589 18.8489C6.52778 18.8173 6.77305 18.7186 7.11133 18.5412C7.60852 18.2804 8.2031 18.27 8.69012 18.549C9.17714 18.8281 9.46533 19.3443 9.48635 19.9021C9.50065 20.2815 9.53719 20.5417 9.63056 20.7654C9.83509 21.2554 10.2274 21.6448 10.7212 21.8478C11.0915 22 11.561 22 12.5 22C13.439 22 13.9085 22 14.2788 21.8478C14.7726 21.6448 15.1649 21.2554 15.3694 20.7654C15.4628 20.5417 15.4994 20.2815 15.5137 19.902C15.5347 19.3443 15.8228 18.8281 16.3098 18.549C16.7968 18.2699 17.3914 18.2804 17.8886 18.5412C18.2269 18.7186 18.4721 18.8172 18.714 18.8488C19.2439 18.9181 19.7798 18.7756 20.2038 18.4527C20.5219 18.2105 20.7566 17.807 21.2261 16.9999C21.6956 16.1929 21.9303 15.7894 21.9827 15.395C22.0524 14.8691 21.9088 14.3372 21.5835 13.9164C21.4349 13.7243 21.2262 13.5628 20.9022 13.3608C20.4261 13.0639 20.1197 12.558 20.1197 11.9999C20.1197 11.4418 20.4261 10.9361 20.9022 10.6392C21.2263 10.4371 21.435 10.2757 21.5836 10.0835C21.9089 9.66273 22.0525 9.13087 21.9828 8.60497C21.9304 8.21055 21.6957 7.80703 21.2262 7C20.7567 6.19297 20.522 5.78945 20.2039 5.54727C19.7799 5.22436 19.244 5.08185 18.7141 5.15109C18.4722 5.18269 18.2269 5.28136 17.8887 5.4588C17.3915 5.71959 16.7969 5.73002 16.3099 5.45096C15.8229 5.17191 15.5347 4.65566 15.5136 4.09794C15.4993 3.71848 15.4628 3.45833 15.3694 3.23463C15.1649 2.74458 14.7726 2.35523 14.2788 2.15224ZM12.5 15C14.1695 15 15.5228 13.6569 15.5228 12C15.5228 10.3431 14.1695 9 12.5 9C10.8305 9 9.47716 10.3431 9.47716 12C9.47716 13.6569 10.8305 15 12.5 15Z" fill="#1C274C"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="top-right-modal" data-modal-placement="top-right" tabindex="-1" 
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto 
               md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200 dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">Personaliza tus noticias</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 
                                               hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto 
                                               inline-flex justify-center items-center dark:hover:bg-gray-600 
                                               dark:hover:text-white" 
                            data-modal-hide="top-right-modal">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="p-4 md:p-5 space-y-4">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                        Selecciona las categorías de noticias que más te interesen.
                    </p>

                    <form action="{{ route('news.updatePreferences') }}" method="POST">
                        @csrf
                        @foreach ($news as $id => $name)
                            <div class="flex items-center mb-4">
                                <input id="checkbox-{{ $id }}" type="checkbox" name="news[]" value="{{ $id }}" 
                                    class="w-4 h-4 accent-yellow-400 bg-gray-100 border-gray-300 rounded 
                                           focus:ring-yellow-500 focus:ring-2"
                                    @if(in_array($id, $userNewsIds)) checked @endif>
                                <label for="checkbox-{{ $id }}" 
                                       class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $name }}</label>
                            </div>
                        @endforeach

                        <div class="flex">
                            <button type="submit" 
                                class="block w-full md:w-auto bg-[#FFDE72] hover:bg-[#FFD03A] 
                                       focus:ring-4 focus:outline-none focus:ring-yellow-300 
                                       font-medium rounded-full text-sm px-5 py-2.5 text-center">
                                Guardar
                            </button>
                            <button data-modal-hide="top-right-modal" type="button" 
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 
                                       bg-white rounded-full border border-[#FFD03A] 
                                       hover:bg-yellow-100 hover:text-yellow-700 
                                       focus:outline-none focus:ring-4 focus:ring-yellow-100">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 mt-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            @foreach ($newsData as $category) 
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg 
                       border-transparent text-gray-500 hover:text-red-600 hover:border-red-600 
                       aria-selected:border-red-600 aria-selected:text-red-600" 
                        id="tab-{{ $category->id }}" 
                        data-tabs-target="#content-{{ $category->id }}" 
                        type="button" role="tab" aria-controls="content-{{ $category->id }}" aria-selected="false">
                        {{ $category->category }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div id="default-tab-content">
        @foreach ($newsData as $category) 
            <div class="hidden p-4 rounded-lg" id="content-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($category->news as $item)
                        <div class="flex flex-col h-full p-4 border rounded-lg bg-white">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $item->title }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-300 line-clamp-3 overflow-hidden" style="height: 80px;">
                                {{ $item->description }}
                            </p>
                            <div class="mt-auto flex justify-end">
                                <button data-modal-target="modal-{{ $item->id }}" data-modal-toggle="modal-{{ $item->id }}" 
                                    class="block text-gray-800 bg-[#FFDE72] hover:bg-[#FFD03A] 
                                           focus:ring-4 focus:outline-none focus:ring-yellow-300 
                                           font-medium rounded-full text-sm px-5 py-2.5 text-center">
                                    Más detalles
                                </button>
                            </div>

                            <div id="modal-{{ $item->id }}" tabindex="-1" aria-hidden="true" 
                                class="hidden fixed inset-0 z-50 w-full p-4 overflow-x-hidden 
                                       overflow-y-auto h-[calc(100%-1rem)] max-h-full">
                                <div class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80" data-modal-hide="modal-{{ $item->id }}"></div>
                                <div class="relative w-full max-w-2xl max-h-full mx-auto mt-20">
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $item->title }}</h3>
                                            <button type="button" 
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 
                                                       rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center 
                                                       items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                                                data-modal-hide="modal-{{ $item->id }}">
                                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="p-4 md:p-5 space-y-4">
                                            <p class="text-gray-700">{{ $item->description }}</p>
                                            <p class="text-base leading-relaxed text-gray-700 dark:text-gray-400">{{ $item->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection