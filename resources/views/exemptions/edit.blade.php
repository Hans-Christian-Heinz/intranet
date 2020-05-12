@extends('layouts.app')

@section('title', "Freistellung bearbeiten")

@section("content")
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('exemptions.update', $exemption) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="start" class="font-weight-bold">Beginn:</label>
                                        <input type="datetime-local" class="form-control @error('start') is-invalid @enderror" name="start" id="start" placeholder="YYYY-MM-DD HH:MM" value="{{ $exemption->start }}">
                                        @error('start')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end" class="font-weight-bold">Ende:</label>
                                        <input type="datetime-local" class="form-control @error('end') is-invalid @enderror" name="end" id="end" placeholder="YYYY-MM-DD HH:MM" value="{{ $exemption->end }}">
                                        @error('end')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reason" class="font-weight-bold">Begründung:</label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" id="reason" cols="30" rows="2" placeholder="Begründung">{{ $exemption->reason }}</textarea>
                                    @error('reason')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <a href="{{ route('exemptions.index') }}" class="btn btn-outline-secondary">Abbrechen</a>
                                    <input type="submit" class="btn btn-primary" value="Änderungen Speichern">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
