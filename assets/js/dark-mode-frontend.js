(function() {
    const STORAGE_KEY = 'roslinopedia-dark-mode';
    
    function init() {
        const toggle = document.querySelector('.dark-mode-toggle');
        if (!toggle) return;
        
        const defaultOn = typeof darkModeColors !== 'undefined' && darkModeColors.defaultOn;
        const saved = localStorage.getItem(STORAGE_KEY);
        const isDark = saved !== null ? saved === '1' : defaultOn;
        
        if (isDark) {
            document.body.classList.add('dark-mode-active');
            toggle.querySelector('.dark-mode-toggle__icon')?.classList.replace('fa-moon', 'fa-sun');
        }
        
        toggle.addEventListener('click', () => {
            const active = document.body.classList.toggle('dark-mode-active');
            localStorage.setItem(STORAGE_KEY, active ? '1' : '0');
            const icon = toggle.querySelector('.dark-mode-toggle__icon');
            if (icon) {
                icon.classList.toggle('fa-moon', !active);
                icon.classList.toggle('fa-sun', active);
            }
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();