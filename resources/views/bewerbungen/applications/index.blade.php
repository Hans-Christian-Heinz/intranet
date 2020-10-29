@extends('layouts.app')

@section('title', "Bewerbungsanschreiben · ")

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex mb-3">
                    <h3 class="my-auto">Bewerbungen</h3>
                </div>

                <table class="table table-bordered table-hover table-striped bg-white">
                    <thead>
                        <tr>
                            <th>Firma</th>
                            <th class="text-center" style="width: 10%;">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr>
                                <td>{{ $application->company->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route("bewerbungen.applications.edit", $application) }}" class="text-secondary">Bearbeiten</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>Noch keine Bewerbungen geschrieben</td>
                                <td class="text-center">
                                    <a href="{{ route("bewerbungen.companies.index") }}" class="text-secondary">Zur Firmenübersicht</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
               {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
