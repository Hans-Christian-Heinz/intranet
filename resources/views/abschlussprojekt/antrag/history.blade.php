@extends('layouts.app')

@section('title', "Projektantrag · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Projektantrag: Änderungsverlauf</h3>
                    </div>
                    {{-- Tabelle der Versionen --}}
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Geändert von</th>
                                <th>
                                    <form id="formVergleichen" method="post" class="form text-center"
                                          action="{{ route('abschlussprojekt.antrag.vergleich', $proposal->project) }}">
                                        @csrf
                                        <input type="submit" class="btn btn-secondary" name="help" value="Vergleichen"/>
                                        @error("vergleichen") <span class="text-danger">{{ $message }}</span> @enderror
                                    </form>
                                </th>
                                <th>Wiederherstellen</th>
                                <th>Löschen</th>
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
                                <td>TODO</td>
                                <td>TODO</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
