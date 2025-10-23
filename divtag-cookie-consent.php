<?php
/**
 * Plugin Name: Divtag Cookie Consent
 * Plugin URI: https://github.com/divtag-nl/wp-plugin-divtag-cookie-consent
 * Description: Cookie Consent by Divtag
 * Version: 2.1.0
 * Author: Divtag
 * Author URI: https://divtag.nl/
 **/

$GLOBALS['package_version'] = '2.1.0';

/**
 * WP Plugin Update checker
 */

require 'plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
  'https://github.com/divtag-nl/wp-plugin-divtag-cookie-consent',
  __FILE__, //Full path to the main plugin file or functions.php.
  'wp-plugin-divtag-cookie-consent'
);

// Set the branch that contains the stable release
$myUpdateChecker->setBranch('master');


/**
 * Load scripts for the client
 */

function load_scripts()
{
  wp_enqueue_script('cookie-consent-js', plugin_dir_url(__FILE__) . 'dist/js/cookie-consent-client.js', [], $GLOBALS['package_version'], true);
  wp_register_style('cookie-consent-css', plugin_dir_url(__FILE__) . 'dist/css/cookie-consent-client.css?ver=' . $GLOBALS['package_version'], false, null);

  wp_localize_script('cookie-consent-js', 'cookie_consent_settings',
    [
      'options'     => get_option('divtag_cookie_consent_option_name'),
      'admin_email' => get_option('admin_email'),
    ]
  );

  wp_enqueue_style('cookie-consent-css');
}

add_action('wp_enqueue_scripts', 'load_scripts');


/**
 * Load scripts for the admin
 */

function load_admin_scripts()
{
  wp_enqueue_script('cookie-consent-js', plugin_dir_url(__FILE__) . 'dist/js/cookie-consent-admin.js?ver=' . $GLOBALS['package_version'], [], null);
  wp_register_style('cookie-consent-css', plugin_dir_url(__FILE__) . 'dist/css/cookie-consent-admin.css?ver=' . $GLOBALS['package_version'], false, null);

  wp_enqueue_style('cookie-consent-css');
}

add_action('admin_enqueue_scripts', 'load_admin_scripts');


/**
 * WordPress Option Page including custom Cookie Consent settings
 */
class DivtagCookieConsent
{
  private $divtag_cookie_consent_options;

  public function __construct()
  {
    add_action('admin_menu', [$this, 'divtag_cookie_consent_add_plugin_page']);
    add_action('admin_init', [$this, 'divtag_cookie_consent_page_init']);
  }

  public function divtag_cookie_consent_add_plugin_page()
  {
    // Cookie icon in Base64 format
    $icon_base64 = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGFyaWEtaGlkZGVuPSJ0cnVlIiByb2xlPSJpbWciIHdpZHRoPSIxZW0iIGhlaWdodD0iMWVtIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWlkWU1pZCBtZWV0IiB2aWV3Qm94PSIwIDAgMjQgMjQiPjxwYXRoIGZpbGw9ImN1cnJlbnRDb2xvciIgZD0iTTIxLjk1IDEwLjk5Yy0xLjc5LS4wMy0zLjctMS45NS0yLjY4LTQuMjJjLTIuOTggMS01Ljc3LTEuNTktNS4xOS00LjU2QzYuOTUuNzEgMiA2LjU4IDIgMTJjMCA1LjUyIDQuNDggMTAgMTAgMTBjNS44OSAwIDEwLjU0LTUuMDggOS45NS0xMS4wMXpNOC41IDE1Yy0uODMgMC0xLjUtLjY3LTEuNS0xLjVTNy42NyAxMiA4LjUgMTJzMS41LjY3IDEuNSAxLjVTOS4zMyAxNSA4LjUgMTV6bTItNUM5LjY3IDEwIDkgOS4zMyA5IDguNVM5LjY3IDcgMTAuNSA3czEuNS42NyAxLjUgMS41cy0uNjcgMS41LTEuNSAxLjV6bTQuNSA2Yy0uNTUgMC0xLS40NS0xLTFzLjQ1LTEgMS0xczEgLjQ1IDEgMXMtLjQ1IDEtMSAxeiIvPjwvc3ZnPg==';

    // The icon in the data URI scheme
    $icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;

    add_menu_page(
      'Divtag Cookie Consent', // page_title
      'Cookie Consent', // menu_title
      'manage_options', // capability
      'divtag-cookie-consent', // menu_slug
      [$this, 'divtag_cookie_consent_create_admin_page'], // function
      $icon_data_uri, // icon_url
      75 // position
    );
  }

