@extends('layouts.app')

@section('title', "Berichtsheft Übersicht")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if ($berichtshefte->count())
                        <div class="d-flex pb-3">
                            <h3 class="mr-auto">Berichtshefte</h3>
                            <a href="{{ route("berichtshefte.create") }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus mr-2"></i>Hinzufügen
                            </a>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center text-strong" style="width: 2%;">#</th>
                                    <th>Lehrjahr</th>
                                    <th class="text-center" style="width: 13%;">Kalender Woche</th>
                                    <th class="text-center" style="width: 10%;">Optionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($berichtshefte as $berichtsheft)
                                    <tr>
                                        <td class="text-center">{{ $berichtsheft->id }}</td>
                                        <td>{{ $berichtsheft->grade }}</td>
                                        <td class="text-center">{{ $berichtsheft->week->format("Y-W") }}</td>
                                        <td>
                                            <a href="{{ route("berichtshefte.edit", $berichtsheft) }}" class="btn btn-sm btn-secondary">Bearbeiten</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $berichtshefte->links() }}
                    @else
                        <div class="card">
                            <div class="card-body text-center p-5">
                                <h3>Noch kein Berichtheft vorhanden</h3>
                                <p>Bitte legen Sie Ihr erstes Berichtsheft an</p>
                                <a href="{{ route("berichtshefte.create") }}" class="btn btn-outline-primary"><i class="fas fa-plus mr-2"></i>Berichtsheft hinzufügen</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
