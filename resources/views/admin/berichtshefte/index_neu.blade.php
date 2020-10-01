{{-- Eine Liste aller Auszubildenden, um auf Ihre Berichtshefte zuzugreifen --}}

@extends('layouts.app')

@section('title', "Berichtshefte · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Alle Auszubildenden</h3>
                    </div>

                    @if($azubis)
                        {{-- Navigationsleiste --}}
                        <ul class="nav nav-pills" id="projectsTab" role="tablist">
                            @foreach($azubis as $beginn => $azubi_liste)
                                <li class="nav-item">
                                    <a class="nav-link @if($loop->first) active @endif"
                                       aria-selected="false" role="tab" id="azubis_{{ $beginn }}_tab" data-toggle="tab"
                                       aria-controls="azubis_{{ $beginn }}" href="#azubis_{{ $beginn }}">
                                        @if($beginn == 'None')
                                            Kein Ausbildungsbeginn eingetragen
                                        @else
                                            Ausbildungsbeginn: {{ Carbon\Carbon::create($beginn)->format('Y-W') }}
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        {{-- Tabinhalt --}}
                        <div class="tab-content" id="azubisTabContent">
                            @foreach($azubis as $beginn => $azubi_liste)
                                <div class="tab-pane @if($loop->first) active show @endif mt-2" id="azubis_{{ $beginn }}"
                                     role="tabpanel" aria-labelledby="azubis_{{ $beginn }}_tab">
                                    @include('admin.berichtshefte.azubis_table')
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">
                            Es wurden noch keine Abschlusspojekte von Auszubildenden angelegt.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
