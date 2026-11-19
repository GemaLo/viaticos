<nav class="navbar mb-5">
    <div class="menu-icon" onclick="toggleMenu()">⋮</div>
    <div class="navbar-logo"><img src="{{ asset('images/logo_blanco.svg') }}" alt="" class="img-logo"></div>


    @if (Auth::check() && asset(Auth::user()->resetpass == 0))

    <!-- Icono de Menú (3 puntos) -->

    <ul class="nav-list">
        <div class="button-li"><li><a href="{{ route('dashboard')}}">Inicio</a></li></div>
        <div class="button-li"><li><a href="{{ route('consultaNomina')}}">Recibos de nómina</a></li></div>
        {{-- <div class="button-li"><li><a href="#">SSPC</a></li></div> --}}
        <div class="button-li"><li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Salir
        </a></li></div>
    </ul>
    @endif

</nav>
<br>

<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    @csrf
</form>
<script>
    function toggleMenu() {
        document.querySelector('.nav-list').classList.toggle('show-menu');
    }
</script>
{{-- admin.dashboard   dashboard--}}