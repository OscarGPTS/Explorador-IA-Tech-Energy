<nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
       
        <a href="{{ "/"  }}" class="flex ml-2 md:mr-24">
          <img src="{{ asset('storage/img/logo.png') }}" class="h-8 mr-3" alt="Logo" />
          <span class="hidden sm:inline self-center text-xl font-semibold whitespace-nowrap">Explorador IA</span>
        </a>

      </div>
      <div class="flex items-center">
        
        

          <!-- Perfil -->
          <div class="flex items-center ml-3">
            <div class="flex items-center space-x-3">
              <p class="hidden sm:block text-sm font-semibold">{{ auth()->user()->name }}</p>

              <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
                <span class="sr-only">Open user menu</span>
                <img class="w-8 h-8 rounded-full" src="{{ isset(auth()->user()->google_image)?auth()->user()->google_image: ""  }}" alt="user photo">
              </button>
            </div>
            <!-- Dropdown menu -->
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-2">
              <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900 dark:text-white" role="none">
                  {{ auth()->user()->name }}
                </p>
                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                  {{ auth()->user()->email }}
                </p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Mi Perfil</a>
                </li>
          

                <li>
                  <button data-modal-target="logout-modal" data-modal-toggle="logout-modal" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 
                                dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" type="button">
                    Cerrar sesión
                  </button>
                </li>
              </ul>
            </div>
          </div>


            <!-- Apps -->
          <button type="button" data-dropdown-toggle="apps-dropdown" class="hidden p-2 text-gray-500 rounded-lg sm:flex hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
            <span class="sr-only">View notifications</span>
            <!-- Icon -->
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
          </button>
          <!-- Dropdown menu -->
          <div class="z-20 z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="apps-dropdown">
            <div class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                Mis aplicaciones
            </div>
            <div class="grid grid-cols-3 gap-4 p-4">

              <a href="{{ route('chat.index') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400"  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14.9536 14.9458L21 21M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Buscador Inteligente</div>
              </a>


              <a href="{{ route('recommendations.index') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    
                  <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Dribbble-Light-Preview" transform="translate(-219.000000, -760.000000)" fill="#000000">
                      <g id="icons" transform="translate(56.000000, 160.000000)">
                        <path d="M163,610.021159 L163,618.021159 C163,619.126159 163.93975,620.000159 165.1,620.000159 L167.199999,620.000159 L167.199999,608.000159 L165.1,608.000159 C163.93975,608.000159 163,608.916159 163,610.021159 M183.925446,611.355159 L182.100546,617.890159 C181.800246,619.131159 180.639996,620.000159 179.302297,620.000159 L169.299999,620.000159 L169.299999,608.021159 L171.104948,601.826159 C171.318098,600.509159 172.754498,599.625159 174.209798,600.157159 C175.080247,600.476159 175.599997,601.339159 175.599997,602.228159 L175.599997,607.021159 C175.599997,607.573159 176.070397,608.000159 176.649997,608.000159 L181.127196,608.000159 C182.974146,608.000159 184.340196,609.642159 183.925446,611.355159" id="like-[#1386]">

                        </path>
                      </g>
                    </g>
                  </g>
                </svg>


                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">
                    Recomendaciones
                </div>
              </a>


              <a href="{{ route('news.index') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">

                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="ic_fluent_news_28_filled" fill="#212121" fill-rule="nonzero">
                        <path d="M22,5.75 L22,20.5 C22,20.7761424 22.2238576,21 22.5,21 C22.7454599,21 22.9496084,20.8231248 22.9919443,20.5898756 L23,20.5 L23,7 L24.25,7 C25.1681734,7 25.9211923,7.70711027 25.9941988,8.60647279 L26,8.75 L26,20.75 C26,22.4830315 24.6435452,23.8992459 22.9344239,23.9948552 L22.75,24 L5.25,24 C3.51696854,24 2.10075407,22.6435452 2.00514479,20.9344239 L2,20.75 L2,5.75 C2,4.8318266 2.70711027,4.07880766 3.60647279,4.0058012 L3.75,4 L20.25,4 C21.1681734,4 21.9211923,4.70711027 21.9941988,5.60647279 L22,5.75 L22,20.5 L22,5.75 Z M9.74652744,13.0034726 L7.25,13.0034726 C6.3318266,13.0034726 5.57880766,13.7105828 5.5058012,14.6099454 L5.5,14.7534726 L5.5,17.25 C5.5,18.1681734 6.20711027,18.9211923 7.10647279,18.9941988 L7.25,19 L9.74652744,19 C10.6647008,19 11.4177198,18.2928897 11.4907262,17.3935272 L11.4965274,17.25 L11.4965274,14.7534726 C11.4965274,13.8352992 10.7894172,13.0822802 9.89005465,13.0092738 L9.74652744,13.0034726 Z M17.75,17.5 L14.25,17.5 L14.1482294,17.5068466 C13.7821539,17.556509 13.5,17.8703042 13.5,18.25 C13.5,18.6296958 13.7821539,18.943491 14.1482294,18.9931534 L14.25,19 L17.75,19 L17.8517706,18.9931534 C18.2178461,18.943491 18.5,18.6296958 18.5,18.25 C18.5,17.8703042 18.2178461,17.556509 17.8517706,17.5068466 L17.75,17.5 Z M7.25,14.5034726 L9.74652744,14.5034726 C9.86487417,14.5034726 9.96401426,14.585706 9.98992476,14.6961499 L9.99652744,14.7534726 L9.99652744,17.25 C9.99652744,17.3683467 9.91429402,17.4674868 9.80385014,17.4933973 L9.74652744,17.5 L7.25,17.5 C7.13165327,17.5 7.03251318,17.4177666 7.00660268,17.3073227 L7,17.25 L7,14.7534726 C7,14.6351258 7.08223341,14.5359857 7.19267729,14.5100752 L7.25,14.5034726 L9.74652744,14.5034726 L7.25,14.5034726 Z M17.75,13.0034726 L14.25,13.0034726 L14.1482294,13.0103192 C13.7821539,13.0599816 13.5,13.3737768 13.5,13.7534726 C13.5,14.1331683 13.7821539,14.4469635 14.1482294,14.4966259 L14.25,14.5034726 L17.75,14.5034726 L17.8517706,14.4966259 C18.2178461,14.4469635 18.5,14.1331683 18.5,13.7534726 C18.5,13.3737768 18.2178461,13.0599816 17.8517706,13.0103192 L17.75,13.0034726 Z M17.75,8.49665793 L6.25,8.49665793 L6.14822944,8.50350455 C5.78215388,8.55316697 5.5,8.86696217 5.5,9.24665793 C5.5,9.6263537 5.78215388,9.94014889 6.14822944,9.98981132 L6.25,9.99665793 L17.75,9.99665793 L17.8517706,9.98981132 C18.2178461,9.94014889 18.5,9.6263537 18.5,9.24665793 C18.5,8.86696217 18.2178461,8.55316697 17.8517706,8.50350455 L17.75,8.49665793 Z" id="🎨-Color">

                        </path>
                    </g>
                  </g>
                </svg>

                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Noticias</div>
              </a>


              <a href="{{ route('admin.stats.dashboard') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3 4C3 3.44772 3.44772 3 4 3H20C20.5523 3 21 3.44772 21 4V7C21 7.55228 20.5523 8 20 8H4C3.44772 8 3 7.55228 3 7V4Z" stroke="currentColor" stroke-width="2"/>
                  <path d="M3 12C3 11.4477 3.44772 11 4 11H20C20.5523 11 21 11.4477 21 12V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V12Z" stroke="currentColor" stroke-width="2"/>
                  <path d="M7 15L10 18L17 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Estadísticas</div>
              </a>

              <a href="{{ route('admin.employees.index') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2"/>
                  <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="currentColor" stroke-width="2"/>
                </svg>
                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Empleados</div>
              </a>

              <a href="{{ route('tech-support.index') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M21 8C21 6.34315 19.6569 5 18 5H6C4.34315 5 3 6.34315 3 8V16C3 17.6569 4.34315 19 6 19H18C19.6569 19 21 17.6569 21 16V8Z" stroke="currentColor" stroke-width="2"/>
                  <path d="M7 10H7.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M11 10H11.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M15 10H15.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M7 14H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Soporte Técnico</div>
              </a>




              {{-- <a href="{{ route('agent.config.view') }}" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1H5C3.9 1 3 1.9 3 3V21C3 22.1 3.9 23 5 23H19C20.1 23 21 22.1 21 21V9H21ZM19 21H5V3H13V9H19V21Z" fill="currentColor"/>
                  <circle cx="9" cy="13" r="1.5" fill="currentColor"/>
                  <circle cx="15" cy="13" r="1.5" fill="currentColor"/>
                  <path d="M9 16.5C9 17.3284 9.67157 18 10.5 18H13.5C14.3284 18 15 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Configurar IA</div>
              </a> --}}

              <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="#000000" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-h</title><path d="M469.54,120.52h0a16,16,0,0,0-25.54-4L382.56,178a16.12,16.12,0,0,1-22.63,0L333.37,151.4a16,16,0,0,1,0-22.63l61.18-61.19a16,16,0,0,0-4.78-25.92h0C343.56,21,285.88,31.78,249.51,67.88c-30.9,30.68-40.11,78.62-25.25,131.53a15.89,15.89,0,0,1-4.49,16L53.29,367.46a64.17,64.17,0,1,0,90.6,90.64L297.57,291.25a15.9,15.9,0,0,1,15.77-4.57,179.3,179.3,0,0,0,46.22,6.37c33.4,0,62.71-10.81,83.85-31.64C482.56,222.84,488.53,157.42,469.54,120.52ZM99.48,447.15a32,32,0,1,1,28.34-28.35A32,32,0,0,1,99.48,447.15Z"/></svg>
                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Proximamente</div>
              </a>
              <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="#000000" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-h</title><path d="M469.54,120.52h0a16,16,0,0,0-25.54-4L382.56,178a16.12,16.12,0,0,1-22.63,0L333.37,151.4a16,16,0,0,1,0-22.63l61.18-61.19a16,16,0,0,0-4.78-25.92h0C343.56,21,285.88,31.78,249.51,67.88c-30.9,30.68-40.11,78.62-25.25,131.53a15.89,15.89,0,0,1-4.49,16L53.29,367.46a64.17,64.17,0,1,0,90.6,90.64L297.57,291.25a15.9,15.9,0,0,1,15.77-4.57,179.3,179.3,0,0,0,46.22,6.37c33.4,0,62.71-10.81,83.85-31.64C482.56,222.84,488.53,157.42,469.54,120.52ZM99.48,447.15a32,32,0,1,1,28.34-28.35A32,32,0,0,1,99.48,447.15Z"/></svg>
                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Proximamente</div>
              </a>
              
              <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="#000000" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-h</title><path d="M469.54,120.52h0a16,16,0,0,0-25.54-4L382.56,178a16.12,16.12,0,0,1-22.63,0L333.37,151.4a16,16,0,0,1,0-22.63l61.18-61.19a16,16,0,0,0-4.78-25.92h0C343.56,21,285.88,31.78,249.51,67.88c-30.9,30.68-40.11,78.62-25.25,131.53a15.89,15.89,0,0,1-4.49,16L53.29,367.46a64.17,64.17,0,1,0,90.6,90.64L297.57,291.25a15.9,15.9,0,0,1,15.77-4.57,179.3,179.3,0,0,0,46.22,6.37c33.4,0,62.71-10.81,83.85-31.64C482.56,222.84,488.53,157.42,469.54,120.52ZM99.48,447.15a32,32,0,1,1,28.34-28.35A32,32,0,0,1,99.48,447.15Z"/></svg>
                  <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Proximamente</div>
              </a>
              <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="#000000" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-h</title><path d="M469.54,120.52h0a16,16,0,0,0-25.54-4L382.56,178a16.12,16.12,0,0,1-22.63,0L333.37,151.4a16,16,0,0,1,0-22.63l61.18-61.19a16,16,0,0,0-4.78-25.92h0C343.56,21,285.88,31.78,249.51,67.88c-30.9,30.68-40.11,78.62-25.25,131.53a15.89,15.89,0,0,1-4.49,16L53.29,367.46a64.17,64.17,0,1,0,90.6,90.64L297.57,291.25a15.9,15.9,0,0,1,15.77-4.57,179.3,179.3,0,0,0,46.22,6.37c33.4,0,62.71-10.81,83.85-31.64C482.56,222.84,488.53,157.42,469.54,120.52ZM99.48,447.15a32,32,0,1,1,28.34-28.35A32,32,0,0,1,99.48,447.15Z"/></svg>
                <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Proximamente</div>
              </a>

              <a href="#" class="block p-4 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="mx-auto mb-1 text-gray-500 w-7 h-7 dark:text-gray-400" fill="#000000" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><title>ionicons-v5-h</title><path d="M469.54,120.52h0a16,16,0,0,0-25.54-4L382.56,178a16.12,16.12,0,0,1-22.63,0L333.37,151.4a16,16,0,0,1,0-22.63l61.18-61.19a16,16,0,0,0-4.78-25.92h0C343.56,21,285.88,31.78,249.51,67.88c-30.9,30.68-40.11,78.62-25.25,131.53a15.89,15.89,0,0,1-4.49,16L53.29,367.46a64.17,64.17,0,1,0,90.6,90.64L297.57,291.25a15.9,15.9,0,0,1,15.77-4.57,179.3,179.3,0,0,0,46.22,6.37c33.4,0,62.71-10.81,83.85-31.64C482.56,222.84,488.53,157.42,469.54,120.52ZM99.48,447.15a32,32,0,1,1,28.34-28.35A32,32,0,0,1,99.48,447.15Z"/></svg>
                  <div class="text-sm font-medium text-gray-900 dark:text-white break-words whitespace-normal">Proximamente</div>
              </a>
            </div>
          </div>

          <div id="logout-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="logout-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">¿Desea cerrar sesión?</h3>
                        
                        <div class="flex justify-center">

                            <form method="POST" action="{{ route('google.logout') }}" class="block">
                                @csrf
                                
                              <button data-modal-hide="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                  Si, Cerrar Sesión
                              </button>
                            </form>
                            
                            <button data-modal-hide="logout-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">No, Cancelar</button>
                        </div>
              
                    </div>
                </div>
            </div>
          </div>
          
        </div>
    </div>
  </div>
</nav>