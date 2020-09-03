@extends('layouts.app')

@section('title', "Benachrichtigungen · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Benachrichtigungen</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th>Absender</th>
                                <th>Empfänger</th>
                                <th>Betreff</th>
                                <th>Datum</th>
                                <th>Gelesen</th>
                                <th>Detail</th>
                                <th>Löschen</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($nachrichten as $message)
                                <tr>
                                    <td class="text-left">{{ $message->data['absender'] }}</td>
                                    <td class="text-left">{{ app()->user->full_name }}</td>
                                    <td class="text-left">{{ $message->data['betreff'] }}</td>
                                    <td>{{ \Carbon\Carbon::instance($message->created_at)->format('d.m.Y') }}</td>
                                    <td>{{ $message->read_at ? 1 : 0 }}</td>
                                    <td>
                                        <a href="{{ route('user.nachrichten.detail', ['message' => $message]) }}">Lesen</a>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="chooseMessage" value="{{ $message->id }}" name="delete[]" form="formDeleteMessages"/>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5"></td>
                                <td>
                                    <button type="button" id="chooseAllMessages" class="btn btn-secondary">Alle auswählen</button>
                                </td>
                                <td class="text-center">
                                    <a data-toggle="modal" href="#deleteMessagesModal" class="btn btn-outline-danger">Löschen</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('nachrichten.deleteMessagesModal')
@endsection
