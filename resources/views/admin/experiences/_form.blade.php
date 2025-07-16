<x-admin.ui.form-card 
    :title="isset($experience) ? 'Modifier une expérience' : 'Ajouter une expérience'"
    :action="isset($experience) ? route('admin.experiences.update', $experience) : route('admin.experiences.store')" 
    :method="isset($experience) ? 'PUT' : 'POST'" 
    :show-reset="!isset($experience)"
    submit-label="{{ isset($experience) ? 'Mettre à jour' : 'Créer' }}"
>

    <div class="row">
        @foreach (config('languages.available') as $lang)
            <div class="col-md-4">
                <x-admin.ui.inputs.text 
                    id="title_{{ $lang }}" 
                    name="title[{{ $lang }}]"
                    label="Titre ({{ strtoupper($lang) }})" 
                    placeholder="Saisir le titre en {{ strtoupper($lang) }}"
                    :value="$experience->title[$lang] ?? ''" 
                    :required="$lang === 'fr'" />
            </div>
         @endforeach
         @foreach (config('languages.available') as $lang)
            <div class="col-md-4">
                <x-admin.ui.inputs.tinymce 
                    id="description_{{ $lang }}" 
                    name="description[{{ $lang }}]"
                    label="Description ({{ strtoupper($lang) }})" 
                    placeholder="Saisir la description en {{ strtoupper($lang) }}"
                    :value="$experience->description[$lang] ?? ''" />
            </div>
         @endforeach
         @foreach (config('languages.available') as $lang)
            <div class="col-md-4">
                <x-admin.ui.inputs.text 
                    id="lieu_{{ $lang }}" 
                    name="lieu[{{ $lang }}]"
                    label="Lieu ({{ strtoupper($lang) }})" 
                    placeholder="Saisir le lieu en {{ strtoupper($lang) }}"
                    :value="$experience->lieu[$lang] ?? ''" 
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
                :value="$experience->datedebut ?? ''" 
                type="date"
                required />
        </div>

        {{-- Date fin --}}
        <div class="col-md-6">
            <x-admin.ui.inputs.text 
                id="datefin" 
                name="datefin"
                label="Date de fin" 
                :value="$experience->datefin ?? ''"
                type="date" />
        </div>
    </div>

</x-admin.ui.form-card>
