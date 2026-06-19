const root = document.documentElement;
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    root.setAttribute('data-theme', savedTheme);
}
const button = document.getElementById('theme-toggle');
if (button) {
    updateIcon();
    button.addEventListener('click', () => {
        const current =
            root.getAttribute('data-theme');
        const next =
            current === 'dark'
                ? 'light'
                : 'dark';
        root.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        updateIcon();
    });
}
function updateIcon() {
    const icon =
        document.querySelector(
            '#theme-toggle i'
        );
    if (! icon) {
        return;
    }
    const dark =
        root.getAttribute('data-theme')
        === 'dark';
    icon.className = dark
        ? 'fa-solid fa-sun'
        : 'fa-solid fa-moon';
}