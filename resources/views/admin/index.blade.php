@extends('layouts.app')

@section('title', 'Administration · ')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-2 row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                              <h2 class="card-title text-center">{{ $exemptionCount }}</h2>
                              <p class="card-text text-center">neue Freistellungsanträge</p>
                            </div>
                          </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                              <h2 class="card-title text-center">{{ $userCount }}</h2>
                              <p class="card-text text-center mb-4">registrierte Benutzer</p>
                            </div>
                          </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
