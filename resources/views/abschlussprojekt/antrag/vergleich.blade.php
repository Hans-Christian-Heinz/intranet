@extends('layouts.app')

@section('title', "Projektantrag · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Projektantrag: Vergleiche Versionen</h3>
                    </div>
                    <div class="border-bottom border-dark py-3">
                        <h4>Version: {{ $versionen[0]->updated_at }}, geändert von {{ $versionen[0]->user->full_name }}</h4>
                        {{-- Navigationsleiste Version 0 --}}
                        @include('abschlussprojekt.antrag.navigationsleiste', [
                            'v_name' => 'v0',
                             'version' => $versionen[0],
                         ])
                        {{-- Tabinhalt Version 0 --}}
                        @include('abschlussprojekt.antrag.tabinhalt', [
                            'v_name' => 'v0',
                             'version' => $versionen[0],
                             'disable' => true,
                         ])
                        {{-- Formular zum Speichern von Version 0 als aktuelle Version --}}
                        <form class="form text-right p-3" action="{{ route('abschlussprojekt.antrag.use_version', $proposal->project) }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $versionen[0]->id }}"/>
                            <input class="btn btn-primary" type="submit" id="speichern" value="Version übernehmen"/>
                        </form>
                    </div>
                    <div class="border-top border-dark py-3">
                        <h4>Version: {{ $versionen[1]->updated_at }}, geändert von {{ $versionen[1]->user->full_name }}</h4>
                        {{-- Navigationsleiste Version 1 --}}
                        @include('abschlussprojekt.antrag.navigationsleiste', [
                            'v_name' => 'v1',
                             'version' => $versionen[1],
                         ])
                        {{-- Tabinhalt Version 1 --}}
                        @include('abschlussprojekt.antrag.tabinhalt', [
                            'v_name' => 'v1',
                             'version' => $versionen[1],
                             'disable' => true,
                         ])
                        {{-- Formular zum Speichern von Version 1 als aktuelle Version --}}
                        <form class="form text-right p-3" action="{{ route('abschlussprojekt.antrag.use_version', $proposal->project) }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $versionen[1]->id }}"/>
                            <input class="btn btn-primary" type="submit" id="speichern" value="Version übernehmen"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
