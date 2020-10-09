{{-- Modales Fenster zum Formatieren eines PDF-Dokuments (Lebenslauf oder Bewerbungsanschreiben) --}}

@push('modals')
    <div class="modal fade" id="formatPdf" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PDF formatieren</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        {{-- Text formatieren --}}
                        <tr>
                            <th colspan="2" class="text-center">Text formatieren</th>
                        </tr>
                        <tr>
                            <td><label for="textfarbe">Textfarbe</label></td>
                            <td>
                                <input class="form-control @error('textFarbe') is-invalid @enderror" name="textfarbe"
                                       id="textfarbe" form="format_pdf" type="color"/>
                                @error("textFarbe")
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label for="textart">Textart</label></td>
                            <td>
                                <select class="form-control @error('textart') is-invalid @enderror" name="textart" id="textart" form="format_pdf">
                                    @foreach(App\Project::FONTS as $name => $var)
                                        <option class="{{ $var }}" @if($var == 'times') selected @endif value="{{ $var }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("textart")
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label for="textgroesse">Textgröße</label></td>
                            <td>
                                <input name="textgroesse" id="textgroesse" type="number" form="format_pdf"
                                       class="form-control @error('texgroesse') is-invalid @enderror" step="1" min="4" value="10" max="15"/>
                                @error("texgroesse")
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ueberschrFarbe">Überschriftenfarbe</label></td>
                            <td>
                                <input class="form-control @error('ueberschrFarbe') is_invalid @enderror" name="ueberschrFarbe"
                                       form="format_pdf" id="ueberschrFarbe" type="color" value="#336699"/>
                                @error("ueberschrFarbe")
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                    <form class="form" id="format_pdf" action="{{ $route }}" method="POST" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-primary">PDF erstellen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
