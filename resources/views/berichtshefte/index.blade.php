@extends('layouts.app')

@section('title', "Berichtshefte · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row bg-white">
                <div class="col-md-12 py-2">
                    @if ($berichtshefte->count())
                        <div class="d-flex pb-3">
                            <h3 class="mr-auto">Berichtsheft</h3>
                            <a href="{{ route("berichtshefte.create") }}" class="btn btn-outline-primary">
                                <span class="fa fa-plus mr-2"></span>Hinzufügen
                            </a>
                        </div>
                        <table class="table table-striped table-bordered text-center">
                            <tr>
                                <th data-toggle="tooltip" data-placement="bottom" title="Ausbildungsbeginn hier: Früheste Woche, für die ein Wochenbericht hinterlegt ist.">
                                    Wochen seit Ausbildungsbeginn
                                </th>
                                <th>Vorhandene Berichtshefte</th>
                                <th>Fehlende Berichtshefte</th>
                            </tr>
                            <tr>
                                @if($criteria)
                                    <td>{{ $criteria['dauer'] }}</td>
                                    <td>{{ $criteria['anzahl'] }}</td>
                                    <td @if($criteria['fehlend'] > 0) class="text-danger" @endif>{{ $criteria['fehlend'] }}</td>
                                @else
                                    <td colspan="3">Es wurden noch keine Berichtshefte angelegt.</td>
                                @endif
                            </tr>
                        </table>

                        {{-- fehlende Wochen --}}
                        @if(count($criteria['missing']))
                            <a class="text-danger" data-toggle="collapse" href="#missingWeeks" role="button" aria-expanded="false"
                               aria-controls="missingWeeks">Fehlende Wochen</a>
                            <div class="collapse" id="missingWeeks">
                                <ul>
                                    @foreach($criteria['missing'] as $missing)
                                        <li>{{ $missing->format("Y-W") }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <table class="table table-striped table-bordered table-hover mt-2">
                            <thead>
                                <tr>
                                    <th class="text-center text-strong" style="width: 2%;">#</th>
                                    <th>Lehrjahr</th>
                                    <th class="text-center" style="width: 13%;">KW</th>
                                    <th class="text-center" style="width: 20%;">Zeitraum</th>
                                    <th class="text-center" style="width: 11%;">Optionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($berichtshefte as $berichtsheft)
                                    <tr>
                                        <td class="text-center">{{ $berichtsheft->id }}</td>
                                        <td>{{ $berichtsheft->grade }}</td>
                                        <td class="text-center">{{ $berichtsheft->week->format("Y-W") }}</td>
                                        <td class="text-center">
                                            {{ $berichtsheft->week->startOfWeek()->format("d.m.Y") }} - {{ $berichtsheft->week->endOfWeek()->format("d.m.Y") }}
                                        </td>
                                        <td>
                                            <a href="{{ route("berichtshefte.edit", $berichtsheft) }}" class="btn btn-sm btn-secondary">
                                                <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                            </a>
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
                                <a href="{{ route("berichtshefte.create") }}" class="btn btn-outline-primary">
                                    <span class="fa fa-plus mr-2"></span>Berichtsheft hinzufügen
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
