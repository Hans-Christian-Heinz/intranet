@if (session('status'))
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if (session('danger'))
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        {{ session('danger') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session('accept_rules'))
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning d-flex" role="alert">
                        <span class="my-auto mr-auto">{{ session('accept_rules') }}</span>
                        <a href="{{ route("rules.index") }}" class="btn btn-sm btn-outline-secondary mr-2"><span class="far fa-eye mr-2"></span>Regeln lesen</a>
                        <button type="submit" form="acceptRulesForm" class="btn btn-sm btn-outline-primary"><span class="fas fa-check mr-2"></span>Regeln akzeptieren</button>
                        <form action="{{ route("rules.accept") }}" method="POST" id="acceptRulesForm">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
