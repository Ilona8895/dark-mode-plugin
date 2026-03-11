<?php

/*            
Plugin Name: Dark Mode   
Description: Dark Mode Plugin with custom colors for Roslinopedia theme
Version: 1.0.0
Author: Ilona Melcher
Author URI: https://github.com/Ilona8895
*/

if(!defined('ABSPATH')) exit;

class DarkModePlugin{

    private $color_slots = [
        'primary' => ['label' => 'Primary', 'default' => '#1f4d3a;'],
        'secondary' => ['label' => 'Secondary', 'default' => '#2f6b4f'],
        'accent'  => ['label' => 'Accent', 'default' => '#c8a96a'],
        'bg'      => ['label' => 'Background', 'default' => '#0f1110'],
        'bg-alt'  => ['label' => 'Background Alt', 'default' => '#161a17'],
        'text'    => ['label' => 'Text', 'default' => '#e8efe9'],
        'text-muted' => ['label' => 'Text Muted', 'default' => '#9aa39c'],  
        'link'    => ['label' => 'Link', 'default' => '#6fbf8a'],
        'border'  => ['label' => 'Border', 'default' => '#2a2f2b'],

    ];

    public function __construct(){

        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminAssets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueFrontendAssets'));
        add_action('wp_footer', array($this, 'outputDarkModeToggle'));
        add_action('wp_head', array($this, 'outputDarkModeStyles'), 100);
        add_action('admin_menu', array($this, 'adminMenu'));        
        add_action('admin_init', array($this, 'settings'));
    }

    function enqueueFrontendAssets() {
        wp_enqueue_script(
            'dark-mode-frontend',
            plugin_dir_url(__FILE__) . 'assets/js/dark-mode-frontend.js',
            array(),
            '1.0',
            true
        );

        wp_enqueue_style(
            'dark-mode-frontend',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            array(),
            '1.0',
            'all'
        );

        $colors = array();
        foreach ($this->color_slots as $key => $value) {
            $option = get_option('dark_mode_color_' . $key, $value['default']);
            $colors[$key] = !empty($option) ? $option : $value['default'];
        }           

        wp_localize_script('dark-mode-frontend', 'darkModeColors', array(
            'colors' => $colors,
            'position' => get_option('dark_mode_position', 'bottom-right'),
            'defaultOn' => get_option('dark_mode_default', '1') === '1'
        ));
    }


    function outputDarkModeToggle() {
        $position = get_option('dark_mode_position', 'bottom-right');
        ?>
        <button type="button" class="dark-mode-toggle dark-mode-toggle--<?php echo esc_attr($position); ?>" aria-label="Przełącz tryb ciemny" title="Tryb ciemny">
            <i class="fas fa-moon dark-mode-toggle__icon"></i>
        </button>
        <?php
    }
    
    function outputDarkModeStyles() {
        $colors = array();
        foreach ($this->color_slots as $key => $value) {
            $option = get_option('dark_mode_color_' . $key, $value['default']);
            $colors[$key] = !empty($option) ? $option : $value['default'];
        }
        ?>
        <style id="dark-mode-custom-colors">
            body.dark-mode-active {
                --color-primary: <?php echo esc_attr($colors['primary']); ?>;
                --color-secondary: <?php echo esc_attr($colors['secondary']); ?>;
                --color-accent: <?php echo esc_attr($colors['accent']); ?>;
                --color-background: <?php echo esc_attr($colors['bg']); ?>;
                --color-background-alt: <?php echo esc_attr($colors['bg-alt']); ?>;
                --color-text: <?php echo esc_attr($colors['text']); ?>;
                --color-text-muted: <?php echo esc_attr($colors['text-muted']); ?>;
                --color-link: <?php echo esc_attr($colors['link']); ?>;
                --color-border: <?php echo esc_attr($colors['border']); ?>;
            }
        </style>
        <?php
    }

    function enqueueAdminAssets($hook) {
        if ($hook !== 'toplevel_page_dark-mode-menu') {
            return;
        }
        
        wp_enqueue_script(
            'dark-mode-admin',
            plugin_dir_url(__FILE__) . 'assets/js/dark-mode-admin.js',
            array(),
            '1.0',
            true
        );
        
        wp_localize_script('dark-mode-admin', 'darkModeDefaults', $this->color_slots);
    }

    function settings() {
        add_settings_section('dark_mode_first_section', null, null, 'dark-mode-settings-page');

        add_settings_field('dark_mode_default', 'Default Dark Mode', array($this, 'defaultHTML'), 'dark-mode-settings-page', 'dark_mode_first_section');
        register_setting('dark_mode_plugin', 'dark_mode_default', array('sanitize_callback' => array($this, 'sanitizeDefault'), 'default' => '1'));

        add_settings_field('dark_mode_position', 'Dark Mode Position Icon', array($this, 'positionHTML'), 'dark-mode-settings-page', 'dark_mode_first_section');
        register_setting('dark_mode_plugin', 'dark_mode_position', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'bottom-right'));

        foreach ($this->color_slots as $key => $value) {
            $option_name = 'dark_mode_color_' . $key;
            
            add_settings_field(
                $option_name,
                $value['label'],
                array($this, 'colorFieldHTML'),
                'dark-mode-settings-page',
                'dark_mode_first_section',
                array('key' => $key, 'default' => $value['default'])
            );
            
            register_setting('dark_mode_plugin', $option_name, array(
                'sanitize_callback' => 'sanitize_hex_color',
                'default' => $value['default']
            ));
        }
    }
    
    function sanitizeDefault($input) {
        return ($input === '1') ? '1' : '0';
    }

    function positionHTML() { ?>
        <select name="dark_mode_position">
            <option value="bottom-left" <?php selected(get_option('dark_mode_position'), 'bottom-left'); ?>>Bottom Left</option>
            <option value="bottom-right" <?php selected(get_option('dark_mode_position'), 'bottom-right'); ?>>Bottom Right</option>
        </select>
    <?php }

    function colorFieldHTML($args) {
        $key = $args['key'];
        $default = $args['default'];
        $option_name = 'dark_mode_color_' . $key;
        $value = get_option($option_name, $default);
        $value = !empty($value) ? $value : $default;
        ?>
        <input type="hidden" name="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($default); ?>">
        <input type="color" 
            name="<?php echo esc_attr($option_name); ?>" 
            value="<?php echo esc_attr($value); ?>" 
            <?php disabled(get_option('dark_mode_default'), '1'); ?> />
        <?php
    }

    function defaultHTML() { ?>
            <input type="checkbox" name="dark_mode_default" value="1" <?php checked(get_option('dark_mode_default'), '1'); ?>/>
      <?php }

    public function adminMenu(){
        add_menu_page( 'Dark Mode Settings', 'Dark Mode', 'manage_options', 'dark-mode-menu', array($this, 'darkModeSettingsPage'), 'dashicons-admin-customizer');
    }



    public function darkModeSettingsPage(){ ?>      
                <div class="wrap">
                    <h1>Dark Mode</h1>
                    <p>This is the dark mode settings page. You can customize the dark mode settings here. Stay checked to use the default colors or set your own colors for each color slot.</p>
                    <?php settings_errors(); ?>
                    <form action="options.php" method="POST">
                    <?php
                        settings_fields('dark_mode_plugin');
                        do_settings_sections('dark-mode-settings-page');
                        submit_button();
                    ?>
                    </form>
                </div>
    
    <?php
    }

}


$darkModePlugin =new DarkModePlugin();

