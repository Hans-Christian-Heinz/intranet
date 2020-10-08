@extends('layouts.app')

@section('title', "Freistellung bearbeiten ·")

@section("content")
    <div class="section">
        <div class="container">
            <div class="row p-3">
                <h3>Freistellungsantrag bearbeiten</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('exemptions.update', $exemption) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="start" class="font-weight-bold">Beginn:</label>
                                        <div class="input-group">
                                            <input type="date" name="start-date" class="form-control @error('start-date') is-invalid @enderror"
                                                placeholder="YYYY-MM-DD" value="{{ $exemption->start->format('Y-m-d') }}" required>
                                            <input type="time" name="start-time" class="form-control @error('start-time') is-invalid @enderror" placeholder="HH:MM (optional)"
                                                value="{{ $exemption->start->format('H:i') !== '00:00' ? $exemption->start->format('H:i') : '' }}">

                                            @error("start-date") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                            @error("start-time") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="end" class="font-weight-bold">Ende:</label>
                                        <div class="input-group">
                                            <input type="date" name="end-date" class="form-control @error('end-date') is-invalid @enderror"
                                                placeholder="YYYY-MM-DD" value="{{ $exemption->end->format('Y-m-d') }}" required>
                                            <input type="time" name="end-time" class="form-control @error('end-time') is-invalid @enderror" placeholder="HH:MM (optional)"
                                                value="{{ $exemption->end->format('H:i') !== '00:00' ? $exemption->end->format('H:i') : '' }}">

                                            @error("end-date") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                            @error("end-time") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reason" class="font-weight-bold">Begründung:</label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" id="reason" cols="30"
                                        rows="2" placeholder="Begründung">{{ $exemption->reason }}</textarea>
                                    @error('reason')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group mb-0">
                                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteExemption">
                                        <span class="fa fa-trash mr-2" aria-hidden="true"></span>Löschen
                                    </button>
                                    <div class="float-right">
                                        <a href="{{ route('exemptions.index') }}" class="btn btn-outline-secondary">
                                            <span class="fa fa-times mr-2"></span>Abbrechen
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <span class="fa fa-floppy-o mr-2"></span>Änderungen speichern
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteExemption" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Freistellung löschen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Möchten Sie die Freistellung wirklich löschen?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger" form="deleteExemptionForm">Löschen</button>

                    <form id="deleteExemptionForm" action="{{ route("exemptions.destroy", $exemption) }}" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
