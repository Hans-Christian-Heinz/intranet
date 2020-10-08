@extends('layouts.app')

@section('title', "Veränderungsverlauf · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row bg-white">
                <div class="col-md-12 py-2">
                    <div class="d-flex pb-3">
                        <h3 class="mx-auto">Projekt{{ $doc_type }} {{ $document->project->user->full_name }}: Veränderungsverlauf</h3>
                    </div>
                    {{-- Tabelle der Versionen --}}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Version</th>
                            <th>Geändert von</th>
                            <th>
                                <form id="formVergleichen" method="post" class="form text-center"
                                      @if(request()->is('admin*'))
                                      action="{{ route('admin.abschlussprojekt.versionen.vergleich',
                                                ['project' => $document->project, 'doc_type' => $doc_type,]) }}"
                                      @else
                                      action="{{ route('abschlussprojekt.versionen.vergleich',
                                                ['project' => $document->project, 'doc_type' => $doc_type,]) }}"
                                    @endif>
                                    @csrf
                                    <input type="submit" class="btn btn-secondary" name="help" value="Vergleichen"/>
                                    @error("vergleichen") <span class="text-danger">{{ $message }}</span> @enderror
                                </form>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($versions as $version)
                            <tr>
                                <td>{{ $version->updated_at }}</td>
                                <td>{{ $version->user->full_name }}</td>
                                <td>
                                    <div class="text-center">
                                        <input type="checkbox" value="{{ $version->id }}" name="vergleichen[]" form="formVergleichen"/>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a class="btn btn-outline-danger" data-toggle="modal" href="#clearHistoryModal">Verlauf löschen</a>
                    @include('abschlussprojekt.versionen.clearHistory', ['project' => $document->project,])
                </div>
            </div>
        </div>
    </div>
@endsection
