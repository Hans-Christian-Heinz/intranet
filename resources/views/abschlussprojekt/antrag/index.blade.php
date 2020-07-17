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
                    @include('abschlussprojekt.antrag.navigationsleiste', ['v_name' => '',])
                    @include('abschlussprojekt.antrag.tabinhalt', ['v_name' => '', 'disable' => false])
                </div>
                {{-- Link zum Veränderungsverlauf --}}
                <div class="mr-auto p-3">
                    <a href="{{ route('abschlussprojekt.antrag.history', $proposal->project) }}" class="btn btn-secondary">
                        Veränderungsverlauf
                    </a>
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
