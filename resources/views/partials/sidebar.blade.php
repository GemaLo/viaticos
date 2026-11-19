<div class="flex h-screen p-12 fixed left-0 top-0 h-full ">
    <div class="relative">
        <div id="sidebarCompact"
            class="flex flex-col items-center w-16 h-full overflow-hidden text-white bg-[#611232]  transition-all duration-300"
            style="border-radius: 15px">
            <a class="flex items-center justify-center mt-3" href="" onclick="toggleSidebar()">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                </svg>
            </a>
            <div class="flex flex-col items-center mt-3 border-t border-gray-300 bg-[#611232]">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300"
                    href="{{ Auth::user()->idtype == 1 ? route('admin.dashboard') : route('dashboard.index') }}">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
            </div>
            @if (Auth::user()->idtype != 3)
                <div class="flex flex-col items-center mt-3 border-t border-gray-300">
                    <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 bg-[#611232]"
                        href="{{ route('validateComition') }}">
                        <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </a>
                    <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300"
                        href="{{ route('users') }}">
                        <svg class="w-6 h-6 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14c-3.866 0-7 3.134-7 7h14c0-3.866-3.134-7-7-7zM12 14c2.761 0 5-2.239 5-5S14.761 4 12 4 7 6.239 7 9s2.239 5 5 5z" />
                        </svg>
                    </a>
                    <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300"
                        href="{{ route('comprobacionExcel') }}">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
                @if (Auth::user()->idtype == 1)
                    <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300"
                        href="{{ route('database.index') }}"> <svg class="w-6 h-6 stroke-current"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                    </a>
                @endif
            @endif
            <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300" href=""
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" />
                </svg>
            </a>
        </div>
        <div id="sidebarExtended"
            class="hidden flex flex-col items-center w-40 h-full overflow-hidden text-white bg-[#611232] rounded transition-all duration-300">
            <a class="flex items-center w-full px-3 mt-3" href="#" onclick="toggleSidebar()">
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                </svg>
                <span class="ml-2 text-sm font-bold" style="ali"></span>
            </a>
            <div class="w-full px-2">
                <div class="flex flex-col items-center w-full mt-3 border-t border-gray-300">
                    <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300"
                        href="{{ Auth::user()->idtype == 1 ? route('admin.dashboard') : route('dashboard.index') }}">
                        <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ml-2 text-sm font-medium">Inicio</span>
                    </a>
                </div>
                @if (Auth::user()->idtype != 3)
                    <div class="flex flex-col items-center w-full mt-3 border-t border-gray-300">
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300"
                            href="{{ route('validateComition') }}">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Validar comisiones</span>
                        </a>
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300"
                            href="{{ route('users') }}">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14c-3.866 0-7 3.134-7 7h14c0-3.866-3.134-7-7-7zM12 14c2.761 0 5-2.239 5-5S14.761 4 12 4 7 6.239 7 9s2.239 5 5 5z" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Usuarios</span>
                        </a>
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300"
                            href="{{ route('comprobacionExcel') }}">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Subir Excel</span>
                        </a>
                    </div>
                    @if (Auth::user()->idtype == 1)
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300"
                            href="{{ route('database.index') }}"> <svg class="w-6 h-6 stroke-current"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Objetivos</span>
                        </a>
                    @endif
                @endif
                {{-- <div class="flex flex-col items-center w-full mt-3 border-t border-gray-300">
                    <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="">
                        <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        <span class="ml-2 text-sm font-medium">Consulta recibos</span>
                    </a>
                </div> --}}
                <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                    <span class="ml-2 text-sm font-medium">Cerrar sesión</span>
                </a>
            </div>
        </div>

    </div>

    <script>
        function toggleSidebar() {
            event.preventDefault();
            const compactSidebar = document.getElementById("sidebarCompact");
            const extendedSidebar = document.getElementById("sidebarExtended");
            const mainContent = document.getElementById("mainContent");

            if (compactSidebar.classList.contains("hidden")) {
                compactSidebar.classList.remove("hidden");
                extendedSidebar.classList.add("hidden");
                // Asegúrate de que mainContent exista antes de intentar modificar sus clases
                if (mainContent) {
                    mainContent.classList.remove("ml-40");
                    mainContent.classList.add("ml-16");
                }
            } else {
                compactSidebar.classList.add("hidden");
                extendedSidebar.classList.remove("hidden");
                // Asegúrate de que mainContent exista antes de intentar modificar sus clases
                if (mainContent) {
                    mainContent.classList.remove("ml-16");
                    mainContent.classList.add("ml-40");
                }
            }
        }
    </script>
    <style>
        #sidebarExtended svg {
            color: white !important;
        }
    </style>
</div>

<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    @csrf
</form>
