{{-- Modales Fenster zum Einfügen von Bildern, Tabellen, Listen und Links --}}

@php($sectionsHelp = $version->sections->sortBy("heading"))

<div class="modal fade section-modal" id="insertPlaceholders" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Einfügen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Navigationsleiste --}}
                <ul class="nav nav-pills" id="insertTab" role="tablist">
                    @foreach(App\Section::INSERT as $insert)
                        <li class="nav-item">
                            <a class="nav-link @if($loop->first) active" @endif aria-controls="insert_{{ $insert }}" href="#insert_{{ $insert }}"
                            @if($loop->first) aria-selected="true" @else aria-selected="false" @endif id="insert_{{ $insert }}_tab" data-toggle="tab">
                                {{ ucfirst($insert) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                {{-- Tabinhalt --}}
                <div class="tab-content my-3" id="insertTabContent">
                    @foreach(App\Section::INSERT as $insert)
                        <div class="tab-pane @if($loop->first) active show @endif" id="insert_{{ $insert }}" role="tabpanel"
                             aria-labelledby="insert_{{ $insert }}_tab">
                            @include('abschlussprojekt.insertModal.' . $insert)
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
