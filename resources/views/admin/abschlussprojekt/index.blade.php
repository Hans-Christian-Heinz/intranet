@extends('layouts.app')

@section('title', "Abschlussprojekt · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Alle Abschlussprojekte</h3>
                    </div>
                    {{-- Navigationsleiste --}}
                    <ul class="nav nav-pills" id="projectsTab" role="tablist">
                        @foreach($projects as $jahr => $fachrichtungen)
                            <li class="nav-item">
                                {{-- Beim Vergleich zweier Versionen werden Unterschiede hervorgehoben --}}
                                <a class="nav-link @if($loop->first) active @endif"
                                   aria-selected="false" role="tab" id="projects_{{ $jahr }}_tab" data-toggle="tab"
                                   aria-controls="projects_{{ $jahr }}" href="#projects_{{ $jahr }}">
                                    @if($jahr == "0")
                                        Kein Datum festgelegt
                                    @else
                                        Abschluss: {{ $jahr }}
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    {{-- Tabinhalt --}}
                    <div class="tab-content" id="projectsTabContent">
                        @foreach($projects as $jahr => $fachrichtungen)
                            <div class="tab-pane @if($loop->first) active show @endif mt-2" id="projects_{{ $jahr }}"
                                 role="tabpanel" aria-labelledby="projects_{{ $jahr }}_tab">
                                @include('admin.abschlussprojekt.projects_table')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
