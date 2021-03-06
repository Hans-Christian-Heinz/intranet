@extends('layouts.app')

@section('title', "Bewerbungen: Betriebe · ")

@push('styles')
    <style>
        .pagination {
            margin-bottom: 0px;
        }
    </style>
@endpush

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if (count($companies) || !empty($q))
                        <div class="d-flex justify-content-between">
                            <h3 class="my-auto">Firmen</h3>
                            <a href="{{ route("bewerbungen.companies.create") }}" class="btn btn-outline-primary">Firma anlegen</a>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <form action="{{ route("bewerbungen.companies.index") }}" id="formCompany" method="get" class="my-auto">
                                        Zeige
                                        <select name="perPage" onchange="this.form.submit();">
                                            @foreach ([10, 25, 50, 75, 100] as $count)
                                                <option value="{{ $count }}" {{ $companies->perPage() == $count ? "selected" : ""}}>{{ $count }}</option>
                                            @endforeach
                                        </select> Firmen pro Seite
                                    </form>

                                    <div>
                                        <select onchange="this.form.submit();" form="formCompany" class="form-control" name="s">
                                            <option value="">Alle Standorte</option>
                                            @foreach($standorte as $ort)
                                                <option {{ $ort->city == $s ? "selected" : "" }} value="{{ $ort->city }}">{{ $ort->city }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <input onchange="this.form.subimt();" form="formCompany" type="text" class="form-control" name="q" placeholder="Suchen..." value="{{ request()->input("q") }}" autofocus>
                                    </div>
                                </div>

                                <table class="table table-hover mt-3">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Beschreibung</th>
                                        <th>Standort</th>
                                        <th class="text-center" style="width: 10%;">Aktionen</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($companies as $company)
                                        <tr>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ Str::limit($company->description, 50) }}</td>
                                            <td>{{ $company->city }}</td>
                                            <td class="text-center">
                                                <a href="{{ route("bewerbungen.companies.show", $company) }}">Anzeigen</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-between">
                                    <p class="my-auto">
                                        @php
                                            $currentPageStart = $companies->perPage() * $companies->currentPage() - $companies->perPage();
                                        @endphp

                                        {{ $currentPageStart + 1 }} bis {{ $currentPageStart + count($companies) }} von {{ $companies->total() }}
                                    </p>

                                    {{ $companies->appends(['perPage' => $companies->perPage(), "q" => request()->input("q")])->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center p-5">
                                <h3>Es wurden noch keine Firmen angelegt</h3>
                                <a href="{{ route("bewerbungen.companies.create") }}" class="btn btn-outline-primary mt-3">Firma anlegen</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
