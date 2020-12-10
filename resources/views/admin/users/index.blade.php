@extends('layouts.app')

@section('title', 'Benutzer verwalten · ')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg-white">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Benutzer</h3>
                        <div class="ml-auto w-25, pt-1">
                            @include('components.searchUsers', ['url' => route('admin.users.index') . '/_id'])
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center text-strong" style="width: 2%;">#</th>
                                <th class="text-center">Nutzername</th>
                                <th class="text-center">Klarname</th>
                                <th class="text-center" style="width: 15%;">Regeln akzeptiert</th>
                                <th class="text-center" style="width: 12%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->ldap_username }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>
                                        @if ($user->hasAcceptedRules())
                                            <span class="fa fa-check text-success mr-5"></span>
                                            {{ $user->accepted_rules_at->format('d.m.Y')}}
                                        @else
                                            <span class="fa fa-times text-danger"></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#deleteUserModal{{ $user->id }}" data-toggle="modal" class="btn btn-outline-danger">Löschen</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
    @foreach($users as $user)
        @include('admin.users.deleteUserModal')
    @endforeach
@endsection
