@extends('layouts.app')

@section('title', "Bewerbungen · ")

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex mb-3">
                    <h3 class="my-auto">Kategorie hinzufügen</h3>

                    <div class="ml-auto my-auto">
                        <a href="{{ route("admin.bewerbungen.categories.index") }}" class="btn btn-sm btn-outline-secondary">Zurück</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route("admin.bewerbungen.categories.store") }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="name">Kategorie Name</label>
                                <input type="text" class="form-control @error("name") is-invalid @enderror" name="name" id="name" placeholder="Kategorie Name" value="{{ old("name") }}" autofocus>
                                @error('name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary float-right">Kategorie hinzufügen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
