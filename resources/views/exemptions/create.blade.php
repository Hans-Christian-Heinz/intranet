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
                                    <div class="form-group col-md-6">
                                        <label for="start" class="font-weight-bold">Beginn:</label>
                                        <input type="datetime-local" class="form-control @error('start') is-invalid @enderror" name="start" id="start" placeholder="YYYY-MM-DD HH:MM" value="{{ old("start") }}">
                                        @error("start")
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end" class="font-weight-bold">Ende:</label>
                                        <input type="datetime-local" class="form-control @error('end') is-invalid @enderror" name="end" id="end" placeholder="YYYY-MM-DD HH:MM" value="{{ old("end") }}">
                                        @error("end")
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
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
