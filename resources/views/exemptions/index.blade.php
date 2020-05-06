@extends('layouts.app')

@section('title', "Freistellungen")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if ($exemptions->count())
                        <div class="d-flex pb-3">
                            <h3 class="mr-auto">Freistellungen</h3>
                            <a href="{{ route("exemptions.create") }}" class="btn btn-outline-primary">
                                <span class="fas fa-plus mr-2"></span>Hinzufügen
                            </a>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center text-strong" style="width: 2%;">#</th>
                                    <th>Begründung</th>
                                    <th class="text-center" style="width: 10%;">Status</th>
                                    <th class="text-center">Optionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exemptions as $exemption)
                                    <tr>
                                        <td class="text-center">{{ $exemption->id }}</td>
                                        <td>{{ $exemption->reason }}</td>
                                        <td class="text-center">{{ $exemption->status }}</td>
                                        <td>
                                            <a href="{{ route("exemptions.show", $exemption) }}" class="btn btn-sm btn-secondary">Ansehen</a>
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
                                <a href="{{ route("berichtshefte.create") }}" class="btn btn-outline-primary"><span class="fas fa-plus mr-2"></span>Berichtsheft hinzufügen</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
