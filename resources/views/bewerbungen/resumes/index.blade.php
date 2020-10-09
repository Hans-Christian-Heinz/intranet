@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div class="row p-3">
            <h3>Lebenslauf</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <resume :user="{{ $user }}" passbildroute="{{ route("bewerbungen.resumes.uploadPassbild", app()->user) }}"
                        passbild="{{ $passbild }}" printroute="{{ route("bewerbungen.resumes.print") }}"></resume>
            </div>
        </div>
    </div>
</div>

@push('modals')
    <div class="modal fade" id="deletePassbildModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Passbild löschen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Möchten Sie das Passbild wirklich löschen?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                    <form class="form" action="{{ route('bewerbungen.resumes.deletePassbild', app()->user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Löschen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@include('bewerbungen.formatPdfModal', ['route' => route("bewerbungen.resumes.print"),])
@endsection
