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
                        @include('abschlussprojekt.antrag.vgl_help', ['version' => $versionen[0], 'v_name' => 'v0',])
                    </div>
                    <div class="border-top border-dark py-3">
                        @include('abschlussprojekt.antrag.vgl_help', ['version' => $versionen[1], 'v_name' => 'v1',])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
