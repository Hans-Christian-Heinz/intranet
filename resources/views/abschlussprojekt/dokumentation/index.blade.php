@extends('layouts.app')

@section('title', "Projektdokumentation · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Projektdokumentation</h3>
                    </div>
                    @include('abschlussprojekt.dokumentation.navigationsleiste', ['v_name' => '',])
                    @include('abschlussprojekt.dokumentation.tabinhalt', ['v_name' => '',])
                </div>
                {{-- Link zum Veränderungsverlauf --}}
                <div class="mr-auto p-3">
                    <a href="{{ route('abschlussprojekt.dokumentation.history', $documentation->project) }}" class="btn btn-secondary">
                        Veränderungsverlauf
                    </a>
                </div>
                {{-- Formular zum Speichern --}}
                <form class="form ml-auto p-3" action="{{ route('abschlussprojekt.dokumentation.store', $documentation->project) }}" method="post" id="formDokumentation">
                    @csrf
                    <input class="btn btn-primary" type="submit" form="formDokumentation" id="speichern" value="Speichern"/>
                </form>
            </div>
        </div>
    </div>
@endsection
