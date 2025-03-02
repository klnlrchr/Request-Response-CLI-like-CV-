            document.addEventListener('DOMContentLoaded', () => {
                const themeToggle = document.getElementById('theme-toggle');
                const body = document.body;

                // Load selected theme
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme) {
                    body.className = savedTheme;
                    themeToggle.checked = savedTheme === 'dark-mode';
                }

                // Switch mode
                themeToggle.addEventListener('change', () => {
                    if (themeToggle.checked) {
                        body.className = 'dark-mode';
                        localStorage.setItem('theme', 'dark-mode'); // Save setting
                    } else {
                        body.className = 'light-mode';
                        localStorage.setItem('theme', 'light-mode'); // Save setting
                    }
                });
            });