  public function divtag_cookie_consent_create_admin_page()
  {
    $this->divtag_cookie_consent_options = get_option('divtag_cookie_consent_option_name'); ?>

    <div class="wrap">
      <h2>Divtag Cookie Consent</h2>
      <p>De taal van de Cookie Consent wordt bepaald door de WordPress taal instelling. Als Nederlands niet geselecteerd
        is, wordt automatisch Engels toegepast.</p>
      <?php settings_errors(); ?>

      <form method="post" action="options.php">
        <?php
        settings_fields('divtag_cookie_consent_option_group');
        do_settings_sections('divtag-cookie-consent-admin');
        submit_button();
        ?>
      </form>
    </div>
  <?php }

  public function divtag_cookie_consent_page_init()
  {
    register_setting(
      'divtag_cookie_consent_option_group', // option_group
      'divtag_cookie_consent_option_name', // option_name
      [$this, 'divtag_cookie_consent_sanitize'] // sanitize_callback
    );

    add_settings_section(
      'divtag_cookie_consent_main_setting_section', // id
      'Hoofdinstellingen', // title
      [$this, 'divtag_cookie_consent_section_info'], // callback
      'divtag-cookie-consent-admin' // page
    );

    add_settings_field(
      'button_color', // id
      'Knoppen kleur', // title
      [$this, 'button_color_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'force_consent', // id
      'Forceer consent', // title
      [$this, 'force_consent_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'title_nl', // id
      'Titel - NL', // title
      [$this, 'title_nl_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'description_nl', // id
      'Uitleg - NL', // title
      [$this, 'description_nl_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'title_en', // id
      'Titel - EN', // title
      [$this, 'title_en_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'description_en', // id
      'Uitleg - EN', // title
      [$this, 'description_en_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'contact_url', // id
      'Contact URL', // title
      [$this, 'contact_url_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_section(
      'divtag_cookie_consent_consent_modal_section', // id
      'Consent Modal', // title
      [$this, 'divtag_cookie_consent_section_info'], // callback
      'divtag-cookie-consent-admin' // page
    );

    add_settings_field(
      'layout', // id
      'Layout', // title
      [$this, 'layout_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_consent_modal_section' // section
    );

    add_settings_field(
      'position_vertical', // id
      'Positie verticaal', // title
      [$this, 'position_vertical_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_consent_modal_section' // section
    );

    add_settings_field(
      'position_horizontal', // id
      'Positie horizontaal', // title
      [$this, 'position_horizontal_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_consent_modal_section' // section
    );

    add_settings_field(
      'swap_buttons', // id
      'Draai knoppen om', // title
      [$this, 'swap_buttons_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_consent_modal_section' // section
    );

    add_settings_section(
      'divtag_cookie_consent_settings_modal_section', // id
      'Instellingen Modal', // title
      [$this, 'divtag_cookie_consent_section_info'], // callback
      'divtag-cookie-consent-admin' // page
    );

    add_settings_field(
      'layout_settings', // id
      'Layout', // title
      [$this, 'layout_settings_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_settings_modal_section' // section
    );

    add_settings_field(
      'position_horizontal_settings', // id
      'Positie horizontaal (Bar)', // title
      [$this, 'position_horizontal_settings_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_settings_modal_section' // section
    );

    add_settings_field(
      'google_tag_manager_code', // id
      'Google Tag Manager code', // title
      [$this, 'google_tag_manager_code_callback'], // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_settings_modal_section' // section
    );
  }

  public function divtag_cookie_consent_sanitize($input)
  {
    $sanitary_values = [];
    if (isset($input['button_color'])) {
      $sanitary_values['button_color'] = sanitize_text_field($input['button_color']);
    }

    if (isset($input['force_consent'])) {
      $sanitary_values['force_consent'] = $input['force_consent'];
    }

    if (isset($input['title_nl'])) {
      $sanitary_values['title_nl'] = sanitize_text_field($input['title_nl']);
    }

    if (isset($input['description_nl'])) {
      $sanitary_values['description_nl'] = esc_textarea($input['description_nl']);
    }

    if (isset($input['title_en'])) {
      $sanitary_values['title_en'] = sanitize_text_field($input['title_en']);
    }

    if (isset($input['description_en'])) {
      $sanitary_values['description_en'] = esc_textarea($input['description_en']);
    }

    if (isset($input['contact_url'])) {
      $sanitary_values['contact_url'] = sanitize_text_field($input['contact_url']);
    }

    if (isset($input['layout'])) {
      $sanitary_values['layout'] = $input['layout'];
    }

    if (isset($input['position_vertical'])) {
      $sanitary_values['position_vertical'] = $input['position_vertical'];
    }

    if (isset($input['position_horizontal'])) {
      $sanitary_values['position_horizontal'] = $input['position_horizontal'];
    }

    if (isset($input['swap_buttons'])) {
      $sanitary_values['swap_buttons'] = $input['swap_buttons'];
    }

    if (isset($input['layout_settings'])) {
      $sanitary_values['layout_settings'] = $input['layout_settings'];
    }

    if (isset($input['position_horizontal_settings'])) {
      $sanitary_values['position_horizontal_settings'] = $input['position_horizontal_settings'];
    }

    if (isset($input['google_tag_manager_code'])) {
      $sanitary_values['google_tag_manager_code'] = $input['google_tag_manager_code'];
    }

    return $sanitary_values;
  }

  public function divtag_cookie_consent_section_info()
  {

  }

  public function button_color_callback()
  {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[button_color]" id="button_color" value="%s" data-coloris>',
      isset($this->divtag_cookie_consent_options['button_color']) ? esc_attr($this->divtag_cookie_consent_options['button_color']) : ''
    );
  }

  public function force_consent_callback()
  {
    printf(
      '<input type="checkbox" name="divtag_cookie_consent_option_name[force_consent]" id="force_consent" value="force_consent" %s> <label for="force_consent">Forceer de gebruiker de cookies te accepteren of weigeren</label>',
      (isset($this->divtag_cookie_consent_options['force_consent']) && $this->divtag_cookie_consent_options['force_consent'] === 'force_consent') ? 'checked' : ''
    );
  }

  public function title_nl_callback()
  {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[title_nl]" id="title_nl" value="%s">',
      isset($this->divtag_cookie_consent_options['title_nl']) ? esc_attr($this->divtag_cookie_consent_options['title_nl']) : ''
    );
  }

  public function description_nl_callback()
  {
    printf(
      '<textarea class="large-text" rows="5" name="divtag_cookie_consent_option_name[description_nl]" id="description_nl">%s</textarea>',
      isset($this->divtag_cookie_consent_options['description_nl']) ? esc_attr($this->divtag_cookie_consent_options['description_nl']) : ''
    );
  }

  public function title_en_callback()
  {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[title_en]" id="title_en" value="%s">',
      isset($this->divtag_cookie_consent_options['title_en']) ? esc_attr($this->divtag_cookie_consent_options['title_en']) : ''
    );
  }

  public function description_en_callback()
  {
    printf(
      '<textarea class="large-text" rows="5" name="divtag_cookie_consent_option_name[description_en]" id="description_en">%s</textarea>',
      isset($this->divtag_cookie_consent_options['description_en']) ? esc_attr($this->divtag_cookie_consent_options['description_en']) : ''
    );
  }

  public function contact_url_callback()
  {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[contact_url]" id="contact_url" value="%s"><p class="description">Vul hier een adres in waar de gebruiker heen kan gaan om meer informatie te verkrijgen. Als dit leeg gelaten wordt, wordt het gevuld met het emailadres <b>' . get_option("admin_email") . '</b>.</p>',
      isset($this->divtag_cookie_consent_options['contact_url']) ? esc_attr($this->divtag_cookie_consent_options['contact_url']) : ''
    );
  }

  public function layout_callback()
  {
    ?> <select name="divtag_cookie_consent_option_name[layout]" id="layout">
    <?php $selected = (isset($this->divtag_cookie_consent_options['layout']) && $this->divtag_cookie_consent_options['layout'] === 'cloud') ? 'selected' : ''; ?>
    <option value="cloud inline" <?php echo $selected; ?>>Cloud</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['layout']) && $this->divtag_cookie_consent_options['layout'] === 'box') ? 'selected' : ''; ?>
    <option value="box" <?php echo $selected; ?>>Box</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['layout']) && $this->divtag_cookie_consent_options['layout'] === 'bar') ? 'selected' : ''; ?>
    <option value="bar" <?php echo $selected; ?>>Bar</option>
  </select> <?php
  }

  public function position_vertical_callback()
  {
    ?> <select name="divtag_cookie_consent_option_name[position_vertical]" id="position_vertical">
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_vertical']) && $this->divtag_cookie_consent_options['position_vertical'] === 'bottom') ? 'selected' : ''; ?>
    <option value="bottom" <?php echo $selected; ?>> Onderaan</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_vertical']) && $this->divtag_cookie_consent_options['position_vertical'] === 'middle') ? 'selected' : ''; ?>
    <option value="middle" <?php echo $selected; ?>> In het midden</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_vertical']) && $this->divtag_cookie_consent_options['position_vertical'] === 'top') ? 'selected' : ''; ?>
    <option value="top" <?php echo $selected; ?>> Bovenaan</option>
  </select> <?php
  }

  public function position_horizontal_callback()
  {
    ?> <select name="divtag_cookie_consent_option_name[position_horizontal]" id="position_horizontal">
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_horizontal']) && $this->divtag_cookie_consent_options['position_horizontal'] === 'right') ? 'selected' : ''; ?>
    <option value="right" <?php echo $selected; ?>> Rechts</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_horizontal']) && $this->divtag_cookie_consent_options['position_horizontal'] === 'left') ? 'selected' : ''; ?>
    <option value="left" <?php echo $selected; ?>> Links</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_horizontal']) && $this->divtag_cookie_consent_options['position_horizontal'] === 'center') ? 'selected' : ''; ?>
    <option value="center" <?php echo $selected; ?>> In het midden</option>
  </select> <?php
  }

  public function swap_buttons_callback()
  {
    printf(
      '<input type="checkbox" name="divtag_cookie_consent_option_name[swap_buttons]" id="swap_buttons" value="swap_buttons" %s> <label for="swap_buttons">Draai de acceptatie en weiger knoppen om</label>',
      (isset($this->divtag_cookie_consent_options['swap_buttons']) && $this->divtag_cookie_consent_options['swap_buttons'] === 'swap_buttons') ? 'checked' : ''
    );
  }

  public function layout_settings_callback()
  {
    ?> <select name="divtag_cookie_consent_option_name[layout_settings]" id="layout_settings">
    <?php $selected = (isset($this->divtag_cookie_consent_options['layout_settings']) && $this->divtag_cookie_consent_options['layout_settings'] === 'box') ? 'selected' : ''; ?>
    <option value="box" <?php echo $selected; ?>>Box</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['layout_settings']) && $this->divtag_cookie_consent_options['layout_settings'] === 'bar') ? 'selected' : ''; ?>
    <option value="bar" <?php echo $selected; ?>>Bar</option>
  </select> <?php
  }

  public function position_horizontal_settings_callback()
  {
    ?> <select name="divtag_cookie_consent_option_name[position_horizontal_settings]" id="position_horizontal_settings">
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_horizontal_settings']) && $this->divtag_cookie_consent_options['position_horizontal_settings'] === 'right') ? 'selected' : ''; ?>
    <option value="right" <?php echo $selected; ?>> Rechts</option>
    <?php $selected = (isset($this->divtag_cookie_consent_options['position_horizontal_settings']) && $this->divtag_cookie_consent_options['position_horizontal_settings'] === 'left') ? 'selected' : ''; ?>
    <option value="left" <?php echo $selected; ?>> Links</option>
  </select> <?php
  }

  public function google_tag_manager_code_callback()
  {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[google_tag_manager_code]" id="google_tag_manager_code" value="%s">',
      isset($this->divtag_cookie_consent_options['google_tag_manager_code']) ? esc_attr($this->divtag_cookie_consent_options['google_tag_manager_code']) : ''
    );
  }
}

if (is_admin()) {
  $divtag_cookie_consent = new DivtagCookieConsent();
}

/*
* Retrieve this value with:
* $divtag_cookie_consent_options = get_option( 'divtag_cookie_consent_option_name' ); // Array of All Options
* $button_color = $divtag_cookie_consent_options['button_color']; // Knoppen kleur
* $force_consent = $divtag_cookie_consent_options['force_consent']; // Forceer consent
* $title_nl = $divtag_cookie_consent_options['title_nl']; // Titel - NL
* $description_nl = $divtag_cookie_consent_options['description_nl']; // Uitleg - NL
* $title_en = $divtag_cookie_consent_options['title_en']; // Titel - EN
* $description_en = $divtag_cookie_consent_options['description_en']; // Uitleg - EN
* $contact_url = $divtag_cookie_consent_options['contact_url']; // Contact URL
* $layout = $divtag_cookie_consent_options['layout']; // Layout
* $position_vertical = $divtag_cookie_consent_options['position_vertical']; // Positie verticaal
* $position_horizontal = $divtag_cookie_consent_options['position_horizontal']; // Positie horizontaal
* $swap_buttons = $divtag_cookie_consent_options['swap_buttons']; // Draai knoppen om
* $layout_settings = $divtag_cookie_consent_options['layout_settings']; // Layout settings
* $position_horizontal_settings = $divtag_cookie_consent_options['position_horizontal_settings']; // Positie horizontaal settings
*/

?>
