const savedTheme = localStorage.getItem('theme');

if (savedTheme === 'dark') {
    document.documentElement.classList.add('dark');
} else if (savedTheme === 'light') {
    document.documentElement.classList.remove('dark');
}

window.toggleTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};

import "./officer-scanner";
