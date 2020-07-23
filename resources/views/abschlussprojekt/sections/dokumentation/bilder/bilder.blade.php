{{-- Zeige die Bilder eines Abschnitts an und stelle Möglichkeiten zur Bearbeitung zur Verfügung --}}

@if(request()->is('*dokumentation'))
    @include('abschlussprojekt.sections.dokumentation.image_disclaimer')
@endif

<table class="table table-striped">
    @foreach($s->images as $image)
        <tr class="align-middle">
            <td>
                <img height="{{ $image->height }}" width="{{ $image->width }}" src="{{ asset('storage/' . $image->path) }}"
                     alt="Bilddatei wurde nicht gefunden"/>
                <br/>
                <span>{{ $image->footnote }}</span>
            </td>
            <td>
                @if(request()->is('*dokumentation'))
                    <a data-toggle="modal" class="btn btn-secondary" href="#bildBearbeiten{{ $image->id }}">Bild bearbeiten</a>
                @endif
            </td>
            <td>
                @if(request()->is('*dokumentation'))
                    <a data-toggle="modal" class="btn btn-outline-danger" href="#bildEntfernen{{ $image->id }}">Bild entfernen</a>
                @endif
            </td>
        </tr>
    @endforeach
</table>

@foreach($s->images as $image)
    @if(request()->is('*dokumentation'))
        @include('abschlussprojekt.sections.dokumentation.bilder.bildBearbeitenModal')
        @include('abschlussprojekt.sections.dokumentation.bilder.bildEntfernenModal')
    @endif
@endforeach
