{{-- modales Fenster zum Verwalten der Adresse --}}

<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profil verwalten</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    {{-- Formular als Tabelle --}}
                    <tr>
                        <th colspan="2" class="text-center">Adresse verwalten</th>
                    </tr>
                    <tr>
                        <td><label for="strasse">Straße</label></td>
                        <td>
                            <input class="form-control @error('strasse') is-invalid @enderror" name="strasse"
                                   id="strasse" form="address_form" type="text" value="{{ app()->user->strasse }}"/>
                            @error("strasse")
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="hausnr">Hausnummer</label></td>
                        <td>
                            <input class="form-control @error('hausnr') is-invalid @enderror" name="hausnr"
                                   id="hausnr" form="address_form" type="text" value="{{ app()->user->hausnr }}"/>
                            @error("hausnr")
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="plz">Postleitzahl</label></td>
                        <td>
                            <input class="form-control @error('plz') is-invalid @enderror" name="plz"
                                   id="plz" form="address_form" type="text" value="{{ app()->user->plz }}"/>
                            @error("plz")
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td><label for="ort">Ort</label></td>
                        <td>
                            <input class="form-control @error('ort') is-invalid @enderror" name="ort"
                                   id="ort" form="address_form" type="text" value="{{ app()->user->ort }}"/>
                            @error("ort")
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>

                <form class="form" id="address_form" action="{{ route('user.address') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Profil speichern</button>
                </form>
            </div>
        </div>
    </div>
</div>
