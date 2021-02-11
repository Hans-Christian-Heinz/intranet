{{-- Zeige eine Kommentar und eine Link zum Löschen des Kommentars an --}}

<div>
    @if(app()->user->is($comment->getDocument()->project->user))
        <input data-url="{{ route('abschlussprojekt.acknowledge_comment', $comment) }}" class="acknowledge-comment mr-1"
               type="checkbox" @if($comment->acknowledged) checked @endif/>
    @endif
    <span class="comment">{{ $comment->comment }}</span>
    <br/>
    <a href="#formAnswer{{ $comment->id }}" class="comment answerComment mr-4" id="answerComment{{ $comment->id }}">Antworten</a>
    @if(app()->user->is($comment->getDocument()->project->user) || app()->user->is($comment->user))
        {{-- Request wird asynchron in benutzerfreundlichkeit.js gesendet --}}
        <a href="{{ route('abschlussprojekt.delete_comment', $comment) }}" id="deleteComment{{ $comment->id }}" class="comment deleteComment">
            Kommentar löschen
        </a>
    @endif
    <div class="d-flex">
        <form class="form form-inline formAnswer w-100" style="display: none;" method="post" action="{{ route('abschlussprojekt.answer_comment', $comment) }}"
              id="formAnswer{{ $comment->id }}" data-container="answersTo{{ $comment->id }}">
            @csrf
            <input type="submit" class="btn btn-outline-primary float-right" value="Antworten"/>
            <input type="text" required class="form-control float-right w-75" name="text" id="answer{{ $comment->id }}"/>
        </form>
    </div>

    @if($comment->answers)
        <div class="ml-5" id="answersTo{{ $comment->id }}">
            @foreach($comment->answers as $answer)
                @include('abschlussprojekt.sections.kommentarHelp', ["comment" => $answer])
            @endforeach
        </div>
    @endif
</div>
