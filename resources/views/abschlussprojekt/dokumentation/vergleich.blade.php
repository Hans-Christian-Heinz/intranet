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
                        @include('abschlussprojekt.dokumentation.vgl_help', ['version' => $versionen[0], 'v_name' => 'v0',])
                    </div>
                    <div class="border-top border-dark pt-3">
                        @include('abschlussprojekt.dokumentation.vgl_help', ['version' => $versionen[1], 'v_name' => 'v1',])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
