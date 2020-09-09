@extends('layouts.app')

@section('title', "Veränderungsverlauf · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row bg-white">
                <div class="col-md-12 py-2">
                    <div class="d-flex pb-3">
                        <h3 class="mx-auto">Projekt{{ $doc_type }} {{ $document->project->user->full_name }}: Vergleiche Versionen</h3>
                    </div>
                    <div class="border-bottom border-dark py-3">
                        @include('abschlussprojekt.versionen.vgl_help', ['version' => $versionen[0], 'v_name' => 'v0',])
                    </div>
                    <div class="border-top border-dark pt-3">
                        @include('abschlussprojekt.versionen.vgl_help', ['version' => $versionen[1], 'v_name' => 'v1',])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
