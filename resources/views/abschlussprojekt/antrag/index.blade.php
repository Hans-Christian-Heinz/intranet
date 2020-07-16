@extends('layouts.app')

@section('title', "Projektantrag · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Projektantrag</h3>
                    </div>
                    {{-- Navigationsleiste --}}
                    <ul class="nav nav-tabs scrollnav" id="antragTab" role="tablist">
                        @foreach($proposal->sections as $section)
                            <li class="nav-item border border-dark">
                                <a class="nav-link" aria-selected="false" role="tab" id="{{ $section->name }}_tab"
                                   data-toggle="tab" aria-controls="{{ $section->name }}" href="#{{ $section->name }}">
                                    {{ $section->heading }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    {{-- Tabinhalt --}}
                    <div class="tab-content" id="antragTabContent">
                        @foreach($proposal->sections as $section)
                            <div class="tab-pane mt-2" id="{{ $section->name }}" role="tabpanel" aria-labelledby="{{ $section->name }}_tab">
                                @include('abschlussprojekt.sections.' . $section->tpl, ['form' => 'formAntrag', 's' => $section,])
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- Formular zum Speichern --}}
                <form class="form ml-auto p-3" action="{{ route('abschlussprojekt.antrag.store', $proposal->project) }}" method="post" id="formAntrag">
                    @csrf
                    <input class="btn btn-primary" type="submit" form="formAntrag" id="speichern" value="Speichern"/>
                </form>
            </div>
        </div>
    </div>
@endsection
