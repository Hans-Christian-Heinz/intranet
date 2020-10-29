{{-- Zeige eine Kommentar und eine Link zum Löschen des Kommentars an --}}

<div>
    <span class="comment">{{ $comment->comment }}</span><br/>
    @if(app()->user->is($comment->getDocument()->project->user) || app()->user->is($comment->user))
        {{-- Request wird asynchron in benutzerfreundlichkeit.js gesendet --}}
        <a href="{{ route('abschlussprojekt.delete_comment', $comment) }}" class="comment deleteComment">Kommentar löschen</a>
    @endif
</div>
