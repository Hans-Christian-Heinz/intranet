@extends('layouts.app')

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
            <app-tpl :tpl="{{ $templates }}"></app-tpl>
        </div>
    </div>
@endsection
