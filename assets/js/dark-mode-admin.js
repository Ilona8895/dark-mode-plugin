(function() {
    
const checkbox = document.querySelector('input[name="dark_mode_default"]');
const colorInputs = document.querySelectorAll('input[name^="dark_mode_color_"]');
checkbox?.addEventListener('change', () => {

    colorInputs.forEach(input => {
        input.disabled = checkbox.checked;
    });

    if(checkbox.checked) {
        colorInputs.forEach(input => {
            input.value = darkModeDefaults[input.name.replace('dark_mode_color_', '')].default;
        });
    }
    
    
});

})();