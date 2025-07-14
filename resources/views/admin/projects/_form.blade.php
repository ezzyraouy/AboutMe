<x-admin.ui.form-card :title="isset($project) ? 'Modifier un project' : 'Ajouter un project'" 
    :action="isset($project) ? route('admin.projects.update', $project) : route('admin.projects.store')" 
    :method="isset($project) ? 'PUT' : 'POST'" enctype="multipart/form-data"
    :show-reset="!isset($project)" 
    submit-label="{{ isset($project) ? 'Mettre à jour' : 'Créer' }}">

    <div class="row">
        @foreach (['fr', 'en', 'ar'] as $lang)
            <div class="col-md-12">
                <x-admin.ui.inputs.text 
                    id="title_{{ $lang }}" 
                    name="title[{{ $lang }}]"
                    label="Titre ({{ strtoupper($lang) }})" 
                    placeholder="Saisir le titre en {{ strtoupper($lang) }}"
                    :value="$project->title[$lang] ?? ''" 
                    :required="$lang === 'fr'" />
            </div>

            <div class="col-md-12">
                <x-admin.ui.inputs.tinymce 
                    id="description_{{ $lang }}" 
                    name="description[{{ $lang }}]"
                    label="Description ({{ strtoupper($lang) }})" 
                    placeholder="Saisir la description en {{ strtoupper($lang) }}"
                    :value="$project->description[$lang] ?? ''" 
                    :required="$lang === 'fr'" />
            </div>
        @endforeach
    </div>

    <div class="row">
        {{-- Link --}}
        <div class="col-md-6">
            <x-admin.ui.inputs.text 
                id="link" 
                name="link"
                label="Lien du projet" 
                placeholder="https://example.com"
                :value="$project->link ?? ''" />
        </div>

        {{-- GitHub Link --}}
        <div class="col-md-6">
            <x-admin.ui.inputs.text 
                id="github_link" 
                name="github_link"
                label="Lien GitHub" 
                placeholder="https://github.com/..."
                :value="$project->github_link ?? ''" />
        </div>

        {{-- Video Link --}}
        <div class="col-md-12">
            <x-admin.ui.inputs.text 
                id="video" 
                name="video"
                label="Lien vidéo" 
                placeholder="https://youtube.com/..."
                :value="$project->video ?? ''" />
        </div>
    </div>

    <div class="row">
        {{-- Image principale --}}
        <div class="col-12 mb-4">
            <x-admin.ui.inputs.image-upload :value="$project->image ?? null" :projectId="$project->id ?? null" />
        </div>

        {{-- Fichiers --}}
        <div class="col-12">
            <x-admin.ui.inputs.file-multiple label="Fichiers (images, vidéos, PDF)" />
        </div>
    </div>

    {{-- Resources Preview --}}
    @if (isset($project) && $project->resources->count())
        <div class="row" id="resources-container">
            @foreach ($project->resources as $resource)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4 resource-item" data-id="{{ $resource->id }}">
                    <div class="card h-100 border-0">
                        <div class="position-relative">
                            @if (Str::startsWith($resource->mime_type, 'image/'))
                                <img class="card-img-top img-fluid"
                                    src="{{ asset('storage/' . $resource->file_path) }}" alt="Image">
                            @elseif (Str::startsWith($resource->mime_type, 'video/'))
                                <video controls class="w-100" style="height: 200px; object-fit: cover;">
                                    <source src="{{ asset('storage/' . $resource->file_path) }}"
                                        type="{{ $resource->mime_type }}">
                                </video>
                            @elseif ($resource->mime_type === 'application/pdf')
                                <div class="d-flex align-items-center justify-content-center"
                                    style="height: 200px; background: #f0f0f0;">
                                    <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
                                        class="text-decoration-none">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i><br>Voir PDF
                                    </a>
                                </div>
                            @endif

                            <div class="position-absolute top-0 end-0 p-2 bg-white rounded-bottom-start"
                                style="opacity: 0.9;">
                                <button type="button" onclick="deleteResource({{ $resource->id }}, this)"
                                    class="btn btn-sm btn-link text-danger p-0 m-0" title="Supprimer">
                                    <i class="bi bi-x-circle-fill fs-5"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body text-center p-2">
                            <small class="text-muted">{{ strtoupper($resource->file_type) }}</small>
                            @if ($resource->is_main)
                                <div class="badge bg-primary mt-1">Principal</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @push('scripts')
        <script>
            // Reusable toast function
            function showToast(icon, title, isSuccess = true) {
                const config = {
                    toast: true,
                    position: 'top-end',
                    icon: icon,
                    title: title,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                };

                if (isSuccess) {
                    Object.assign(config, {
                        iconColor: '#28a745',
                        background: '#d4edda',
                        color: '#155724'
                    });
                } else {
                    Object.assign(config, {
                        iconColor: '#dc3545',
                        background: '#f8d7da',
                        color: '#721c24'
                    });
                }

                Swal.fire(config);
            }

            // Reusable delete handler
            async function handleDelete(url, button, successCallback) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) return;

                const originalHtml = button.innerHTML;
                button.innerHTML = '<i class="bi bi-arrow-clockwise fs-5"></i>';
                button.disabled = true;

                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Erreur réseau');

                    const data = await response.json();

                    if (!data.success) throw new Error(data.message || 'Erreur lors de la suppression');

                    if (typeof successCallback === 'function') {
                        successCallback(data);
                    }

                    showToast('success', data.message || 'Supprimé avec succès');
                } catch (error) {
                    console.error(error);
                    showToast('error', error.message, false);
                } finally {
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                }
            }

            function deleteMainImage(projectId, button) {
                const url = "{{ route('admin.projects.removeImage', ':id') }}".replace(':id', projectId);
                handleDelete(url, button, (data) => {
                    button.closest('.position-relative').remove();
                });
            }

            function deleteResource(id, button) {
                const url = "{{ route('admin.resources.destroy', ':id') }}".replace(':id', id);
                handleDelete(url, button, (data) => {
                    const resourceItem = document.querySelector(`.resource-item[data-id="${id}"]`);
                    if (resourceItem) resourceItem.remove();
                });
            }
        </script>
    @endpush
</x-admin.ui.form-card>
