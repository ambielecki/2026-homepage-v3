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
