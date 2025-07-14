<x-admin.ui.form-card 
    :title="isset($skill) ? 'Modifier une compétence' : 'Ajouter une compétence'"
    :action="isset($skill) ? route('admin.skills.update', $skill) : route('admin.skills.store')" 
    :method="isset($skill) ? 'PUT' : 'POST'" 
    :show-reset="!isset($skill)"
    submit-label="{{ isset($skill) ? 'Mettre à jour' : 'Créer' }}"
>

    <div class="row">
        @foreach (['fr', 'en', 'ar'] as $lang)
            <div class="col-md-6">
                <x-admin.ui.inputs.text 
                    id="title_{{ $lang }}" 
                    name="title[{{ $lang }}]"
                    label="Titre ({{ strtoupper($lang) }})" 
                    placeholder="Saisir le titre en {{ strtoupper($lang) }}"
                    :value="$skill->title[$lang] ?? ''" 
                    :required="$lang === 'fr'" />
            </div>

            <div class="col-md-6">
                <x-admin.ui.inputs.text 
                    id="percent_{{ $lang }}" 
                    name="percent[{{ $lang }}]"
                    label="Pourcentage ({{ strtoupper($lang) }})" 
                    placeholder="Ex: 85"
                    :value="$skill->percent[$lang] ?? ''" 
                    :required="$lang === 'fr'" />
            </div>
        @endforeach
    </div>

</x-admin.ui.form-card>
