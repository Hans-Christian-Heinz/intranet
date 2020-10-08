@extends('layouts.app')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h3>Firma Anlegen</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route("bewerbungen.companies.store") }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error("name") is-invalid @enderror" name="name" id="name" placeholder="Firmen Name" value="{{ old("name") }}">
                                    @error("name")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Adresse</label>
                                    <input type="text" class="form-control @error("address") is-invalid @enderror" name="address" id="address" placeholder="Straße Nr." value="{{ old("address") }}">
                                    @error("address")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="zip">Postleitzahl</label>
                                        <input type="text" class="form-control @error("zip") is-invalid @enderror" name="zip" id="zip" placeholder="Postleitzahl" value="{{ old("zip") }}">
                                        @error("zip")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-8">
                                        <label for="city">Stadt</label>
                                        <input type="text" class="form-control @error("city") is-invalid @enderror" name="city" id="city" placeholder="Stadt" value="{{ old("city") }}">
                                        @error("city")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="state">Bundesland</label>
                                        <input type="text" class="form-control @error("state") is-invalid @enderror" name="state" id="state" placeholder="Bundesland" value="{{ old("state") }}">
                                        @error("state")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="country">Land</label>
                                        <input type="text" class="form-control @error("country") is-invalid @enderror" name="country" id="country" placeholder="Land" value="{{ old("country") }}">
                                        @error("country")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Firmen Beschreibung</label>
                                    <textarea class="form-control @error("description") is-invalid @enderror" name="description" id="description" rows="10">{{ old("description") }}</textarea>
                                    @error("description")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group float-right">
                                    <button type="submit" class="btn btn-outline-primary">Firma anlegen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
