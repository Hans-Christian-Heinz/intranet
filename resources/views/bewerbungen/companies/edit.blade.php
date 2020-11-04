@extends('layouts.app')

@section('title', "Bewerbungen: Betriebe · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h3>Firma Bearbeiten</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route("bewerbungen.companies.update", $company) }}" method="POST">
                                @csrf
                                @method("PATCH")
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error("name") is-invalid @enderror" name="name" id="name" placeholder="Firmen Name" value="{{ old("name") ?: $company->name }}">
                                    @error("name")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Adresse</label>
                                    <input type="text" class="form-control @error("address") is-invalid @enderror" name="address" id="address" placeholder="Straße Nr." value="{{ old("address") ?: $company->address }}">
                                    @error("address")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="zip">Postleitzahl</label>
                                        <input type="text" class="form-control @error("zip") is-invalid @enderror" name="zip" id="zip" placeholder="Postleitzahl" value="{{ old("zip") ?: $company->zip }}">
                                        @error("zip")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-8">
                                        <label for="city">Stadt</label>
                                        <input type="text" class="form-control @error("city") is-invalid @enderror" name="city" id="city" placeholder="Stadt" value="{{ old("city") ?: $company->city }}">
                                        @error("city")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="state">Bundesland</label>
                                        <input type="text" class="form-control @error("state") is-invalid @enderror" name="state" id="state" placeholder="Bundesland" value="{{ old("state") ?: $company->state }}">
                                        @error("state")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="country">Land</label>
                                        <input type="text" class="form-control @error("country") is-invalid @enderror" name="country" id="country" placeholder="Land" value="{{ old("country") ?: $company->country }}">
                                        @error("country")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="contact">Ansprechpartner</label>
                                        <input type="text" class="form-control @error("contact") is-invalid @enderror" name="contact"
                                               id="contact" placeholder="Ansprechpartner" value="{{ old("contact") ?:$company->contact }}">
                                        @error("contact")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="function">Tätigkeit</label>
                                        <input type="text" class="form-control @error("function") is-invalid @enderror"
                                               name="function" id="function" placeholder="Tätigkeit des Ansprechpartners" value="{{ old("function") ?:$company->function }}">
                                        @error("function")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error("email") is-invalid @enderror" name="email"
                                               id="email" placeholder="Email des Ansprechpartners" value="{{ old("email") ?:$company->email }}">
                                        @error("email")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone">Telephon</label>
                                        <input type="text" class="form-control @error("phone") is-invalid @enderror"
                                               name="phone" id="phone" placeholder="Telephonnummer des Ansprechparnters" value="{{ old("phone") ?:$company->phone }}">
                                        @error("phone")
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Firmen Beschreibung</label>
                                    <textarea class="form-control @error("description") is-invalid @enderror" name="description" id="description" rows="10">{{ old("description") ?: $company->description }}</textarea>
                                    @error("description")
                                    <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-outline-danger" data-toggle="modal" type="button"
                                            @if(app()->user->isAdmin()) data-target="#deleteCompanyModal" @endif>Firma löschen</button>

                                    <div class="float-right">
                                        <a href="{{ route("bewerbungen.companies.show", $company) }}" class="btn btn-outline-secondary">Abbrechen</a>
                                        <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('bewerbungen.companies.deleteCompanyModal')
@endsection
