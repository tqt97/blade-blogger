export default function initializeTagNoneOptionHandler(elementId = 'tags') {
    const select = document.getElementById(elementId);
    if (!select) {
        console.warn(`Tag select element with ID '${elementId}' not found.`);
        return;
    }

    select.addEventListener('change', function () {
        // Check if the '__none__' option is selected
        const noneOptionSelected = Array.from(this.selectedOptions).some(opt => opt.value === '__none__');

        if (noneOptionSelected) {
            // If '__none__' is selected, deselect all options (including '__none__' itself, effectively clearing selection)
            Array.from(this.options).forEach(option => {
                option.selected = false;
            });
            // Optionally, to ensure '__none__' is not submitted, you might want to explicitly set its value to empty or handle server-side.
            // The current behavior (deselecting all) means an empty array of tags will be submitted.
        }
    });
}
