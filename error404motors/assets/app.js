const toggle = document.querySelector('[data-menu-toggle]');
const nav = document.querySelector('[data-main-nav]');

if (toggle && nav) {
    toggle.addEventListener('click', () => {
        nav.classList.toggle('open');
    });
}

document.querySelectorAll('[data-confirm]').forEach((button) => {
    button.addEventListener('click', (event) => {
        const message = button.getAttribute('data-confirm') || 'Continue?';

        if (!window.confirm(message)) {
            event.preventDefault();
        }
    });
});

