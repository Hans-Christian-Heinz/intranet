@extends('layouts.app')

@section('title', "Bewerbungen: Vorlage · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <h3 class="my-auto">Templates für Bewerbungsanschreiben</h3>
                    </div>
                </div>
            </div>
            <app-tpl-new save_route="{{ route('admin.bewerbungen.templates.updateNew') }}" version_route="{{ route('admin.bewerbungen.templates.versionen') }}"
             		restore_default_route="{{ route('admin.bewerbungen.templates.restoreDefaultNew') }}"></app-tpl-new>
        </div>
    </div>
@endsection
