@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex mb-3">
                    <h3 class="my-auto">Kategorie bearbeiten</h3>

                    <div class="ml-auto my-auto">
                        <a href="{{ route("admin.bewerbungen.categories.index") }}" class="btn btn-sm btn-outline-secondary">Zurück</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route("admin.bewerbungen.categories.update", $category) }}" method="POST">
                            @csrf
                            @method("PATCH")

                            <div class="form-group">
                                <label for="name">Kategorie Name</label>
                                <input type="text" class="form-control @error("name") is-invalid @enderror" name="name" id="name" placeholder="Kategorie Name" value="{{ old("name") ?: $category->name }}">
                                @error('name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="pos">Kategorie Position</label>
                                <input type="number" min="0" max="{{ $max }}" class="form-control @error("position") is-invalid @enderror" name="position" id="pos" value="{{ old("position") ?: $category->position }}">
                                @error('position')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteCategoryModal">Löschen</button>
                                <button type="submit" class="btn btn-primary float-right">Änderungen speichern</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
    <x-modal id="deleteCategoryModal" title="Kategorie löschen">
        <x-slot name="body">
            <p class="text-center py-3">Möchten Sie diese Kategorie wirklich löschen?</p>
        </x-slot>

        <x-slot name="footer">
            <form action="{{ route("admin.bewerbungen.categories.destroy", $category) }}" method="POST">
                @csrf
                @method("DELETE")
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                <button type="submit" class="btn btn-danger">Kategorie löschen</button>
            </form>
        </x-slot>
    </x-modal>
@endpush
