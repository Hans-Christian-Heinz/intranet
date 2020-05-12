@extends('layouts.app')

@section('title', 'Werkstattregeln · ')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <h3 class="mr-auto my-auto">Werkstattregeln</h3>
                                <span class="text-secondary my-auto">
                                    aktualisiert am <b>{{ $rules->updated_at->format("d.m.Y") }}</b>
                                </span>
                            </div>
                            <hr>
                            {!! $rules->value !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
