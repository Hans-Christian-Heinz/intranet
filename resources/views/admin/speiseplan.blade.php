@extends('layouts.app')

@section('title', "Speiseplan · ")

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Speiseplan hochladen</h3>
                    </div>
                    <div class="card-body">
                        <form class="d-flex" action="{{ route('admin.speiseplan.save') }}" enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="custom-file mx-2">
                                <input type="file" id="speiseplan" name="speiseplan" class="custom-file-input"
                                       accept="application/pdf" required/>
                                <label class="custom-file-label" for="speiseplan">Speiseplan</label>
                            </div>
                            <input type="submit" class="btn btn-primary mx-2" value="Hochladen"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
