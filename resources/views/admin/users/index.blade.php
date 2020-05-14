@extends('layouts.app')

@section('title', 'Benutzer verwalten · ')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Benutzer</h3>
                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center text-strong" style="width: 2%;">#</th>
                                <th class="text-center">Nutzername</th>
                                <th class="text-center">Klarname</th>
                                <th class="text-center">E-Mail-Adresse</th>
                                <th class="text-center" style="width: 12%;">Rechte</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->ldap_username }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="d-flex">
                                        @if ($user->is_admin)
                                            <span class="text-danger mr-auto">Admin</span>
                                            <a href="{{ route('admin.users.demote', $user) }}">
                                                <span class="fa fa-user-times text-secondary font-weight-bold" aria-hidden="true"
                                                    data-toggle="tooltip" data-placement="right" title="Zu normalem Nutzer machen"></span>
                                            </a>
                                        @else
                                            <span class="text-muted mr-auto">Benutzer</span>
                                            <a href="{{ route('admin.users.promote', $user) }}">
                                                <span class="fa fa-user-plus text-primary font-weight-bold" aria-hidden="true"
                                                    data-toggle="tooltip" data-placement="right" title="Zu Admin machen"></span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
