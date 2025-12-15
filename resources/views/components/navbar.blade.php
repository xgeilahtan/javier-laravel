{{-- resources/views/components/navbar.blade.php --}}

<header class="navbar-principal" id="header">
    <nav class="nav-container">

        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Javier Cabelo e Estética">
        </a>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('sobre') }}">Sobre</a></li>
            <li><a href="{{ route('servicos') }}">Serviços</a></li>
            <li><a href="{{ route('contato') }}">Contato</a></li>
        </ul>

        <div class="nav-direita">
            <a href="{{ route('agendamento.create') }}" class="btn-agendar">Agendar</a>

            @guest
                <a href="#" class="btn-agendar" data-bs-toggle="modal" data-bs-target="#loginModal">Entrar</a>
            @endguest

            @auth
            <div class="user-menu">
                    <a href="{{ route('profile.show') }}" class="btn-icon-perfil" title="Ir para o Perfil">
                        <i class='bx bx-user-circle'> Perfil</i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-agendar">Sair</button>
                    </form>
                </div>
            @endauth

            <div class="menu-mobile-icone" id="menu-icon">
                <i class='bx bx-menu'></i>
            </div>
        </div>

    </nav>
</header>
