@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <resume :user="{{ app()->user }}" printroute="{{ route("bewerbungen.resumes.print") }}"></resume>
            </div>
        </div>
    </div>
</div>
@endsection
