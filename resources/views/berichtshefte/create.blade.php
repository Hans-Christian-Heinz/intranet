@extends('layouts.app')

@section('title', 'Berichtsheft hinzufügen · ')

@section("content")
    <div class="section">
        <div class="container">
            <div class="row p-3">
                <h3>Berichtsheft erstellen</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        @if(request()->is('admin*'))
                            <div class="card-header bg-white">
                                <h3>Berichtsheft erstellen für {{ $azubi->full_name }}</h3>
                            </div>
                        @endif
                        <div class="card-body">
                            @if(request()->is('admin*'))
                            <form action="{{ route("admin.berichtshefte.store", $azubi) }}" method="POST">
                            @else
                            <form action="{{ route("berichtshefte.store") }}" method="POST">
                            @endif
                                @csrf

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="week"><strong>Kalenderwoche:</strong></label>
                                        <input type="week" class="form-control @error('week') is-invalid @enderror" name="week" id="week" value="{{ $nextBerichtsheftDate->format("Y-\WW") }}">
                                        @error("week")
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label for="grade"><strong>Lehrjahr:</strong></label>
                                        <select class="form-control @error('grade') is-invalid @enderror" name="grade" id="grade">
                                            @for ($i = 1; $i <= 3; $i++)
                                                <option value="{{ $i }}" @if ($nextBerichtsheftGrade == $i) selected @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error("grade")
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="work_activities"><strong>Betriebliche Tätigkeiten:</strong></label>
                                    <textarea class="form-control @error('work_activities') is-invalid @enderror" name="work_activities" id="work_activities" cols="30" rows="4" placeholder="Betriebliche Tätigkeiten">{{ old("work_activities") }}</textarea>
                                    @error("work_activities")
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="instructions"><strong>Unterweisungen, betrieblicher Unterricht, sonstige Schulungen:</strong></label>
                                    <textarea class="form-control @error('instructions') is-invalid @enderror" name="instructions" id="instructions" cols="30" rows="4" placeholder="Unterweisungen, betrieblicher Unterricht, sonstige Schulungen">{{ old("instructions") }}</textarea>
                                    @error("instructions")
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="school"><strong>Themen des Berufsschulunterrichts:</strong></label>
                                    <textarea class="form-control @error('school') is-invalid @enderror" name="school" id="school" cols="30" rows="4" placeholder="Themen des Berufsschulunterrichts">{{ old("school") }}</textarea>
                                    @error("school")
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group mb-0">
                                    <input type="submit" class="btn btn-primary float-right" value="Berichtsheft hinzufügen">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
