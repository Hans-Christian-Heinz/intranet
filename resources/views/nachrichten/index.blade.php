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
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-center">Absender</th>
                                <th class="text-center">Empfänger</th>
                                <th class="text-center">Betreff</th>
                                <th class="text-center">Datum</th>
                                <th class="text-center">Gelesen</th>
                                <th class="text-center">Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($nachrichten as $message)
                                <tr>
                                    <td>{{ $message->data['absender'] }}</td>
                                    <td>{{ app()->user->full_name }}</td>
                                    <td>{{ $message->data['betreff'] }}</td>
                                    <td>{{ \Carbon\Carbon::instance($message->created_at)->format('d.m.Y') }}</td>
                                    <td>{{ $message->read_at ? 1 : 0 }}</td>
                                    <td>
                                        <a href="{{ route('user.nachrichten.detail', ['message' => $message]) }}">Lesen</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
