document.addEventListener('DOMContentLoaded', () => {
    document
        .querySelectorAll('.reply-toggle')
        .forEach((button) => {
            button.addEventListener('click', () => {
                const card = button.closest('.card');
                const form = card.querySelector(
                    '.reply-form'
                );
                if (! form) {
                    return;
                }
                form.style.display =
                    form.style.display === 'block'
                        ? 'none'
                        : 'block';
            });
        });
});