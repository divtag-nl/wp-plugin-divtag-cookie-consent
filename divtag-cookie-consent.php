<?php
/**
* Plugin Name: Divtag Cookie Consent
* Plugin URI: https://github.com/divtag-nl/wp-plugin-divtag-cookie-consent
* Description: Cookie Consent by Divtag
* Version: 1.2.0
* Author: Divtag
* Author URI: https://divtag.nl/
**/

/**
 * Bitbucket WP Plugin Update checker
 */
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/divtag-nl/wp-plugin-divtag-cookie-consent',
	__FILE__,
	'wp-plugin-divtag-cookie-consent'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');


/**
 * Load (external) scripts
 */

add_action( 'wp_head', 'header_scripts' );
function header_scripts(){
  ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v2.8.0/dist/cookieconsent.css">
  <?php
}

add_action( 'wp_footer', 'footer_scripts' );
function footer_scripts(){
  ?>
  <script src="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v2.8.0/dist/cookieconsent.js"></script>
  <?php
}

function add_type_attribute($tag, $handle, $src) {
  if ( 'cookie-consent' !== $handle ) {
    return $tag;
  }
  $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
  return $tag;
}

function load_scripts() {
  $js_ver = date("dmy-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/cookie-consent.js' ));

  wp_enqueue_script('cookie-consent', plugins_url( 'assets/js/cookie-consent.js', __FILE__ ), array(), $js_ver, true );
  wp_localize_script('cookie-consent', 'cookie_consent_settings',
    array(
      'options' => get_option('divtag_cookie_consent_option_name'),
      'admin_email' => get_option('admin_email'),
    )
  );
}
add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);
add_action('wp_enqueue_scripts', 'load_scripts');


/**
 * WordPress Option Page including custom Cookie Consent settings
 */

