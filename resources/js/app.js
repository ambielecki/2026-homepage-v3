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

const analyticsConsentElement = document.querySelector('[data-analytics-consent]');

if (analyticsConsentElement instanceof HTMLElement) {
    const measurementId = analyticsConsentElement.dataset.measurementId;
    const acceptButton = analyticsConsentElement.querySelector('[data-analytics-accept]');
    const rejectButton = analyticsConsentElement.querySelector('[data-analytics-reject]');
    const settingsButtons = document.querySelectorAll('[data-analytics-settings]');
    const storageKey = 'andrewbielecki_analytics_consent';
    const consentLifetime = 183 * 24 * 60 * 60 * 1000;
    let analyticsLoaded = false;

    const readConsent = () => {
        try {
            const storedConsent = JSON.parse(window.localStorage.getItem(storageKey));

            if (
                !['accepted', 'rejected'].includes(storedConsent?.choice)
                || !Number.isFinite(storedConsent?.expires_at)
                || storedConsent.expires_at <= Date.now()
            ) {
                try {
                    window.localStorage.removeItem(storageKey);
                } catch {
                    // The consent prompt remains available when storage is blocked.
                }

                return null;
            }

            return storedConsent.choice;
        } catch {
            return null;
        }
    };

    const storeConsent = (choice) => {
        try {
            window.localStorage.setItem(storageKey, JSON.stringify({
                choice,
                expires_at: Date.now() + consentLifetime,
            }));
        } catch {
            // The current choice still applies for this page load.
        }
    };

    const showConsent = () => {
        analyticsConsentElement.classList.remove('hidden');
    };

    const hideConsent = () => {
        analyticsConsentElement.classList.add('hidden');
    };

    const initializeGtag = () => {
        window.dataLayer = window.dataLayer ?? [];
        window.gtag = window.gtag ?? function gtag() {
            window.dataLayer.push(arguments);
        };
    };

    const consentDefaults = {
        analytics_storage: 'denied',
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied',
    };

    const loadAnalytics = () => {
        if (!measurementId || analyticsLoaded) {
            return;
        }

        analyticsLoaded = true;
        initializeGtag();
        window.gtag('consent', 'default', consentDefaults);
        window.gtag('consent', 'update', {
            ...consentDefaults,
            analytics_storage: 'granted',
        });
        window.gtag('set', 'allow_google_signals', false);
        window.gtag('set', 'allow_ad_personalization_signals', false);
        window.gtag('js', new Date());
        window.gtag('config', measurementId);

        const script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(measurementId)}`;
        document.head.append(script);
    };

    const deleteAnalyticsCookies = () => {
        const domainParts = window.location.hostname.split('.');
        const domains = ['', window.location.hostname];

        if (domainParts.length >= 2) {
            domains.push(`.${domainParts.slice(-2).join('.')}`);
        }

        document.cookie
            .split(';')
            .map((cookie) => cookie.split('=')[0].trim())
            .filter((name) => name.startsWith('_ga'))
            .forEach((name) => {
                domains.forEach((domain) => {
                    const domainAttribute = domain ? `;domain=${domain}` : '';

                    document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/${domainAttribute};SameSite=Lax`;
                });
            });
    };

    const acceptAnalytics = () => {
        storeConsent('accepted');
        hideConsent();
        loadAnalytics();
    };

    const rejectAnalytics = () => {
        storeConsent('rejected');
        hideConsent();

        if (!analyticsLoaded) {
            return;
        }

        initializeGtag();
        window.gtag('consent', 'update', consentDefaults);
        deleteAnalyticsCookies();
        window.location.reload();
    };

    if (acceptButton instanceof HTMLButtonElement) {
        acceptButton.addEventListener('click', acceptAnalytics);
    }

    if (rejectButton instanceof HTMLButtonElement) {
        rejectButton.addEventListener('click', rejectAnalytics);
    }

    settingsButtons.forEach((button) => {
        button.addEventListener('click', showConsent);
    });

    const savedConsent = readConsent();

    if (savedConsent === 'accepted') {
        loadAnalytics();
    } else if (savedConsent === null) {
        showConsent();
    }
}
