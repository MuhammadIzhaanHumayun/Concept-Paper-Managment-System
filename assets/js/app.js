document.addEventListener('DOMContentLoaded', () => {
    const category = document.getElementById('category_id');
    const criteria = document.getElementById('categoryCriteria');
    if (!category || !criteria) return;

    const updateCriteria = () => {
        criteria.textContent = category.selectedOptions[0]?.dataset.criteria || '';
    };

    category.addEventListener('change', updateCriteria);
    updateCriteria();
});
