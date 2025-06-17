
        // Initialiser AOS
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic'
        });

        // Gestion du dropdown profil
        const userAvatar = document.getElementById('userAvatar');
        const profileDropdown = document.getElementById('profileDropdown');

        userAvatar.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        // Fermer le dropdown quand on clique ailleurs
        document.addEventListener('click', function () {
            profileDropdown.classList.remove('show');
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement;

        darkModeToggle.addEventListener('click', function () {
            if (htmlElement.getAttribute('data-bs-theme') === 'dark') {
                htmlElement.removeAttribute('data-bs-theme');
                localStorage.setItem('theme', 'light');
            } else {
                htmlElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Vérifier le thème au chargement
        if (localStorage.getItem('theme') === 'dark' ||
            (window.matchMedia('(prefers-color-scheme: dark)').matches && !localStorage.getItem('theme'))) {
            htmlElement.setAttribute('data-bs-theme', 'dark');
        }

        // Animation au scroll
        const cards = document.querySelectorAll('.card-pro');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });
    