@extends('layouts.app')

@section('title', "Projektdokumentation · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Projektdokumentation: Vergleiche Versionen</h3>
                    </div>
                    <div class="border-bottom border-dark pb-3">
                        {{-- Navigationsleiste Version 0 --}}
                        @include('abschlussprojekt.dokumentation.navigationsleiste', ['v_name' => 'v0', 'version' => $versionen[0]])
                        {{-- Tabinhalt Version 0 --}}
                        @include('abschlussprojekt.dokumentation.tabinhalt', ['v_name' => 'v0', 'version' => $versionen[0]])
                    </div>
                    <div class="border-top border-dark pt-3">
                        {{-- Navigationsleiste Version 1 --}}
                        @include('abschlussprojekt.dokumentation.navigationsleiste', ['v_name' => 'v1', 'version' => $versionen[1]])
                        {{-- Tabinhalt Version 1 --}}
                        @include('abschlussprojekt.dokumentation.tabinhalt', ['v_name' => 'v1', 'version' => $versionen[1]])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
