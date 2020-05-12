@extends('layouts.app')

@section('title', 'Freistellungen · ')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if ($newExemptions->where('status', 'new')->count())
                        <div class="d-flex pb-3">
                            <h3 class="mr-auto">neue Freistellungen</h3>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center text-strong" style="width: 2%;">#</th>
                                    <th class="text-center">Benutzer</th>
                                    <th class="text-center" style="width: 15%">Zeitraum</th>
                                    <th class="text-center">Begründung</th>
                                    <th class="text-center" style="width: 10%;">Status</th>
                                    <th class="text-center" style="width: 12%">Optionen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($newExemptions as $exemption)
                                    <tr>
                                        <td class="text-center">{{ $exemption->id }}</td>
                                        <td class="text-center">{{ $exemption->owner->ldap_username }}</td>
                                        <td>
                                            {{ $exemption->start->format('d. M Y H:i') }} –<br>
                                            {{$exemption->end->format('d. M Y H:i') }}
                                        </td>
                                        <td>{{ $exemption->reason }}</td>
                                        <td class="text-center">{{ $statuses[$exemption->status] }}</td>
                                        <td>
                                            <a href="{{ route("admin.exemptions.edit", $exemption) }}" class="btn btn-sm btn-secondary">
                                                <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="card">
                            <div class="card-body text-center p-5">
                                <h3>keine Freistellungen vorhanden</h3>
                                <p>Aktuell sind keine neuen Freistellungen vorhanden.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @if ($pastExemptions->count())
                <div class="row">
                    <div class="col-md-12">
                            <div class="d-flex pb-3">
                                <h3 class="mr-auto mt-4">vergangene Freistellungen</h3>
                            </div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center text-strong" style="width: 2%;">#</th>
                                        <th class="text-center">Benutzer</th>
                                        <th class="text-center" style="width: 15%">Zeitraum</th>
                                        <th class="text-center">Begründung</th>
                                        <th class="text-center" style="width: 10%;">Status</th>
                                        <th class="text-center" style="width: 12%">Optionen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pastExemptions as $exemption)
                                        <tr>
                                            <td class="text-center">{{ $exemption->id }}</td>
                                            <td class="text-center">{{ $exemption->owner->ldap_username }}</td>
                                            <td>
                                                {{ $exemption->start->format('d. M Y H:i') }} –<br>
                                                {{$exemption->end->format('d. M Y H:i') }}
                                            </td>
                                            <td>{{ $exemption->reason }}</td>
                                            <td class="text-center">
                                                {{ $statuses[$exemption->status] }}
                                                @if($exemption->admin) von {{ $exemption->admin->ldap_username }}@endif
                                            </td>
                                            <td>
                                                <a href="{{ route("admin.exemptions.edit", $exemption) }}" class="btn btn-sm btn-secondary">
                                                    <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
