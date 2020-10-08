@push('modals')
    <x-modal id="deleteCompanyModal" title="Firma löschen">
        <x-slot name="body">
            <p class="text-center py-3">Möchten Sie diese Firma wirklich löschen?</p>
        </x-slot>

        <x-slot name="footer">
            <form action="{{ route("bewerbungen.companies.destroy", $company) }}" method="POST">
                @csrf
                @method("DELETE")
                <button type="button" class="btn btn-link text-secondary" data-dismiss="modal">Abbrechen</button>
                <button type="submit" class="btn btn-danger">Firma löschen</button>
            </form>
        </x-slot>
    </x-modal>
@endpush
