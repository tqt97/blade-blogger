import initBulkDelete from '../../common/bulk-delete.js';

initBulkDelete();

document.addEventListener('DOMContentLoaded', function () {
    const clearBtn = document.getElementById('clear-filters-button');

    if (!clearBtn) return;

    const urlParams = new URLSearchParams(window.location.search);
    const hasFilter = Array.from(urlParams.keys()).length > 0;

    if (hasFilter) {
        clearBtn.classList.remove('hidden');
    }
});
