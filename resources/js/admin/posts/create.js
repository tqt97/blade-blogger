document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('tags');

    select.addEventListener('change', function () {
        const noneOption = Array.from(select.selectedOptions).find(opt => opt.value === '__none__');
        if (noneOption) {
            Array.from(select.options).forEach(option => {
                option.selected = false;
            });
        }
    });
});
