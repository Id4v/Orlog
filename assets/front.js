import "./front.css";

function switchTheme({html, sunIcon, moonIcon}) {
    html.classList.toggle('dark');
    const isDark = html.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    sunIcon.classList.toggle('hidden', isDark);
    moonIcon.classList.toggle('hidden', !isDark);
}
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');

    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        html.classList.add('dark');
        sunIcon.classList.add('hidden');
        moonIcon.classList.remove('hidden');
    }

    toggle.addEventListener('click', () => {
        const props = {
            html,
            sunIcon,
            moonIcon,
        }
        if (!document.startViewTransition) {
            switchTheme(props)
            return;
        }

        const themeTransition = document.startViewTransition(() => switchTheme(props));
    });
});
