@extends('layouts.app')

@section('title', "Bewerbungen · ")

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h3 class="my-auto">Kategorien</h3>
                    <a href="{{ route("admin.bewerbungen.categories.create") }}" class="btn btn-outline-primary">Kategorie hinzufügen</a>
                </div>
                <table class="table table-striped table-bordered table-hover mt-3 bg-white">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">Position</th>
                            <th>Name</th>
                            <th class="text-center" style="width: 10%;">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td class="text-center">{{ $category->position }}</td>
                                <td>{{ $category->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route("admin.bewerbungen.categories.edit", $category) }}" class="text-secondary">Bearbeiten</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Noch keine Kategorien erstellt</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
