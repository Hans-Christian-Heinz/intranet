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
                                    aktualisiert am <b>{{ $rules->updated_at->format('d.m.Y') }}</b>
                                </span>
                            </div>

                            <hr>
                                {!! $rules->value !!}
                            <hr>

                            <div>
                                @if($hasAcceptedRules)
                                    <p class="text-secondary my-auto">
                                        Sie haben die Werkstattregeln am <b>{{ $acceptedRulesAt->format('d.m.Y') }}</b> akzeptiert.
                                    </p>
                                @else
                                <form action="{{ route('rules.accept') }}" method="POST" class="form-inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group ml-auto">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input @error('accept-rules') is-invalid @enderror"
                                                id="accept-rules" name="accept-rules" required>
                                            <label class="custom-control-label" for="accept-rules">
                                                Ich habe die Werkstattregeln durchgelesen und akzeptiere sie.
                                            </label>
                                            @error('accept-rules')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                          </div>
                                    </div>
                                    <div class="form-group ml-5">
                                        <button type="submit" class="btn btn-primary">Absenden</button>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
