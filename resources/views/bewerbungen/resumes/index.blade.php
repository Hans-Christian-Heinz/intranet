@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div class="row p-3">
            <h3>Lebenslauf</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <resume :user="{{ app()->user }}" printroute="{{ route("bewerbungen.resumes.print") }}"></resume>
            </div>
        </div>
    </div>
</div>

@include('bewerbungen.formatPdfModal', ['route' => route("bewerbungen.resumes.print"),])
@endsection
