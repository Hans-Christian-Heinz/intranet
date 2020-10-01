{{-- Eine Liste aller Auszubildenden, um auf Ihre Berichtshefte zuzugreifen --}}

@extends('layouts.app')

@section('title', "Berichtshefte · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Alle Auszubildenden</h3>
                    </div>

                    <table class="table table-striped text-center">
                        <thead>
                        <tr>
                            <th>Auszubildene(r)</th>
                            <th>Wochen seit Ausbildungsbeginn</th>
                            <th>Vorhandene Berichtshefte</th>
                            <th>Fehlende Berichtshefte</th>
                            <th>Berichtshefte ansehen</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($azubis as $azubi)
                            <tr>
                                <td>{{ $azubi->full_name }}</td>
                                @if(is_null($azubi->ausbildungsbeginn))
                                    <td colspan="3">Es wurden noch keine Berichtshefte angelegt.</td>
                                @else
                                    <td>{{ $azubi->criteria['dauer'] }}</td>
                                    <td>{{ $azubi->criteria['anzahl'] }}</td>
                                    <td>{{ $azubi->criteria['fehlend'] }}</td>
                                @endif
                                <td>
                                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.berichtshefte.liste', $azubi) }}">Ansehen</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $azubis->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

