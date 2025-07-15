<x-admin.ui.form-card
    :title="isset($education) ? 'Modifier une éducation' : 'Ajouter une éducation'"
    :action="isset($education) ? route('admin.educations.update', $education) : route('admin.educations.store')"
    :method="isset($education) ? 'PUT' : 'POST'"
    :show-reset="!isset($education)"
    submit-label="{{ isset($education) ? 'Mettre à jour' : 'Créer' }}">

    <div class="row">
        @foreach (['fr', 'en', 'ar'] as $lang)
        <div class="col-md-4">
            <x-admin.ui.inputs.text
                id="title_{{ $lang }}"
                name="title[{{ $lang }}]"
                label="Titre ({{ strtoupper($lang) }})"
                placeholder="Saisir le titre en {{ strtoupper($lang) }}"
                :value="$education->title[$lang] ?? ''"
                :required="$lang === 'fr'" />
        </div>
        @endforeach
        @foreach (['fr', 'en', 'ar'] as $lang)
        <div class="col-md-4">
            <x-admin.ui.inputs.text
                id="lieu_{{ $lang }}"
                name="lieu[{{ $lang }}]"
                label="Lieu ({{ strtoupper($lang) }})"
                placeholder="Saisir le lieu en {{ strtoupper($lang) }}"
                :value="$education->lieu[$lang] ?? ''"
                :required="$lang === 'fr'" />
        </div>
        @endforeach
    </div>

    <div class="row">
        {{-- Date début --}}
        <div class="col-md-6">
            <x-admin.ui.inputs.text
                id="datedebut"
                name="datedebut"
                label="Date de début"
                type="date"
                :value="$education->datedebut ?? ''"
                required />
        </div>

        {{-- Date fin --}}
        <div class="col-md-6">
            <x-admin.ui.inputs.text
                id="datefin"
                name="datefin"
                label="Date de fin"
                type="date"
                :value="$education->datefin ?? ''" />
        </div>
    </div>

</x-admin.ui.form-card>