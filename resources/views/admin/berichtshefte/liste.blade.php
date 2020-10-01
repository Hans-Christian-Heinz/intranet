{{-- Eine Liste aller Berichtshefte eines Auszubildenden (für Ausbilder einsehbar) --}}

@extends('layouts.app')

@section('title', "Berichtshefte · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row bg-white">
                <div class="col-md-12 py-2">
                    <div class="d-flex pb-3">
                        <h3>Berichtshefte {{ $azubi->full_name }}</h3>
                    </div>
                    @if ($berichtshefte->count())
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center text-strong" style="width: 2%;">#</th>
                                <th>Lehrjahr</th>
                                <th class="text-center" style="width: 13%;">KW</th>
                                <th class="text-center" style="width: 11%;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($berichtshefte as $berichtsheft)
                                <tr>
                                    <td class="text-center">{{ $berichtsheft->id }}</td>
                                    <td>{{ $berichtsheft->grade }}</td>
                                    <td class="text-center">{{ $berichtsheft->week->format("Y-W") }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-secondary" href="{{ route('admin.berichtshefte.show', $berichtsheft) }}">
                                            Ansehen
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
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
