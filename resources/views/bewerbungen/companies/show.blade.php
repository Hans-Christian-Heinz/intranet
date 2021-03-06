@extends('layouts.app')

@section('title', "Bewerbungen: Betriebe · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                {{-- Main content --}}
                <div class="col-md-9">
                    {{-- Company information --}}
                    <div class="card">
                        <div class="card-body">
                            <h3>{{ $company->name }}</h3>

                            <label for="description" class="text-secondary">Beschreibung</label>
                            <p class="mb-0">{!! nl2br($company->description) !!}</p>
                        </div>
                    </div>

                    {{-- Company Ratings --}}
                    <div class="card my-3">
                        <div class="card-body">
                            <a href="{{ route("bewerbungen.companies.reviews.create", $company) }}" class="text-secondary stretched-link mb-0">Bewertung schreiben...</a>
                        </div>
                    </div>

                    {{-- Reviews list --}}
                    @forelse ($reviews as $review)
                        <x-review :review="$review" />
                    @empty
                        <div class="card">
                            <div class="card-body p-5 text-center">
                                <p class="text-secondary mb-0">Es wurden noch keine Bewertungen abgegeben.</p>
                            </div>
                        </div>
                    @endforelse

                    <div class="d-flex justify-content-center">
                        {{ $reviews->links() }}
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-md-3">
                    {{-- Admin tools for companies --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ route("bewerbungen.companies.edit", $company) }}" class="btn btn-block btn-outline-secondary">Firma bearbeiten</a>
                            <button class="btn btn-block btn-outline-danger" data-toggle="modal" @if(app()->user->isAdmin()) data-target="#deleteCompanyModal" @endif>Firma löschen</button>
                        </div>
                    </div>

                    @include('bewerbungen.companies.deleteCompanyModal')

                    <div class="card mb-3">
                        <div class="card-body">
                            <form action="{{ route("bewerbungen.applications.store", $company) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-block btn-outline-primary">Bewerben</button>
                            </form>
                        </div>
                    </div>

                    {{-- Company information --}}
                    <div class="card">
                        <div class="card-body">
                            <label for="name" class="text-secondary">Firmen Name</label>
                            <p><strong>{{ $company->name }}</strong></p>

                            <label for="address" class="text-secondary">Adresse</label>
                            <address id="address">
                                {{ $company->address }}<br>
                                {{ $company->zip }} {{ $company->city }}<br>
                                {{ $company->state }} {{ $company->country }}
                            </address>

                            <label for="ansprechpartner" class="text-secondary">Ansprechpartner</label>
                            <p class="mb-0" id="ansprechpartner">
                                @if($company->contract)
                                    {{ $company->contact }}, {{ $company->function }}<br>
                                    {{ $company->email }}<br>
                                    {{ $company->phone }}
                                @else
                                    Kein Ansprechpartner angegeben
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
