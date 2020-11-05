@extends('layouts.app')

@section('title', "Bewerbungsanschreiben · ")

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex mb-3">
                    <h3 class="my-auto">Bewerbung an {{ $application->company->name }}</h3>
                    <div class="ml-auto my-auto">
                        <a href="{{ route("bewerbungen.applications.index") }}" class="btn btn-sm btn-outline-secondary">Zurück</a>
                    </div>
                </div>
            </div>
        </div>
        @if (App\ApplicationTemplate::where('version', $application->tpl_version)->count() > 0)
            <application-new route="{{ route("bewerbungen.applications.update", $application) }}" version="{{ $application->tpl_version }}"
                             application="{{ $application }}" :user="{{ app()->user }}" :saved="{{ $application->body ?: json_encode([]) }}"></application-new>
        @else
            <p class="text-center"><b>
                Die Vorlage, auf der dieses Anschreiben basiert, existiert nicht mehr.
                Dieses Anschreiben kann nur noch eingeschränkt bearbeitet werden.
            </b></p>
            <application-no-tpl route="{{ route("bewerbungen.applications.update", $application) }}"
                             :user="{{ app()->user }}" :saved="{{ $application->body ?: json_encode([]) }}"></application-no-tpl>
        @endif
    </div>
</div>

@include('bewerbungen.formatPdfModal', ['route' => route("bewerbungen.applications.printNew", $application),])
@endsection

@push('modals')
    <x-modal id="deleteApplicationModal" title="Bewerbung löschen">
        <x-slot name="body">
            <p class="text-center py-3">Sind Sie sicher, dass Sie die Bewerbung<br> für <strong>{{ $application->company->name }}</strong> wirklich löschen wollen?</p>
        </x-slot>

        <x-slot name="footer">
            <form action="{{ route("bewerbungen.applications.destroy", $application) }}" method="POST">
                @csrf
                @method("DELETE")
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                <button type="submit" class="btn btn-danger">Bewerbung löschen</button>
            </form>
        </x-slot>
    </x-modal>
@endpush
