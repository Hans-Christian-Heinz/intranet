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
                    @include('abschlussprojekt.dokumentation.tabinhalt', ['v_name' => '', 'disable' => false])
                </div>
                {{-- Link zum Veränderungsverlauf --}}
                <div class="mr-auto p-3">
                    <a href="{{ route('abschlussprojekt.dokumentation.history', $documentation->project) }}" class="btn btn-secondary">
                        Veränderungsverlauf
                    </a>
                </div>
                {{-- Formular zum Speichern --}}
                <form class="form form-inline ml-auto p-3" action="{{ route('abschlussprojekt.dokumentation.store', $documentation->project) }}" method="post" id="formDokumentation">
                    @csrf
                    {{-- Link zum Erstellen eines PDF-Dokuments --}}
                    <a class="btn btn-secondary mx-2" data-toggle="modal" id="formatPdfModal" href="#formatPdf">PDF erstellen</a>
                    <input class="btn btn-primary mx-2" type="submit" form="formDokumentation" id="speichern" value="Speichern"/>
                </form>
            </div>
        </div>
    </div>

    @include('abschlussprojekt.formatPdfModal', ['route' => 'abschlussprojekt.dokumentation.pdf', 'project' => $documentation->project,])
@endsection
