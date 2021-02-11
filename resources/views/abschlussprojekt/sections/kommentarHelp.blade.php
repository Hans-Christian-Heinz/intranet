{{-- Zeige eine Kommentar und eine Link zum Löschen des Kommentars an --}}

<div>
    @if(app()->user->is($comment->getDocument()->project->user))
        <input data-url="{{ route('abschlussprojekt.acknowledge_comment', $comment) }}" class="acknowledge-comment mr-1"
               type="checkbox" @if($comment->acknowledged) checked @endif/>
    @endif
    <span class="comment">{{ $comment->comment }}</span>
    <br/>
    @if(app()->user->is($comment->getDocument()->project->user) || app()->user->is($comment->user))
        {{-- Request wird asynchron in benutzerfreundlichkeit.js gesendet --}}
        <a href="{{ route('abschlussprojekt.delete_comment', $comment) }}" id="deleteComment{{ $comment->id }}" class="comment deleteComment">
            Kommentar löschen
        </a>
    @endif
</div>