class DivtagCookieConsent {
	private $divtag_cookie_consent_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'divtag_cookie_consent_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'divtag_cookie_consent_page_init' ) );
	}

	public function divtag_cookie_consent_add_plugin_page() {
    // Cookie icon in Base64 format
    $icon_base64 = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGFyaWEtaGlkZGVuPSJ0cnVlIiByb2xlPSJpbWciIHdpZHRoPSIxZW0iIGhlaWdodD0iMWVtIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWlkWU1pZCBtZWV0IiB2aWV3Qm94PSIwIDAgMjQgMjQiPjxwYXRoIGZpbGw9ImN1cnJlbnRDb2xvciIgZD0iTTIxLjk1IDEwLjk5Yy0xLjc5LS4wMy0zLjctMS45NS0yLjY4LTQuMjJjLTIuOTggMS01Ljc3LTEuNTktNS4xOS00LjU2QzYuOTUuNzEgMiA2LjU4IDIgMTJjMCA1LjUyIDQuNDggMTAgMTAgMTBjNS44OSAwIDEwLjU0LTUuMDggOS45NS0xMS4wMXpNOC41IDE1Yy0uODMgMC0xLjUtLjY3LTEuNS0xLjVTNy42NyAxMiA4LjUgMTJzMS41LjY3IDEuNSAxLjVTOS4zMyAxNSA4LjUgMTV6bTItNUM5LjY3IDEwIDkgOS4zMyA5IDguNVM5LjY3IDcgMTAuNSA3czEuNS42NyAxLjUgMS41cy0uNjcgMS41LTEuNSAxLjV6bTQuNSA2Yy0uNTUgMC0xLS40NS0xLTFzLjQ1LTEgMS0xczEgLjQ1IDEgMXMtLjQ1IDEtMSAxeiIvPjwvc3ZnPg==';

    // The icon in the data URI scheme
    $icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;

		add_menu_page(
			'Divtag Cookie Consent', // page_title
			'Cookie Consent', // menu_title
			'manage_options', // capability
			'divtag-cookie-consent', // menu_slug
			array( $this, 'divtag_cookie_consent_create_admin_page' ), // function
			$icon_data_uri, // icon_url
			75 // position
		);
	}

	public function divtag_cookie_consent_create_admin_page() {
		$this->divtag_cookie_consent_options = get_option( 'divtag_cookie_consent_option_name' ); ?>

		<div class="wrap">
			<h2>Divtag Cookie Consent</h2>
      <p>De taal van de Cookie Consent wordt bepaald door de WordPress taal instelling. Als Nederlands niet geselecteerd is, wordt automatisch Engels toegepast.</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'divtag_cookie_consent_option_group' );
					do_settings_sections( 'divtag-cookie-consent-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

  public function divtag_cookie_consent_page_init() {
    register_setting(
      'divtag_cookie_consent_option_group', // option_group
      'divtag_cookie_consent_option_name', // option_name
      array( $this, 'divtag_cookie_consent_sanitize' ) // sanitize_callback
    );

    add_settings_section(
      'divtag_cookie_consent_main_setting_section', // id
      'Hoofdinstellingen', // title
      array( $this, 'divtag_cookie_consent_section_info' ), // callback
      'divtag-cookie-consent-admin' // page
    );

    add_settings_field(
			'knoppen_kleur', // id
			'Knoppen kleur', // title
			array( $this, 'knoppen_kleur_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_main_setting_section' // section
		);

    add_settings_field(
      'forceer_consent', // id
      'Forceer consent', // title
      array( $this, 'forceer_consent_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'donkere_modus', // id
      'Donkere modus', // title
      array( $this, 'donkere_modus_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'titel_nl', // id
      'Titel - NL', // title
      array( $this, 'titel_nl_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'uitleg_nl', // id
      'Uitleg - NL', // title
      array( $this, 'uitleg_nl_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'titel_en', // id
      'Titel - EN', // title
      array( $this, 'titel_en_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'uitleg_en', // id
      'Uitleg - EN', // title
      array( $this, 'uitleg_en_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_field(
      'contact_url', // id
      'Contact URL', // title
      array( $this, 'contact_url_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_main_setting_section' // section
    );

    add_settings_section(
      'divtag_cookie_consent_consent_modal_section', // id
      'Consent Modal', // title
      array( $this, 'divtag_cookie_consent_section_info' ), // callback
      'divtag-cookie-consent-admin' // page
    );

    add_settings_field(
			'layout', // id
			'Layout', // title
			array( $this, 'layout_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_consent_modal_section' // section
		);

		add_settings_field(
			'positie_verticaal', // id
			'Positie verticaal', // title
			array( $this, 'positie_verticaal_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_consent_modal_section' // section
		);

		add_settings_field(
			'positie_horizontaal', // id
			'Positie horizontaal', // title
			array( $this, 'positie_horizontaal_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_consent_modal_section' // section
		);

    add_settings_field(
			'transitie', // id
			'Transitie', // title
			array( $this, 'transitie_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_consent_modal_section' // section
		);

		add_settings_field(
			'draai_knoppen_om', // id
			'Draai knoppen om', // title
			array( $this, 'draai_knoppen_om_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_consent_modal_section' // section
		);

    add_settings_section(
      'divtag_cookie_consent_settings_modal_section', // id
      'Instellingen Modal', // title
      array( $this, 'divtag_cookie_consent_section_info' ), // callback
      'divtag-cookie-consent-admin' // page
    );

    add_settings_field(
			'layout_settings', // id
			'Layout', // title
			array( $this, 'layout_settings_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_settings_modal_section' // section
		);

		add_settings_field(
			'positie_horizontaal_settings', // id
			'Positie horizontaal', // title
			array( $this, 'positie_horizontaal_settings_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_settings_modal_section' // section
		);

    add_settings_field(
			'transitie_settings', // id
			'Transitie', // title
			array( $this, 'transitie_settings_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_settings_modal_section' // section
		);
  }

  public function divtag_cookie_consent_sanitize($input) {
    $sanitary_values = array();
    if ( isset( $input['knoppen_kleur'] ) ) {
      $sanitary_values['knoppen_kleur'] = sanitize_text_field( $input['knoppen_kleur'] );
    }

    if ( isset( $input['forceer_consent'] ) ) {
      $sanitary_values['forceer_consent'] = $input['forceer_consent'];
    }

    if ( isset( $input['donkere_modus'] ) ) {
      $sanitary_values['donkere_modus'] = $input['donkere_modus'];
    }

    if ( isset( $input['titel_nl'] ) ) {
      $sanitary_values['titel_nl'] = sanitize_text_field( $input['titel_nl'] );
    }

    if ( isset( $input['uitleg_nl'] ) ) {
      $sanitary_values['uitleg_nl'] = esc_textarea( $input['uitleg_nl'] );
    }

    if ( isset( $input['titel_en'] ) ) {
      $sanitary_values['titel_en'] = sanitize_text_field( $input['titel_en'] );
    }

    if ( isset( $input['uitleg_en'] ) ) {
      $sanitary_values['uitleg_en'] = esc_textarea( $input['uitleg_en'] );
    }

    if ( isset( $input['contact_url'] ) ) {
      $sanitary_values['contact_url'] = sanitize_text_field( $input['contact_url'] );
    }

    if ( isset( $input['layout'] ) ) {
			$sanitary_values['layout'] = $input['layout'];
		}

		if ( isset( $input['positie_verticaal'] ) ) {
			$sanitary_values['positie_verticaal'] = $input['positie_verticaal'];
		}

		if ( isset( $input['positie_horizontaal'] ) ) {
			$sanitary_values['positie_horizontaal'] = $input['positie_horizontaal'];
		}

    if ( isset( $input['transitie'] ) ) {
			$sanitary_values['transitie'] = $input['transitie'];
		}

		if ( isset( $input['draai_knoppen_om'] ) ) {
			$sanitary_values['draai_knoppen_om'] = $input['draai_knoppen_om'];
		}

    if ( isset( $input['layout_settings'] ) ) {
			$sanitary_values['layout_settings'] = $input['layout_settings'];
		}

		if ( isset( $input['positie_horizontaal_settings'] ) ) {
			$sanitary_values['positie_horizontaal_settings'] = $input['positie_horizontaal_settings'];
		}

    if ( isset( $input['transitie_settings'] ) ) {
			$sanitary_values['transitie_settings'] = $input['transitie_settings'];
		}

    return $sanitary_values;
  }

  public function divtag_cookie_consent_section_info() {
    
  }

  public function knoppen_kleur_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[knoppen_kleur]" id="knoppen_kleur" value="%s"><p class="description">Vul een Hex kleurcode in inclusief \'#\' om de standaard kleur voor de knoppen te vervangen.</p>',
      isset( $this->divtag_cookie_consent_options['knoppen_kleur'] ) ? esc_attr( $this->divtag_cookie_consent_options['knoppen_kleur']) : ''
    );
  }

  public function forceer_consent_callback() {
    printf(
      '<input type="checkbox" name="divtag_cookie_consent_option_name[forceer_consent]" id="forceer_consent" value="forceer_consent" %s> <label for="forceer_consent">Forceer de gebruiker de cookies te accepteren of weigeren</label>',
      ( isset( $this->divtag_cookie_consent_options['forceer_consent'] ) && $this->divtag_cookie_consent_options['forceer_consent'] === 'forceer_consent' ) ? 'checked' : ''
    );
  }

  public function donkere_modus_callback() {
    printf(
      '<input type="checkbox" name="divtag_cookie_consent_option_name[donkere_modus]" id="donkere_modus" value="donkere_modus" %s> <label for="donkere_modus">Weergeef de Cookie Consent in een donker thema</label>',
      ( isset( $this->divtag_cookie_consent_options['donkere_modus'] ) && $this->divtag_cookie_consent_options['donkere_modus'] === 'donkere_modus' ) ? 'checked' : ''
    );
  }

  public function titel_nl_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[titel_nl]" id="titel_nl" value="%s">',
      isset( $this->divtag_cookie_consent_options['titel_nl'] ) ? esc_attr( $this->divtag_cookie_consent_options['titel_nl']) : ''
    );
  }

  public function uitleg_nl_callback() {
    printf(
      '<textarea class="large-text" rows="5" name="divtag_cookie_consent_option_name[uitleg_nl]" id="uitleg_nl">%s</textarea>',
      isset( $this->divtag_cookie_consent_options['uitleg_nl'] ) ? esc_attr( $this->divtag_cookie_consent_options['uitleg_nl']) : ''
    );
  }

  public function titel_en_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[titel_en]" id="titel_en" value="%s">',
      isset( $this->divtag_cookie_consent_options['titel_en'] ) ? esc_attr( $this->divtag_cookie_consent_options['titel_en']) : ''
    );
  }

  public function uitleg_en_callback() {
    printf(
      '<textarea class="large-text" rows="5" name="divtag_cookie_consent_option_name[uitleg_en]" id="uitleg_en">%s</textarea>',
      isset( $this->divtag_cookie_consent_options['uitleg_en'] ) ? esc_attr( $this->divtag_cookie_consent_options['uitleg_en']) : ''
    );
  }

  public function contact_url_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[contact_url]" id="contact_url" value="%s"><p class="description">Vul hier een adres in waar de gebruiker heen kan gaan om meer informatie te verkrijgen. Als dit leeg gelaten wordt, wordt het gevuld met het admin emailadres en mailto naar <b>' . get_option("admin_email") . '</b>.</p>',
      isset( $this->divtag_cookie_consent_options['contact_url'] ) ? esc_attr( $this->divtag_cookie_consent_options['contact_url']) : ''
    );
  }

  public function layout_callback() {
		?> <select name="divtag_cookie_consent_option_name[layout]" id="layout">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout'] ) && $this->divtag_cookie_consent_options['layout'] === 'cloud') ? 'selected' : '' ; ?>
			<option value="cloud" <?php echo $selected; ?>>Cloud</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout'] ) && $this->divtag_cookie_consent_options['layout'] === 'box') ? 'selected' : '' ; ?>
			<option value="box" <?php echo $selected; ?>>Box</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout'] ) && $this->divtag_cookie_consent_options['layout'] === 'bar') ? 'selected' : '' ; ?>
			<option value="bar" <?php echo $selected; ?>>Bar</option>
		</select> <?php
	}

	public function positie_verticaal_callback() {
		?> <select name="divtag_cookie_consent_option_name[positie_verticaal]" id="positie_verticaal">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_verticaal'] ) && $this->divtag_cookie_consent_options['positie_verticaal'] === 'bottom') ? 'selected' : '' ; ?>
			<option value="bottom" <?php echo $selected; ?>> Onderaan</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_verticaal'] ) && $this->divtag_cookie_consent_options['positie_verticaal'] === 'middle') ? 'selected' : '' ; ?>
			<option value="middle" <?php echo $selected; ?>> In het midden</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_verticaal'] ) && $this->divtag_cookie_consent_options['positie_verticaal'] === 'top') ? 'selected' : '' ; ?>
			<option value="top" <?php echo $selected; ?>> Bovenaan</option>
		</select> <?php
	}

	public function positie_horizontaal_callback() {
		?> <select name="divtag_cookie_consent_option_name[positie_horizontaal]" id="positie_horizontaal">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal'] ) && $this->divtag_cookie_consent_options['positie_horizontaal'] === 'right') ? 'selected' : '' ; ?>
			<option value="right" <?php echo $selected; ?>> Rechts</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal'] ) && $this->divtag_cookie_consent_options['positie_horizontaal'] === 'left') ? 'selected' : '' ; ?>
			<option value="left" <?php echo $selected; ?>> Links</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal'] ) && $this->divtag_cookie_consent_options['positie_horizontaal'] === 'center') ? 'selected' : '' ; ?>
			<option value="center" <?php echo $selected; ?>> In het midden</option>
		</select> <?php
	}

  public function transitie_callback() {
		?> <select name="divtag_cookie_consent_option_name[transitie]" id="transitie">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['transitie'] ) && $this->divtag_cookie_consent_options['transitie'] === 'slide') ? 'selected' : '' ; ?>
			<option value="slide" <?php echo $selected; ?>> Slide</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['transitie'] ) && $this->divtag_cookie_consent_options['transitie'] === 'zoom') ? 'selected' : '' ; ?>
			<option value="zoom" <?php echo $selected; ?>> Zoom</option>
		</select> <?php
	}

	public function draai_knoppen_om_callback() {
		printf(
			'<input type="checkbox" name="divtag_cookie_consent_option_name[draai_knoppen_om]" id="draai_knoppen_om" value="draai_knoppen_om" %s> <label for="draai_knoppen_om">Draai de acceptatie en weiger knoppen om</label>',
			( isset( $this->divtag_cookie_consent_options['draai_knoppen_om'] ) && $this->divtag_cookie_consent_options['draai_knoppen_om'] === 'draai_knoppen_om' ) ? 'checked' : ''
		);
	}

  public function layout_settings_callback() {
		?> <select name="divtag_cookie_consent_option_name[layout_settings]" id="layout_settings">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout_settings'] ) && $this->divtag_cookie_consent_options['layout_settings'] === 'box') ? 'selected' : '' ; ?>
			<option value="box" <?php echo $selected; ?>>Box</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout_settings'] ) && $this->divtag_cookie_consent_options['layout_settings'] === 'bar') ? 'selected' : '' ; ?>
			<option value="bar" <?php echo $selected; ?>>Bar</option>
		</select> <?php
	}

	public function positie_horizontaal_settings_callback() {
		?> <select name="divtag_cookie_consent_option_name[positie_horizontaal_settings]" id="positie_horizontaal_settings">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal_settings'] ) && $this->divtag_cookie_consent_options['positie_horizontaal_settings'] === 'right') ? 'selected' : '' ; ?>
			<option value="right" <?php echo $selected; ?>> Rechts</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal_settings'] ) && $this->divtag_cookie_consent_options['positie_horizontaal_settings'] === 'left') ? 'selected' : '' ; ?>
			<option value="left" <?php echo $selected; ?>> Links</option>
		</select> <?php
	}

  public function transitie_settings_callback() {
		?> <select name="divtag_cookie_consent_option_name[transitie_settings]" id="transitie_settings">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['transitie_settings'] ) && $this->divtag_cookie_consent_options['transitie_settings'] === 'slide') ? 'selected' : '' ; ?>
			<option value="slide" <?php echo $selected; ?>> Slide</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['transitie_settings'] ) && $this->divtag_cookie_consent_options['transitie_settings'] === 'zoom') ? 'selected' : '' ; ?>
			<option value="zoom" <?php echo $selected; ?>> Zoom</option>
		</select> <?php
	}

  }
  if ( is_admin() )
  $divtag_cookie_consent = new DivtagCookieConsent();

