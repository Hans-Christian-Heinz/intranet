@extends('layouts.app')

@section('title', "Projektdokumentation-Bilder · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row bg-white">
                <div class="col-md-12 py-2">
                    <div class="d-flex pb-3">
                        <h3 class="mr-auto">Bilder für die Projektdokumentation</h3>
                    </div>
                    <table class="table text-center">
                        @foreach($image_files as $file)
                            <tr class="border-bottom-0">
                                <td colspan="3" class="border-bottom-0">
                                    <img src="{{ asset('storage/' . $file) }}" alt="Datei wurde nicht gefunden.">
                                </td>
                            </tr>
                            <tr class="border-top-0">
                                <td colspan="2" class="border-top-0"></td>
                                <td class="border-top-0">
                                    <a class="btn btn-outline-danger @error('datei') is-invalid @enderror"
                                       data-toggle="modal" href="#deleteImage{{ $loop->index }}">
                                        Bilddatei löschen
                                    </a>
                                    @error('datei') <p class="invalid-feedback">{{ $message }}</p> @enderror
                                    @include('abschlussprojekt.bilder.loeschenModal', ['index' => $loop->index,])
                                </td>
                            </tr>
                        @endforeach
                        @if($image_files->isEmpty())
                            <tr>
                                <th colspan="3">Es wurden noch keine Bilddateien hochgeladen</th>
                            </tr>
                        @endif
                        <tr class="border-bottom-0">
                            <th colspan="3" class="border-bottom-0">Bilddatei hochladen:</th>
                        </tr>
                        <tr class="border-top-0">
                            <td colspan="2" class="border-top-0 text-left">
                                <div class="input-group">
                                   <div class="custom-file">
                                        <input type="file" id="bilddatei" form="formBild" required name="bilddatei"
                                               class="custom-file-input @error('bilddatei') is-invalid @enderror"
                                               accept="image/jpeg, image/jpg, image/png, image/bmp, image/gif, image/svg, image/webp"/>
                                        <label class="custom-file-label" for="bilddatei">Bilddatei</label>
                                       @error('bilddatei') <p class="invalid-feedback">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </td>
                            <td class="border-top-0">
                                <form class="form" action="{{ route('abschlussprojekt.bilder.upload', $project) }}"
                                      method="post" id="formBild" enctype="multipart/form-data">
                                    @csrf
                                    <input type="submit" class="btn btn-primary" form="formBild" value="Hochladen"/>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>

                {{ $image_files->links() }}
            </div>
        </div>
    </div>
@endsection
