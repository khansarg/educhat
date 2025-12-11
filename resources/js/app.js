document.addEventListener('DOMContentLoaded', () => {
    const html   = document.documentElement;
    const toggle = document.getElementById('darkModeToggle');

    // --- DARK MODE EXISTING CODE ---
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') {
        html.classList.add('dark');
    } else if (saved === 'light') {
        html.classList.remove('dark');
    }
    if (toggle) {
        toggle.addEventListener('click', () => {
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    }

    // --- NEW: TOGGLE RIGHT SIDEBAR DI CHAT ---
    const infoToggleBtn = document.getElementById('infoToggleBtn');
    const infoPanel     = document.getElementById('cloInfoPanel');

    if (infoToggleBtn && infoPanel) {
        infoToggleBtn.addEventListener('click', () => {
            infoPanel.classList.toggle('hidden');
        });
    }
});
