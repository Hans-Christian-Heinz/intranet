<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Navigation umschalten">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @auth
                    @if (app()->user->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route("admin.index") }}" class="nav-link text-danger">
                            <span class="fa fa-cog mr-2" aria-hidden="true"></span>Zur Administration
                        </a>
                    </li>
                    @endif
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Anmelden</a>
                    </li>
                @else
                    <li class="nav-item {{ (request()->is('berichtsheft*')) ? 'active' : '' }}">
                        <a href="{{ route("berichtshefte.index") }}" class="nav-link">Berichtshefte</a>
                    </li>
                    <li class="nav-item {{ (request()->is('exemptions*')) ? 'active' : '' }}">
                        <a href="{{ route("exemptions.index") }}" class="nav-link">Freistellungen</a>
                    </li>
                    <li class="nav-item {{ (request()->is('rules')) ? 'active' : '' }}">
                        <a href="{{ route("rules.index") }}" class="nav-link">Werkstattregeln</a>
                    </li>
                    <li class="nav-item {{ (request()->is('abschlussprojekt*')) ? 'active' : '' }}">
                        <a href="{{ route("abschlussprojekt.index") }}" class="nav-link">Abschlussprojekt</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#addressModal" data-toggle="modal">Adresse verwalten</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                Abmelden
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@auth
    @include('user.addressModal')
@endauth
