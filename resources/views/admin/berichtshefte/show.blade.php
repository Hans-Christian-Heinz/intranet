@extends('layouts.app')

@section('title', "Berichtshefte ·")

@section("content")
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h3>Berichtshefte {{ $berichtsheft->owner->full_name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="week"><strong>Kalenderwoche:</strong></label>
                                    <input type="week" class="form-control" disabled name="week" id="week" value="{{ $berichtsheft->week->format("Y-\WW") }}">
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="grade"><strong>Lehrjahr:</strong></label>
                                    <select class="form-control" disabled name="grade" id="grade">
                                        @for ($i = 1; $i <= 3; $i++)
                                            <option value="{{ $i }}" @if ($berichtsheft->grade == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="work_activities"><strong>Betriebliche Tätigkeiten:</strong></label>
                                <textarea class="form-control" disabled name="work_activities" id="work_activities" cols="30" rows="4"
                                          placeholder="Betriebliche Tätigkeiten">{{ old("work_activities") ?: $berichtsheft->work_activities }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="instructions"><strong>Unterweisungen, betrieblicher Unterricht, sonstige Schulungen:</strong></label>
                                <textarea class="form-control" disabled name="instructions" id="instructions" cols="30" rows="4"
                                          placeholder="Unterweisungen, betrieblicher Unterricht, sonstige Schulungen">{{ old("instructions") ?: $berichtsheft->instructions }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="school"><strong>Themen des Berufsschulunterrichts:</strong></label>
                                <textarea class="form-control" disabled name="school" id="school" cols="30" rows="4"
                                          placeholder="Themen des Berufsschulunterrichts">{{ old("school") ?: $berichtsheft->school }}</textarea>
                            </div>
                            <div class="form-group d-flex mb-0">
                                <div>
                                    <a href="{{ $previousWeek ? route("admin.berichtshefte.show", $previousWeek) : "#" }}"
                                       class="btn btn-outline-secondary {{ (!$previousWeek) ? "disabled" : "" }}" {{ (!$previousWeek) ? "disabled" : "" }}>
                                        <span class="fa fa-caret-left mr-2"></span>Vorherige Woche
                                    </a>
                                    <a href="{{ $nextWeek ? route("admin.berichtshefte.show", $nextWeek) : '#' }}"
                                       class="btn btn-outline-secondary {{ (!$nextWeek) ? "disabled" : "" }}">
                                        Nächste Woche<span class="fa fa-caret-right ml-2"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

