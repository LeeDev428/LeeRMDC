<x-app-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-xl transform hover:scale-105 transition duration-300 ease-in-out">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Theme Settings</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Switch between light and dark mode.</p>

            <div class="mt-4">
                <button id="theme-toggle" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none transition duration-300">
                    Change Theme
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleButton = document.getElementById('theme-toggle');

            if (themeToggleButton) {
                themeToggleButton.addEventListener('click', function() {
                    const html = document.documentElement;
                    html.classList.toggle('dark');
                    document.body.classList.toggle('dark'); // Ensure body class is also toggled
                    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
                });
            }

            // Apply the saved theme on page load
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.classList.add(savedTheme);
                document.body.classList.add(savedTheme); // Ensure body class is also added
            }
        });
    </script>
</x-app-layout>