@extends('layouts.app')

@section('title', 'Administration · ')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="card">
                        <a href="{{ route('admin.exemptions.index') }}" class="text-dark">
                            <div class="card-body">
                            <h1 class="card-title text-center display-2 {{ $exemptionCount ? 'text-primary font-weight-bold' : '' }}">
                                {{ $exemptionCount }}
                            </h1>
                                <p class="card-text text-center">neue Freistellungsanträge</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <a href="{{ route('admin.users.index') }}" class="text-dark">
                            <div class="card-body">
                                <h1 class="card-title text-center display-2">{{ $userCount }}</h1>
                                <p class="card-text text-center">registrierte Benutzer</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
