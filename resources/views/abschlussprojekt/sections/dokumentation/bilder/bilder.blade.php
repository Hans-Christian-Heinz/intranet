{{-- Zeige die Bilder eines Abschnitts an und stelle Möglichkeiten zur Bearbeitung zur Verfügung --}}

@if(request()->is('*dokumentation'))
    @include('abschlussprojekt.sections.dokumentation.image_disclaimer')
@endif

<table class="table table-striped">
    @foreach($s->images as $image)
        <tr>
            <td>
                <img height="{{ $image->height }}" width="{{ $image->width }}" src="{{ asset('storage/' . $image->path) }}"
                     alt="Bilddatei wurde nicht gefunden"/>
                <br/>
                <span>{{ $image->footnote }}</span>
            </td>
            <td>
                @if(request()->is('*dokumentation'))
                    <a data-toggle="modal" class="btn btn-outline-danger" href="#bildEntfernen{{ $image->id }}">Bild entfernen</a>
                @endif
            </td>
        </tr>

        @if(request()->is('*dokumentation'))
            @include('abschlussprojekt.sections.dokumentation.bilder.bildEntfernenModal')
        @endif
    @endforeach
</table>
