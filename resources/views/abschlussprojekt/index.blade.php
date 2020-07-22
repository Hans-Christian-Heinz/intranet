@extends('layouts.app')

@section('title', "Abschlussprojekt · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Abschlussprojekt</h3>
                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 15%;">Thema</th>
                                <th class="text-center">@isset($project) {{ $project->topic }} @else - @endisset</th>
                                <th class="text-center" style="width: 11%;">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">Projektantrag</td>
                                <td>
                                    <a @isset($project->proposal) href="{{ route('abschlussprojekt.antrag.index', $project) }}"
                                       @else href="{{ route('abschlussprojekt.antrag.create', $project) }}" @endisset class="btn btn-sm btn-secondary">
                                        <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Projektdokumentation</td>
                                <td>
                                    <a @isset($project->documentation) href="{{ route('abschlussprojekt.dokumentation.index', $project) }}"
                                       @else href="{{ route('abschlussprojekt.dokumentation.create', $project) }}"
                                       @endisset class="btn btn-sm btn-secondary">
                                        <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Bilder</td>
                                <td>
                                    <a @isset($project) href="{{ route('abschlussprojekt.bilder.index', $project) }}"
                                       @else href="{{ route('abschlussprojekt.create') }}" @endisset class="btn btn-sm btn-secondary">
                                        <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
