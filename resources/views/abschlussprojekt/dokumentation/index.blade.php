@extends('layouts.app')

@section('title', "Projektdokumentation · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row bg-white">
                <div class="col-md-12 py-2">
                    <div class="d-flex pb-3">
                        <h3 class="mx-auto">Projektdokumentation {{ $documentation->project->user->full_name }}</h3>
                    </div>
                    @include('abschlussprojekt.lock_document', [
                        'document' => $documentation,
                        'route_prefix' => 'abschlussprojekt.dokumentation',
                        'route_param' => 'documentation',
                    ])
                    <div class="row">
                        <div class="col-3">
                            @include('abschlussprojekt.sections.sectionNavigation', ['v_name' => '', 's' => $documentation, 'name' => 'documentation'])
                        </div>
                        <div class="col-9 tab-content">
                            @include('abschlussprojekt.sections.tabinhaltNeu', ['v_name' => '', 'disable' => $disable, 's' => $documentation, 'form' => 'formDokumentation',])
                        </div>
                    </div>

                    {{--@include('abschlussprojekt.dokumentation.navigationsleiste', ['v_name' => '',])
                    @include('abschlussprojekt.dokumentation.tabinhalt', ['v_name' => '', 'disable' => $disable])--}}
                </div>
                {{-- Link zum Veränderungsverlauf --}}
                <div class="mr-auto p-3">
                    @if(request()->is('admin*'))
                        <a href="{{ route('admin.abschlussprojekt.versionen.index', ['project' => $documentation->project, 'doc_type' => 'dokumentation',]) }}"
                           class="btn btn-secondary">
                            Veränderungsverlauf
                        </a>
                    @else
                        <a href="{{ route('abschlussprojekt.versionen.index', ['project' => $documentation->project, 'doc_type' => 'dokumentation',]) }}"
                           class="btn btn-secondary">
                            Veränderungsverlauf
                        </a>
                    @endif
                </div>
                {{-- Formular zum Speichern --}}
                <form class="form form-inline ml-auto p-3" action="{{ route('abschlussprojekt.dokumentation.store', $documentation->project) }}" method="post" id="formDokumentation">
                    @csrf
                    {{-- Schaltfläche zum Generieren von Platzhaltern (Einfügen von Bildern, Tabellen etc.) --}}
                    <a class="btn btn-secondary mx-2" data-toggle="modal" href="#insertPlaceholders">Einfügen</a>
                    {{-- Link zum Erstellen eines PDF-Dokuments --}}
                    <a class="btn btn-secondary mx-2" data-toggle="modal" id="formatPdfModal" href="#formatPdf">PDF erstellen</a>
                    <input class="btn btn-primary save-document mx-2" type="submit" form="formDokumentation" id="speichern"
                           value="Speichern" @if($disable) disabled @endif/>
                </form>
            </div>
        </div>
    </div>

    @include('abschlussprojekt.formatPdfModal', ['route' => 'abschlussprojekt.dokumentation.pdf', 'project' => $documentation->project,])
    @include('abschlussprojekt.insertModal.insertModal')
@endsection
