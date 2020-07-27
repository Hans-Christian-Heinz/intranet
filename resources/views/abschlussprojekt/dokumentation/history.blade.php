@extends('layouts.app')

@section('title', "Projektdokumentation · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Projektdokumentation {{ $documentation->project->user->full_name }}: Veränderungsverlauf</h3>
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
                                        action="{{ route('admin.abschlussprojekt.dokumentation.vergleich', $documentation->project) }}"
                                      @else
                                        action="{{ route('abschlussprojekt.dokumentation.vergleich', $documentation->project) }}"
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
                    <a class="btn btn-outline-danger float-right" data-toggle="modal" href="#clearHistoryModal">Verlauf löschen</a>
                    @include('abschlussprojekt.clearHistory',
                        ['route' => 'abschlussprojekt.dokumentation.clear_history', 'project' => $documentation->project,])
                </div>
            </div>
        </div>
    </div>
@endsection
