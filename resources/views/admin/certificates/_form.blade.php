<x-admin.ui.form-card 
    :title="isset($certificate) ? 'Modifier un certificat' : 'Ajouter un certificat'"
    :action="isset($certificate) ? route('admin.certificates.update', $certificate) : route('admin.certificates.store')" 
    :method="isset($certificate) ? 'PUT' : 'POST'" 
    enctype="multipart/form-data"
    :show-reset="!isset($certificate)"
    submit-label="{{ isset($certificate) ? 'Mettre à jour' : 'Créer' }}"
>

    <div class="row">
        @foreach (['fr', 'en', 'ar'] as $lang)
            <div class="col-md-12">
                <x-admin.ui.inputs.text 
                    id="title_{{ $lang }}" 
                    name="title[{{ $lang }}]"
                    label="Titre ({{ strtoupper($lang) }})" 
                    placeholder="Saisir le titre en {{ strtoupper($lang) }}"
                    :value="$certificate->title[$lang] ?? ''" 
                    :required="$lang === 'fr'" />
            </div>

            <div class="col-md-12">
                <x-admin.ui.inputs.tinymce 
                    id="description_{{ $lang }}" 
                    name="description[{{ $lang }}]"
                    label="Description ({{ strtoupper($lang) }})" 
                    placeholder="Saisir la description en {{ strtoupper($lang) }}"
                    :value="$certificate->description[$lang] ?? ''" />
            </div>
        @endforeach

        {{-- Link --}}
        <div class="col-md-12">
            <x-admin.ui.inputs.text 
                id="link" 
                name="link"
                label="Lien du certificat" 
                placeholder="https://example.com"
                :value="$certificate->link ?? ''" />
        </div>

        {{-- Image --}}
        <div class="col-12 mb-4">
            <x-admin.ui.inputs.image-upload :value="$certificate->image ?? null" :certificateId="$certificate->id ?? null" />
        </div>
    </div>

</x-admin.ui.form-card>
