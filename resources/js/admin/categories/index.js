import initBulkDelete from '../../common/bulk-delete.js';

initBulkDelete({
    // checkboxSelector: '.checkbox-item',
    // checkAllSelector: '#check-all',
    // formSelector: '#bulk-delete-form',
    // inputSelector: '#bulk-delete-ids',
    // buttonSelector: '#bulk-delete-button',
    // countBoxSelector: '#selected-count',
    // countNumberSelector: '#selected-count-number',
    // alertMessage: 'Vui lòng chọn ít nhất một mục để xóa.'
});

document.addEventListener('DOMContentLoaded', function () {
    const clearBtn = document.getElementById('clear-filters-button');

    if (!clearBtn) return;

    const urlParams = new URLSearchParams(window.location.search);
    const hasFilter = Array.from(urlParams.keys()).length > 0;

    if (hasFilter) {
        clearBtn.classList.remove('hidden');
    }
});
