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
                                    <a href="#chooseTopic" data-toggle="modal" class="btn btn-sm btn-secondary">
                                        <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Wählen
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">Projektantrag</td>
                                <td>
                                    <a @isset($project) href="{{ route('abschlussprojekt.antrag.index', $project) }}"
                                       @else href="#chooseTopic" data-toggle="modal" @endisset class="btn btn-sm btn-secondary">
                                        <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Projektdokumentation</td>
                                <td>
                                    <a @isset($project) href="{{ route('abschlussprojekt.dokumentation.index', $project) }}"
                                       @else href="#chooseTopic" data-toggle="modal" @endisset class="btn btn-sm btn-secondary">
                                        <span class="fa fa-pencil-square-o mr-1" aria-hidden="true"></span>Bearbeiten
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Bilder</td>
                                <td>
                                    <a @isset($project) href="#" @else href="#chooseTopic" data-toggle="modal" @endisset class="btn btn-sm btn-secondary">
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

    <!-- Dialog zum Wählen eines Themas -->
    <div class="modal fade" id="chooseTopic" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thema wählen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="text-center" for="topic">Bitte geben Sie das Thema Ihres Abschlussprojekts an.</label>
                    <input type="text" class="form-control @error('instructions') is-invalid @enderror" id="topic"
                           name="topic" form="chooseTopicForm" @isset($project) value="{{ $project->topic }}" @endisset/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-primary" form="chooseTopicForm">Speichern</button>

                    <form id="chooseTopicForm" @isset($project) action="{{ route('abschlussprojekt.update', $project) }}"
                          @else action="{{ route('abschlussprojekt.create') }}" @endisset method="POST">
                        @csrf
                        @isset($project)
                            @method('PATCH')
                        @endisset
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
