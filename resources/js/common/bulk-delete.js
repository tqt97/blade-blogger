export default function initBulkDelete(options = {}) {
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll(options.checkboxSelector || '.checkbox-item');
        const checkAll = document.querySelector(options.checkAllSelector || '#check-all');
        const deleteForm = document.querySelector(options.formSelector || '#bulk-delete-form');
        const deleteInput = document.querySelector(options.inputSelector || '#bulk-delete-ids');
        const deleteBtn = document.querySelector(options.buttonSelector || '#bulk-delete-button');
        const countBox = document.querySelector(options.countBoxSelector || '#selected-count');
        const countNumber = document.querySelector(options.countNumberSelector || '#selected-count-number');

        const getSelectedCheckboxes = () => Array.from(checkboxes).filter(cb => cb.checked);

        function updateUI() {
            const selected = getSelectedCheckboxes();
            console.log(selected)
            const selectedIds = selected.map(cb => cb.value);
            const hasSelection = selectedIds.length > 0;

            if (deleteInput) deleteInput.value = selectedIds.join(',');
            if (deleteBtn) deleteBtn.style.display = hasSelection ? 'inline-block' : 'none';
            if (countBox) countBox.style.display = hasSelection ? 'block' : 'none';
            if (countNumber) countNumber.textContent = selectedIds.length;
        }

        function toggleCheckAll() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkAll.checked = allChecked;
        }

        checkAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateUI();
        });
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                toggleCheckAll();
                updateUI();
            });
        });

        if (deleteForm) {
            deleteForm.addEventListener('submit', function (e) {
                if (getSelectedCheckboxes().length === 0) {
                    e.preventDefault();
                    alert(options.alertMessage || 'Vui lòng chọn ít nhất một mục để xóa.');
                }
            });
        }
    });
}
