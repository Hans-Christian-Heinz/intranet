@extends('layouts.app')

@section('title', 'Werkstattregeln bearbeiten · ')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @error("rules")
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto my-auto">Werkstattregeln</h3>
                        <a href="{{ route("rules.index") }}" class="btn btn-outline-secondary mr-2"><span class="far fa-eye mr-2"></span>Zu den Werkstattregeln</a>
                        <button type="submit" class="btn btn-outline-primary" form="saveRules">
                            <span class="fa fa-floppy-o mr-2"></span>Änderungen speichern
                        </button>
                    </div>
                    <form action="{{ route("admin.rules.update") }}" method="POST" id="saveRules">
                        @csrf
                        @method("PATCH")
                        <textarea name="rules" id="rules">{{ $rules }}</textarea>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset("js/tinymce.js") }}"></script>
    <script>
        tinymce.init({
            selector: "#rules",
            skin_url: "/css/tinymce/skins/ui/oxide",
            content_css: "/css/tinymce/skins/content/default/content.css",
            menubar: false,
            statusbar: false,
            height: "650px",
            plugins: ["lists"],
            toolbar: "undo redo | styleselect | fontsizeselect | bullist numlist | bold italic | alignleft aligncenter alignright alignjustify"
        });
    </script>
@endsection
