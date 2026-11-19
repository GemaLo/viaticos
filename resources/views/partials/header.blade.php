<header class="mb-3">
    <nav class="navbar navbar-expand navbar-light">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link active dropdown-toggle" href="#" id="dropdownMenuButton" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">Notification 1</a></li>
                            <li><a class="dropdown-item" href="#">Notification 2</a></li>
                            <li><a class="dropdown-item" href="#">Notification 3</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-menu d-flex">
                                <div class="user-name text-end me-3">
                                    {{-- <!-- <h6>{{ Auth::user()->name ?? 'Admin' }}</h6> --> --}}
                                    <h6>Admin</h6>
                                    <!-- <p class="mb-0 text-sm">Administrator</p> -->
                                    <p class="mb-0 text-sm">Administrator</p>
                                </div>
                                <div class="user-img d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                        {{-- <!-- <img src="{{ asset('mazer/images/faces/1.jpg') }}" alt="User Avatar"> --> --}}
                                        <img src="/mazer/images/faces/1.jpg" alt="User Avatar">
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <!--
                                {{-- <a class="dropdown-item" href="{{ route('logout') }}" --}}
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                -->
                                <a class="dropdown-item" href="#">Logout</a>
                                <!--
                                {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> --}}
                                    @csrf
                                </form>
                                -->
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
