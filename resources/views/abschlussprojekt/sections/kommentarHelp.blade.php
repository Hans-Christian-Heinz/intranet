{{-- Zeige eine Kommentar und eine Link zum Löschen des Kommentars an --}}

<div id="showComment{{ $comment->id }}">
    {{--@if(app()->user->is($comment->getDocument()->project->user))--}}
    @unless($comment->deleted_at)
        <input data-url="{{ route('abschlussprojekt.acknowledge_comment', $comment) }}" class="acknowledge-comment mr-1"
               type="checkbox" @if(!app()->user->is($comment->getDocument()->project->user)) disabled @endif @if($comment->acknowledged) checked @endif/>
    @endunless
    {{--@endif--}}
    <span class="comment @if($comment->deleted_at) text-danger @endif">{{ $comment->comment }}</span>
    <br/>
    @if($comment->deleted_at)
        <a href="{{ route('abschlussprojekt.restore_comment', $comment->id) }}" class="comment restoreComment mr-4">Wiederherstellen</a>
    @else
        <a href="#formAnswer{{ $comment->id }}" class="comment answerComment mr-4" id="answerComment{{ $comment->id }}">Antworten</a>
    @endif
    @if(app()->user->is($comment->user))
        {{-- Request wird asynchron in benutzerfreundlichkeit.js gesendet --}}
        @if($comment->deleted_at)
            <a href="{{ route('abschlussprojekt.force_delete_comment', $comment->id) }}" id="deleteComment{{ $comment->id }}" class="comment deleteComment">
                Kommentar endgültig löschen
            </a>
        @else
        <a href="{{ route('abschlussprojekt.delete_comment', $comment) }}" id="deleteComment{{ $comment->id }}" class="comment deleteComment">
            Kommentar löschen
        </a>
        @endif
    @endif
    @unless($comment->deleted_at)
    <div class="d-flex">
        <form class="form form-inline formAnswer w-100" style="display: none;" method="post" action="{{ route('abschlussprojekt.answer_comment', $comment) }}"
              id="formAnswer{{ $comment->id }}" data-container="answersTo{{ $comment->id }}">
            @csrf
            <input type="submit" class="btn btn-outline-primary float-right" value="Antworten"/>
            <input type="text" required class="form-control float-right w-75" name="text" id="answer{{ $comment->id }}"/>
        </form>
    </div>
    @endunless

    @if($comment->answers)
        <div class="ml-5" id="answersTo{{ $comment->id }}">
            @foreach($comment->answers as $answer)
                @include('abschlussprojekt.sections.kommentarHelp', ["comment" => $answer])
            @endforeach
        </div>
    @endif
</div>
