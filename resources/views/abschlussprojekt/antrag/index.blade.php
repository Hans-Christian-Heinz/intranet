@extends('layouts.app')

@section('title', "Projektantrag · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row bg-white">
                <div class="col-md-12 py-2">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Projektantrag {{ $proposal->project->user->full_name }}</h3>
                    </div>
                    @include('abschlussprojekt.lock_document', [
                        'document' => $proposal,
                        'route_prefix' => 'abschlussprojekt.antrag',
                        'route_param' => 'proposal',
                    ])
                    @include('abschlussprojekt.antrag.navigationsleiste', ['v_name' => '',])
                    @include('abschlussprojekt.antrag.tabinhalt', ['v_name' => '', 'disable' => $disable])
                </div>
                {{-- Link zum Veränderungsverlauf --}}
                <div class="mr-auto p-3">
                    @if(request()->is('admin*'))
                        <a href="{{ route('admin.abschlussprojekt.versionen.index', ['project' => $proposal->project, 'doc_type' => 'antrag',]) }}"
                           class="btn btn-secondary">
                            Veränderungsverlauf
                        </a>
                    @else
                        <a href="{{ route('abschlussprojekt.versionen.index', ['project' => $proposal->project, 'doc_type' => 'antrag',]) }}"
                           class="btn btn-secondary">
                            Veränderungsverlauf
                        </a>
                    @endif
                </div>
                {{-- Formular zum Speichern --}}
                <form class="form form-inline ml-auto p-3" action="{{ route('abschlussprojekt.antrag.store', $proposal->project) }}" method="post" id="formAntrag">
                    @csrf
                    {{-- Schaltfläche zum Generieren von Platzhaltern (Einfügen von Bildern, Tabellen etc.) --}}
                    <a class="btn btn-secondary mx-2" data-toggle="modal" href="#insertPlaceholders">Einfügen</a>
                    {{-- Link zum Erstellen eines PDF-Dokuments --}}
                    <a class="btn btn-secondary mx-2" data-toggle="modal" id="formatPdfModal" href="#formatPdf">PDF erstellen</a>
                    <input class="btn btn-primary mx-2" type="submit" form="formAntrag" id="speichern"
                           value="Speichern" @if($disable) disabled @endif/>
                </form>
            </div>
        </div>
    </div>

    @include('abschlussprojekt.formatPdfModal', ['route' => 'abschlussprojekt.antrag.pdf', 'project' => $proposal->project,])
    @include('abschlussprojekt.insertModal.insertModal')
@endsection
