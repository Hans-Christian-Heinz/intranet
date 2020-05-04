<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.index') }}">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Navigation umschalten">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route("home") }}" class="nav-link"><i class="fas fa-sign-out-alt mr-2"></i>Zurück zur Benutzeroberfläche</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#secondNavbar" aria-controls="secondNavbar" aria-expanded="false" aria-label="Navigation umschalten">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="secondNavbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ (request()->is("admin")) ? 'active' : '' }}">
                    <a href="{{ route("admin.index") }}" class="nav-link pl-0">Dashboard</a>
                </li>
                <li class="nav-item {{ (request()->is("admin/rules*")) ? 'active' : '' }}">
                    <a href="{{ route("admin.rules.edit") }}" class="nav-link">Werkstattregeln</a>
                </li>
                <li class="nav-item {{ (request()->is("admin/berichtshefte*")) ? 'active' : '' }}">
                    <a href="{{ route("admin.berichtshefte.index") }}" class="nav-link">Berichtshefte</a>
                </li>
                <li class="nav-item {{ (request()->is("admin/freistellungen*")) ? 'active' : '' }}">
                    <a href="{{ route("admin.freistellungen.index") }}" class="nav-link">Freistellungen</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
