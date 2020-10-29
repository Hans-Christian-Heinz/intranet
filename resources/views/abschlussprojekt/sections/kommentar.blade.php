{{-- Zeige alle Kommentare, die zu diesem Abschnitt gehören, sowie ein Formular, um neue Kommentare zu erstellen --}}

<div class="mt-2 pt-1 border-top">
    {{-- Vorhandene Kommentare --}}
    @foreach($comments as $comment)
        @include('abschlussprojekt.sections.kommentarHelp')
    @endforeach

    {{-- Formular für neue Kommentare --}}
    <form method="post" class="form-horizontal formAddComment mt-4" @if(request()->is('*antrag'))
          action="{{ route('abschlussprojekt.antrag.comment', ['project' => $proposal->project, 'proposal' => $proposal,]) }}"
          @else action="{{ route('abschlussprojekt.dokumentation.comment',
                    ['project' => $documentation->project, 'documentation' => $documentation,]) }}" @endif>
        @csrf
        <input type="hidden" required name="section_name" value="{{ $section->name }}"/>
        <div class="form-group row">
            <label for="comment{{ $section->id }}" class="control-label col-lg-2">Kommentar:</label>
            <input type="text" required class="form-control col-lg-10" name="text" id="comment{{ $section->id }}"/>
        </div>
        <input type="submit" class="float-right btn btn-outline-primary" value="Kommentar speichern"/>
    </form>
</div>
