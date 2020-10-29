@extends('layouts.app')

@section('title', "Bewerbungen: Vorlage · ")

@section('content')
    <div class="section">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <h3 class="my-auto">Versionen der Vorlage für Bewerbungsanschreiben</h3>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-hover table-striped bg-white">
            <thead>
            	<tr>
            		<th>Version</th>
                	<th>Anzahl an auf der Version basierenden Dokumenten</th>
                	<th></th>
            	</tr>
            </thead>
            <tbody>
            	@foreach($versionen as $v)
            	<tr>
            		<td>{{ $v->version }}</td>
            		<td>{{ $v->anzahl }}</td>
            		<td>
						<a data-toggle="modal" href="#deleteTplModal{{ $v->version }}" class="btn btn-small btn-outline-danger">
							Löschen
						</a>
					</td>
            	</tr>
            	@endforeach
            	<tr>
            		<td></td>
            		<td>
						<a data-toggle="modal" href="#deleteUnneededTplsModal" class="btn btn-small btn-outline-danger">
							Alle Versionen ohne darauf basierende Dokumente löschen
						</a>
					</td>
            		<td>
            			<a data-toggle="modal" href="#deleteTplsModal" class="btn btn-small btn-outline-danger">
							Alle bis auf die neueste löschen
						</a>
            		</td>
            	</tr>
            </tbody>
            </table>
        </div>
    </div>
@endsection

@push('modals')
	@foreach($versionen as $v)
		<x-modal id="deleteTplModal{{ $v->version }}" title="Version l�schen">
            <x-slot name="body">
                <p class="text-center py-3">
					Beachten Sie: Nachdem eine Version gelöscht wurde, können auf ihr basierende Dokumente nur noch eingeschränkt
					bearbeitet werden. Sie können nach wie vor ausgedruckt werden.
				</p>
            </x-slot>

            <x-slot name="footer">
                <form action="{{ route("admin.bewerbungen.templates.delete", $v->version) }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger">Version löschen</button>
                </form>
            </x-slot>
        </x-modal>
	@endforeach

	<x-modal id="deleteUnneededTplsModal" title="Versionen l�schen">
            <x-slot name="body">
                <p class="text-center py-3">
					Sind Sie sicher, dass Sie alle Vorlagen löschen wollen, auf denen keine Dokumente basieren?
				</p>
            </x-slot>

            <x-slot name="footer">
                <form action="{{ route("admin.bewerbungen.templates.deleteUnused") }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger">Vorlagen löschen</button>
                </form>
            </x-slot>
        </x-modal>

        <x-modal id="deleteTplsModal" title="Versionen l�schen">
            <x-slot name="body">
                <p class="text-center py-3">
					Sind Sie sicher, dass Sie alle Versionen außer der neusten löschen wollen?<br/>
					Beachten Sie: Nachdem eine Version gelöscht wurde, können auf ihr basierende Dokumente nur noch eingeschränkt
					bearbeitet werden. Sie können nach wie vor ausgedruckt werden.
				</p>
            </x-slot>

            <x-slot name="footer">
                <form action="{{ route("admin.bewerbungen.templates.deleteAll") }}" method="POST">
                    @csrf
                    @method("DELETE")
                    <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger">Vorlagen löschen</button>
                </form>
            </x-slot>
        </x-modal>
@endpush
