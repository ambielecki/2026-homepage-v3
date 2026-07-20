document.querySelectorAll('[data-file-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const fileName = input.files?.[0]?.name ?? 'No file selected';
        const fileNameElement = input
            .closest('fieldset')
            ?.querySelector('[data-file-name]');

        if (fileNameElement instanceof HTMLElement) {
            fileNameElement.textContent = fileName;
        }
    });
});

const richTextareas = document.querySelectorAll('textarea[data-rich-text]');

if (richTextareas.length > 0) {
    (async () => {
        const { default: tinymce } = await import('tinymce/tinymce');

        await Promise.all([
            import('tinymce/icons/default'),
            import('tinymce/themes/silver'),
            import('tinymce/models/dom'),
            import('tinymce/plugins/link'),
            import('tinymce/plugins/lists'),
            import('tinymce/skins/ui/oxide/skin.css'),
            import('tinymce/skins/ui/oxide/content.css'),
            import('tinymce/skins/content/default/content.css'),
        ]);

        tinymce.init({
            selector: 'textarea[data-rich-text]',
            license_key: 'gpl',
            promotion: false,
            branding: false,
            menubar: false,
            plugins: 'link lists',
            toolbar: 'bold italic | bullist numlist | link | removeformat',
            skin: false,
            content_css: false,
        });

        document.querySelectorAll('form').forEach((form) => {
            form.addEventListener('submit', () => {
                tinymce.triggerSave();
            });
        });
    })();
}

const imagePickerModal = document.querySelector('[data-image-picker-modal]');

if (imagePickerModal instanceof HTMLDialogElement) {
    const resultsElement = imagePickerModal.querySelector('[data-image-picker-results]');
    const headerFilter = imagePickerModal.querySelector('[data-image-picker-header-filter]');
    const previousButton = imagePickerModal.querySelector('[data-image-picker-prev]');
    const nextButton = imagePickerModal.querySelector('[data-image-picker-next]');
    const clearButton = imagePickerModal.querySelector('[data-image-picker-clear]');
    const pickerUrl = imagePickerModal.dataset.url;
    const pickerState = {
        targetInput: null,
        targetLabel: null,
        placeholder: 'Use placeholder',
        page: 1,
        lastPage: 1,
    };

    const escapeHtml = (value) => {
        const span = document.createElement('span');
        span.textContent = value ?? '';

        return span.innerHTML;
    };

    const updatePagination = () => {
        if (previousButton instanceof HTMLButtonElement) {
            previousButton.disabled = pickerState.page <= 1;
        }

        if (nextButton instanceof HTMLButtonElement) {
            nextButton.disabled = pickerState.page >= pickerState.lastPage;
        }
    };

    const selectImage = (image) => {
        if (pickerState.targetInput instanceof HTMLInputElement) {
            pickerState.targetInput.value = image.id;
        }

        if (pickerState.targetLabel instanceof HTMLElement) {
            pickerState.targetLabel.textContent = image.alt_text;
        }

        imagePickerModal.close();
    };

    const renderImages = (images) => {
        if (!(resultsElement instanceof HTMLElement)) {
            return;
        }

        if (images.length === 0) {
            resultsElement.innerHTML = '<div class="col-span-full text-sm text-base-content/70">No images match this filter.</div>';

            return;
        }

        resultsElement.innerHTML = images.map((image) => `
            <article class="card card-border bg-base-100">
                <figure class="aspect-video bg-base-200">
                    <img class="h-full w-full object-cover" src="${escapeHtml(image.thumbnail_url)}" alt="${escapeHtml(image.alt_text)}">
                </figure>
                <div class="card-body gap-3">
                    <h3 class="card-title text-sm">${escapeHtml(image.alt_text)}</h3>
                    <p class="text-xs leading-5 text-base-content/70">${escapeHtml(image.description ?? 'No description provided.')}</p>
                    <div class="card-actions justify-between">
                        <span class="badge ${image.is_header ? 'badge-success' : ''}">${image.is_header ? 'Header' : 'Image'}</span>
                        <button class="btn btn-sm" type="button" data-image-picker-select="${image.id}">Select</button>
                    </div>
                </div>
            </article>
        `).join('');

        resultsElement.querySelectorAll('[data-image-picker-select]').forEach((button) => {
            button.addEventListener('click', () => {
                const image = images.find((candidate) => String(candidate.id) === button.dataset.imagePickerSelect);

                if (image) {
                    selectImage(image);
                }
            });
        });
    };

    const loadImages = async (page = 1) => {
        if (!(resultsElement instanceof HTMLElement) || !pickerUrl) {
            return;
        }

        resultsElement.innerHTML = '<div class="col-span-full text-sm text-base-content/70">Loading images.</div>';

        const url = new URL(pickerUrl, window.location.origin);
        url.searchParams.set('page', page);

        if (headerFilter instanceof HTMLInputElement && headerFilter.checked) {
            url.searchParams.set('header_only', '1');
        }

        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            resultsElement.innerHTML = '<div class="col-span-full text-sm text-error">Images could not be loaded.</div>';

            return;
        }

        const payload = await response.json();
        pickerState.page = payload.pagination.current_page;
        pickerState.lastPage = payload.pagination.last_page;
        renderImages(payload.images);
        updatePagination();
    };

    document.querySelectorAll('[data-image-picker-open]').forEach((button) => {
        button.addEventListener('click', () => {
            pickerState.targetInput = document.getElementById(button.dataset.targetInput);
            pickerState.targetLabel = document.getElementById(button.dataset.targetLabel);
            pickerState.placeholder = button.dataset.placeholder ?? 'Use placeholder';

            if (headerFilter instanceof HTMLInputElement) {
                headerFilter.checked = button.dataset.headerOnly === '1';
            }

            imagePickerModal.showModal();
            loadImages(1);
        });
    });

    if (headerFilter instanceof HTMLInputElement) {
        headerFilter.addEventListener('change', () => loadImages(1));
    }

    if (previousButton instanceof HTMLButtonElement) {
        previousButton.addEventListener('click', () => loadImages(Math.max(1, pickerState.page - 1)));
    }

    if (nextButton instanceof HTMLButtonElement) {
        nextButton.addEventListener('click', () => loadImages(Math.min(pickerState.lastPage, pickerState.page + 1)));
    }

    if (clearButton instanceof HTMLButtonElement) {
        clearButton.addEventListener('click', () => {
            if (pickerState.targetInput instanceof HTMLInputElement) {
                pickerState.targetInput.value = '';
            }

            if (pickerState.targetLabel instanceof HTMLElement) {
                pickerState.targetLabel.textContent = pickerState.placeholder;
            }

            imagePickerModal.close();
        });
    }

    updatePagination();
}
