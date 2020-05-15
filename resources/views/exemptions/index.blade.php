@extends('layouts.app')

@section('title', "Freistellungen · ")

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
                                    <th class="text-center" style="width: 15%;">Zeitraum</th>
                                    <th class="text-center">Begründung</th>
                                    <th class="text-center" style="width: 10%;">Status</th>
                                    <th class="text-center" style="width: 11%;">Optionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exemptions as $exemption)
                                    <tr>
                                        <td class="text-center">{{ $exemption->id }}</td>
                                        <td>
                                            @if ($exemption->start->format('H:i') === '00:00')
                                                {{ $exemption->start->format('d. M Y') }}
                                            @else
                                                {{ $exemption->start->format('d. M Y H:i') }}
                                            @endif
                                            –<br>
                                            @if ($exemption->end->format('H:i') === '00:00')
                                                {{ $exemption->end->format('d. M Y') }}
                                            @else
                                                {{ $exemption->end->format('d. M Y H:i') }}
                                            @endif
                                        </td>
                                        <td>{{ $exemption->reason }}</td>
                                        <td class="text-center">
                                            {{ $statuses[$exemption->status] }}
                                            @if($exemption->admin) von {{ $exemption->admin->ldap_username }}@endif
                                        </td>
                                        <td>
                                            @if($exemption->status === 'new')
                                                <a href="{{ route("exemptions.edit", $exemption) }}" class="btn btn-sm btn-secondary mx-auto">
                                                    <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="card">
                            <div class="card-body text-center p-5">
                                <h3>Noch keine Freistellungen vorhanden</h3>
                                <p>Sie können hier eine neue Freistellung beantragen</p>
                                <a href="{{ route("exemptions.create") }}" class="btn btn-outline-primary">
                                    <span class="fas fa-plus mr-2"></span>Freistellung beantragen
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
