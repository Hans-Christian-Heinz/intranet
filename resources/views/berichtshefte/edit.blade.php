@extends('layouts.app')

@section('title', "Berichtsheft bearbeiten")

@section("content")
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route("berichtshefte.update", $berichtsheft) }}" method="POST">
                                @csrf
                                @method("PATCH")

                                <div class="row">
                                    <div class="form-group col-md-10">
                                        <label for="week">Kalenderwoche</label>
                                        <input type="week" class="form-control @error('week') is-invalid @enderror" name="week" id="week" value="{{ $berichtsheft->week->format("Y-\WW") }}">
                                        @error("week")
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="grade">Lehrjahr</label>
                                        <select class="form-control @error('grade') is-invalid @enderror" name="grade" id="grade">
                                            @for ($i = 1; $i <= 3; $i++)
                                                <option value="{{ $i }}" @if ($berichtsheft->grade == $i) selected @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error("grade")
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="work_activities"><strong>Betriebliche Tätigkeiten:</strong></label>
                                    <textarea class="form-control @error('work_activities') is-invalid @enderror" name="work_activities" id="work_activities" cols="30" rows="4" placeholder="Betriebliche Tätigkeiten">{{ old("work_activities") ?: $berichtsheft->work_activities }}</textarea>
                                    @error("work_activities")
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="instructions"><strong>Unterweisungen, betrieblicher Unterricht, sonstige Schulungen:</strong></label>
                                    <textarea class="form-control @error('instructions') is-invalid @enderror" name="instructions" id="instructions" cols="30" rows="4" placeholder="Unterweisungen, betrieblicher Unterricht, sonstige Schulungen">{{ old("instructions") ?: $berichtsheft->instructions }}</textarea>
                                    @error("instructions")
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="school"><strong>Themen des Berufsschulunterrichts:</strong></label>
                                    <textarea class="form-control @error('school') is-invalid @enderror" name="school" id="school" cols="30" rows="4" placeholder="Themen des Berufsschulunterrichts">{{ old("school") ?: $berichtsheft->school }}</textarea>
                                    @error("school")
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group d-flex">
                                    <div class="mr-auto">
                                        <button type="submit" class="btn btn-primary">Änderungen Speichern</button>
                                        <a href="{{ route("berichtshefte.show", $berichtsheft) }}" class="btn btn-secondary" target="_blank">Drucken</a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteBerichtsheft">Löschen</button>
                                    </div>
                                    <div>
                                        <a href="{{ $previousWeek ? route("berichtshefte.edit", $previousWeek) : "#" }}" class="btn btn-outline-secondary {{ (!$previousWeek) ? "disabled" : "" }}" {{ (!$previousWeek) ? "disabled" : "" }}>
                                            <i class="fas fa-caret-left mr-2"></i>Vorherige Woche
                                        </a>
                                        <a href="{{ $nextWeek ? route("berichtshefte.edit", $nextWeek) : "#" }}" class="btn btn-outline-secondary {{ (!$nextWeek) ? "disabled" : "" }}" {{ (!$nextWeek) ? "disabled" : "" }}>
                                            Nächste Woche<i class="fas fa-caret-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Berichtsheft Löschen Modal --}}
    <div class="modal fade" id="deleteBerichtsheft" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Berichtsheft löschen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Möchten Sie das Berichtsheft wirklich löschen?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger" form="deleteBerichtsheftForm">Löschen</button>

                    <form id="deleteBerichtsheftForm" action="{{ route("berichtshefte.destroy", $berichtsheft) }}" method="POST">
                        @csrf
                        @method("DELETE")
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