/* 
* Retrieve this value with:
* $divtag_cookie_consent_options = get_option( 'divtag_cookie_consent_option_name' ); // Array of All Options
* $knoppen_kleur = $divtag_cookie_consent_options['knoppen_kleur']; // Knoppen kleur
* $forceer_consent = $divtag_cookie_consent_options['forceer_consent']; // Forceer consent
* $donkere_modus = $divtag_cookie_consent_options['donkere_modus']; // Donkere modus
* $titel_nl = $divtag_cookie_consent_options['titel_nl']; // Titel - NL
* $uitleg_nl = $divtag_cookie_consent_options['uitleg_nl']; // Uitleg - NL
* $titel_en = $divtag_cookie_consent_options['titel_en']; // Titel - EN
* $uitleg_en = $divtag_cookie_consent_options['uitleg_en']; // Uitleg - EN
* $contact_url = $divtag_cookie_consent_options['contact_url']; // Contact URL
* $layout = $divtag_cookie_consent_options['layout']; // Layout
* $positie_verticaal = $divtag_cookie_consent_options['positie_verticaal']; // Positie verticaal
* $positie_horizontaal = $divtag_cookie_consent_options['positie_horizontaal']; // Positie horizontaal
* $draai_knoppen_om = $divtag_cookie_consent_options['draai_knoppen_om']; // Draai knoppen om
* $transitie = $divtag_cookie_consent_options['transitie']; // Transitie
* $layout_settings = $divtag_cookie_consent_options['layout_settings']; // Layout settings
* $positie_horizontaal_settings = $divtag_cookie_consent_options['positie_horizontaal_settings']; // Positie horizontaal settings
* $transitie_settings = $divtag_cookie_consent_options['transitie_settings']; // Transitie settings
*/

?>
