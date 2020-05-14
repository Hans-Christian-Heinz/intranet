@extends('layouts.app')

@section('title', 'Freistellung erstellen · ')

@section("content")
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route("exemptions.store") }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="start" class="font-weight-bold">Beginn:</label>
                                        <div class="input-group">
                                            <input type="date" name="start-date" class="form-control @error('start-date') is-invalid @enderror"
                                                placeholder="YYYY-MM-DD" required>
                                            <input type="time" name="start-time" class="form-control @error('start-time') is-invalid @enderror"
                                                placeholder="HH:MM (optional)">

                                            @error("start-date") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                            @error("start-time") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="end" class="font-weight-bold">Ende:</label>
                                        <div class="input-group">
                                            <input type="date" name="end-date" class="form-control @error('end-date') is-invalid @enderror"
                                                placeholder="YYYY-MM-DD" required>
                                            <input type="time" name="end-time" class="form-control @error('end-time') is-invalid @enderror"
                                                placeholder="HH:MM (optional)">

                                            @error("end-date") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                            @error("end-time") <p class="invalid-feedback">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reason" class="font-weight-bold">Begründung:</label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" id="reason" cols="30" rows="2" placeholder="Begründung">{{ old("reason") }}</textarea>
                                    @error("reason")
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Freistellung beantragen">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
