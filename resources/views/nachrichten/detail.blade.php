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
                    <table class="table table-striped table-bordered table-hover mb-2">
                        <thead>
                        <tr>
                            <th>Von {{ $message->data['absender'] }}</th>
                            <th>{{ app()->user->full_name }}</th>
                            <th>{{ $message->data['betreff'] }}</th>
                            <th>{{ \Carbon\Carbon::instance($message->created_at)->format('d.m.Y H:i:s') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="4">{{ $message->data['inhalt'] }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <form class="form form-inline ml-auto p-3" action="{{ route('user.nachrichten.delete', compact('message')) }}"
                      method="post">
                    @csrf
                    @method('delete')
                    <a class="btn btn-secondary mx-2" href="{{ route('user.nachrichten') }}">Zurück</a>
                    <input class="btn btn-primary mx-2" type="submit" value="Löschen"/>
                </form>
            </div>
        </div>
    </div>
@endsection
