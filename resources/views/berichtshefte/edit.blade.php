@extends('layouts.app')

@section('title', "Berichtsheft bearbeiten ·")

@section("content")
    <div class="section">
        <div class="container">
            <div class="row p-3">
                <h3>Berichtsheft bearbeiten</h3>
                <div class="ml-auto">
                    @if(request()->is('admin*'))
                        <a href="{{ $previousWeek ? route("admin.berichtshefte.edit", $previousWeek) : "#" }}" class="btn btn-sm btn-outline-secondary {{ (!$previousWeek) ? "disabled" : "" }}" {{ (!$previousWeek) ? "disabled" : "" }}>
                    @else
                        <a href="{{ $previousWeek ? route("berichtshefte.edit", $previousWeek) : "#" }}" class="btn btn-sm btn-outline-secondary {{ (!$previousWeek) ? "disabled" : "" }}" {{ (!$previousWeek) ? "disabled" : "" }}>
                    @endif
                            <span class="fa fa-caret-left mr-2"></span>Vorherige Woche
                        </a>
                    @if ($nextWeek)
                        @if(request()->is('admin*'))
                            <a href="{{ route("admin.berichtshefte.edit", $nextWeek)}}" class="btn btn-sm btn-outline-secondary">
                        @else
                            <a href="{{ route("berichtshefte.edit", $nextWeek)}}" class="btn btn-sm btn-outline-secondary">
                        @endif
                                Nächste Woche<span class="fa fa-caret-right ml-2"></span>
                            </a>
                    @else
                        @if(request()->is('admin*'))
                            <a href="{{ route("admin.berichtshefte.create", $berichtsheft->owner) }}" class="btn btn-sm btn-outline-info">
                        @else
                            <a href="{{ route("berichtshefte.create") }}" class="btn btn-sm btn-outline-info">
                        @endif
                                Neue Woche<span class="fa fa-plus ml-2"></span>
                            </a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if(request()->is('admin*'))
                            <form action="{{ route("admin.berichtshefte.update", $berichtsheft) }}" method="POST">
                            @else
                            <form action="{{ route("berichtshefte.update", $berichtsheft) }}" method="POST">
                            @endif
                                @csrf
                                @method("PATCH")

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="week"><strong>Kalenderwoche:</strong></label>
                                        <input type="week" class="form-control @error('week') is-invalid @enderror" name="week" id="week" value="{{ $berichtsheft->week->format("Y-\WW") }}">
                                        @error("week")
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label for="grade"><strong>Lehrjahr:</strong></label>
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
                                <div class="form-group d-flex mb-0">
                                    <div class="mr-auto">
                                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteBerichtsheft">
                                            <span class="fa fa-trash mr-2" aria-hidden="true"></span>Löschen
                                        </button>
                                    </div>
                                    <div class="ml-auto">
                                        <a href="{{ route("berichtshefte.show", $berichtsheft) }}" class="btn btn-secondary" target="_blank">
                                            <span class="fa fa-print mr-2" aria-hidden="true"></span>Drucken
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
                    <p class="text-justify">
                        Möchten Sie das Berichtsheft wirklich löschen?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger" name="beginn_anpassen" value="false" form="deleteBerichtsheftForm">
                        Löschen
                    </button>

                    @if(request()->is('admin*'))
                    <form id="deleteBerichtsheftForm" action="{{ route('admin.berichtshefte.destroy', $berichtsheft) }}" method="POST">
                    @else
                    <form id="deleteBerichtsheftForm" action="{{ route('berichtshefte.destroy', $berichtsheft) }}" method="POST">
                    @endif
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
