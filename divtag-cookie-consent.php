<?php
/**
* Plugin Name: Divtag Cookie Consent
* Plugin URI: https://www.bitbucket.com/
* Description: Cookie Consent by Divtag
* Version: 1.0.3
* Author: Divtag
* Author URI: https://divtag.nl/
**/

/**
 * Bitbucket WP Update checker
 */

require('vendor/autoload.php');

$repo = 'divtag_nl/wp-plugin-divtag-cookie-consent'; // Name of your repository. This is either "<user>/<repo>" or "<team>/<repo>".
$bitbucket_username = 'robinvoormpo';                // Your personal BitBucket username
$bitbucket_app_pass = 'J5x5ZguHE4sWyc4qKay4';        // The generated app password with read access

new \Maneuver\BitbucketWpUpdater\PluginUpdater(__FILE__, $repo, $bitbucket_username, $bitbucket_app_pass);


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

function load_scripts() {
  $js_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/cookie-consent.js' ));

  wp_enqueue_script('cookie-consent', plugins_url( 'assets/js/cookie-consent.js', __FILE__ ), array(), $js_ver, true );
  wp_localize_script('cookie-consent', 'cookieConsentSettings',
    array(
      'options' => get_option('divtag_cookie_consent_option_name'),
      'adminEmail' => get_option('admin_email'),
    )
  );
}
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
      'divtag_cookie_consent_setting_section', // id
      'Instellingen', // title
      array( $this, 'divtag_cookie_consent_section_info' ), // callback
      'divtag-cookie-consent-admin' // page
    );

    add_settings_field(
			'knoppen_kleur_12', // id
			'Knoppen kleur', // title
			array( $this, 'knoppen_kleur_12_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_setting_section' // section
		);

    add_settings_field(
      'forceer_consent_0', // id
      'Forceer consent', // title
      array( $this, 'forceer_consent_0_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_setting_section' // section
    );

    add_settings_field(
      'donkere_modus_1', // id
      'Donkere modus', // title
      array( $this, 'donkere_modus_1_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_setting_section' // section
    );

    add_settings_field(
      'titel_nl_2', // id
      'Titel - NL', // title
      array( $this, 'titel_nl_2_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_setting_section' // section
    );

    add_settings_field(
      'uitleg_nl_3', // id
      'Uitleg - NL', // title
      array( $this, 'uitleg_nl_3_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_setting_section' // section
    );

    add_settings_field(
      'titel_en_4', // id
      'Titel - EN', // title
      array( $this, 'titel_en_4_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_setting_section' // section
    );

    add_settings_field(
      'uitleg_en_5', // id
      'Uitleg - EN', // title
      array( $this, 'uitleg_en_5_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_setting_section' // section
    );

    add_settings_field(
      'contact_url_6', // id
      'Contact URL', // title
      array( $this, 'contact_url_6_callback' ), // callback
      'divtag-cookie-consent-admin', // page
      'divtag_cookie_consent_setting_section' // section
    );

    add_settings_field(
			'layout_7', // id
			'Layout', // title
			array( $this, 'layout_7_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_setting_section' // section
		);

		add_settings_field(
			'positie_verticaal_8', // id
			'Positie verticaal', // title
			array( $this, 'positie_verticaal_8_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_setting_section' // section
		);

		add_settings_field(
			'positie_horizontaal_9', // id
			'Positie horizontaal', // title
			array( $this, 'positie_horizontaal_9_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_setting_section' // section
		);

    add_settings_field(
			'transitie_11', // id
			'Transitie', // title
			array( $this, 'transitie_11_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_setting_section' // section
		);

		add_settings_field(
			'draai_knoppen_om_10', // id
			'Draai knoppen om', // title
			array( $this, 'draai_knoppen_om_10_callback' ), // callback
			'divtag-cookie-consent-admin', // page
			'divtag_cookie_consent_setting_section' // section
		);
  }

  public function divtag_cookie_consent_sanitize($input) {
    $sanitary_values = array();
    if ( isset( $input['knoppen_kleur_12'] ) ) {
      $sanitary_values['knoppen_kleur_12'] = sanitize_text_field( $input['knoppen_kleur_12'] );
    }

    if ( isset( $input['forceer_consent_0'] ) ) {
      $sanitary_values['forceer_consent_0'] = $input['forceer_consent_0'];
    }

    if ( isset( $input['donkere_modus_1'] ) ) {
      $sanitary_values['donkere_modus_1'] = $input['donkere_modus_1'];
    }

    if ( isset( $input['titel_nl_2'] ) ) {
      $sanitary_values['titel_nl_2'] = sanitize_text_field( $input['titel_nl_2'] );
    }

    if ( isset( $input['uitleg_nl_3'] ) ) {
      $sanitary_values['uitleg_nl_3'] = esc_textarea( $input['uitleg_nl_3'] );
    }

    if ( isset( $input['titel_en_4'] ) ) {
      $sanitary_values['titel_en_4'] = sanitize_text_field( $input['titel_en_4'] );
    }

    if ( isset( $input['uitleg_en_5'] ) ) {
      $sanitary_values['uitleg_en_5'] = esc_textarea( $input['uitleg_en_5'] );
    }

    if ( isset( $input['contact_url_6'] ) ) {
      $sanitary_values['contact_url_6'] = sanitize_text_field( $input['contact_url_6'] );
    }

    if ( isset( $input['layout_7'] ) ) {
			$sanitary_values['layout_7'] = $input['layout_7'];
		}

		if ( isset( $input['positie_verticaal_8'] ) ) {
			$sanitary_values['positie_verticaal_8'] = $input['positie_verticaal_8'];
		}

		if ( isset( $input['positie_horizontaal_9'] ) ) {
			$sanitary_values['positie_horizontaal_9'] = $input['positie_horizontaal_9'];
		}

    if ( isset( $input['transitie_11'] ) ) {
			$sanitary_values['transitie_11'] = $input['transitie_11'];
		}

		if ( isset( $input['draai_knoppen_om_10'] ) ) {
			$sanitary_values['draai_knoppen_om_10'] = $input['draai_knoppen_om_10'];
		}

    return $sanitary_values;
  }

  public function divtag_cookie_consent_section_info() {
    
  }

  public function knoppen_kleur_12_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[knoppen_kleur_12]" id="knoppen_kleur_12" value="%s"><p class="description">Vul een Hex kleurcode in inclusief \'#\' om de standaard kleur voor de knoppen te vervangen.</p>',
      isset( $this->divtag_cookie_consent_options['knoppen_kleur_12'] ) ? esc_attr( $this->divtag_cookie_consent_options['knoppen_kleur_12']) : ''
    );
  }

  public function forceer_consent_0_callback() {
    printf(
      '<input type="checkbox" name="divtag_cookie_consent_option_name[forceer_consent_0]" id="forceer_consent_0" value="forceer_consent_0" %s> <label for="forceer_consent_0">Forceer de gebruiker de cookies te accepteren of weigeren</label>',
      ( isset( $this->divtag_cookie_consent_options['forceer_consent_0'] ) && $this->divtag_cookie_consent_options['forceer_consent_0'] === 'forceer_consent_0' ) ? 'checked' : ''
    );
  }

  public function donkere_modus_1_callback() {
    printf(
      '<input type="checkbox" name="divtag_cookie_consent_option_name[donkere_modus_1]" id="donkere_modus_1" value="donkere_modus_1" %s> <label for="donkere_modus_1">Weergeef de Cookie Consent in een donker thema</label>',
      ( isset( $this->divtag_cookie_consent_options['donkere_modus_1'] ) && $this->divtag_cookie_consent_options['donkere_modus_1'] === 'donkere_modus_1' ) ? 'checked' : ''
    );
  }

  public function titel_nl_2_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[titel_nl_2]" id="titel_nl_2" value="%s">',
      isset( $this->divtag_cookie_consent_options['titel_nl_2'] ) ? esc_attr( $this->divtag_cookie_consent_options['titel_nl_2']) : ''
    );
  }

  public function uitleg_nl_3_callback() {
    printf(
      '<textarea class="large-text" rows="5" name="divtag_cookie_consent_option_name[uitleg_nl_3]" id="uitleg_nl_3">%s</textarea>',
      isset( $this->divtag_cookie_consent_options['uitleg_nl_3'] ) ? esc_attr( $this->divtag_cookie_consent_options['uitleg_nl_3']) : ''
    );
  }

  public function titel_en_4_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[titel_en_4]" id="titel_en_4" value="%s">',
      isset( $this->divtag_cookie_consent_options['titel_en_4'] ) ? esc_attr( $this->divtag_cookie_consent_options['titel_en_4']) : ''
    );
  }

  public function uitleg_en_5_callback() {
    printf(
      '<textarea class="large-text" rows="5" name="divtag_cookie_consent_option_name[uitleg_en_5]" id="uitleg_en_5">%s</textarea>',
      isset( $this->divtag_cookie_consent_options['uitleg_en_5'] ) ? esc_attr( $this->divtag_cookie_consent_options['uitleg_en_5']) : ''
    );
  }

  public function contact_url_6_callback() {
    printf(
      '<input class="regular-text" type="text" name="divtag_cookie_consent_option_name[contact_url_6]" id="contact_url_6" value="%s"><p class="description">Vul hier een adres in waar de gebruiker heen kan gaan om meer informatie te verkrijgen. Als dit leeg gelaten wordt, wordt het gevuld met het admin emailadres en mailto naar <b>' . get_option("admin_email") . '</b>.</p>',
      isset( $this->divtag_cookie_consent_options['contact_url_6'] ) ? esc_attr( $this->divtag_cookie_consent_options['contact_url_6']) : ''
    );
  }

  public function layout_7_callback() {
		?> <select name="divtag_cookie_consent_option_name[layout_7]" id="layout_7">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout_7'] ) && $this->divtag_cookie_consent_options['layout_7'] === 'cloud') ? 'selected' : '' ; ?>
			<option value="cloud" <?php echo $selected; ?>>Cloud</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout_7'] ) && $this->divtag_cookie_consent_options['layout_7'] === 'box') ? 'selected' : '' ; ?>
			<option value="box" <?php echo $selected; ?>>Box</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['layout_7'] ) && $this->divtag_cookie_consent_options['layout_7'] === 'bar') ? 'selected' : '' ; ?>
			<option value="bar" <?php echo $selected; ?>>Bar</option>
		</select> <?php
	}

	public function positie_verticaal_8_callback() {
		?> <select name="divtag_cookie_consent_option_name[positie_verticaal_8]" id="positie_verticaal_8">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_verticaal_8'] ) && $this->divtag_cookie_consent_options['positie_verticaal_8'] === 'bottom') ? 'selected' : '' ; ?>
			<option value="bottom" <?php echo $selected; ?>> Onderaan</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_verticaal_8'] ) && $this->divtag_cookie_consent_options['positie_verticaal_8'] === 'middle') ? 'selected' : '' ; ?>
			<option value="middle" <?php echo $selected; ?>> In het midden</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_verticaal_8'] ) && $this->divtag_cookie_consent_options['positie_verticaal_8'] === 'top') ? 'selected' : '' ; ?>
			<option value="top" <?php echo $selected; ?>> Bovenaan</option>
		</select> <?php
	}

	public function positie_horizontaal_9_callback() {
		?> <select name="divtag_cookie_consent_option_name[positie_horizontaal_9]" id="positie_horizontaal_9">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal_9'] ) && $this->divtag_cookie_consent_options['positie_horizontaal_9'] === 'right') ? 'selected' : '' ; ?>
			<option value="right" <?php echo $selected; ?>> Rechts</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal_9'] ) && $this->divtag_cookie_consent_options['positie_horizontaal_9'] === 'left') ? 'selected' : '' ; ?>
			<option value="left" <?php echo $selected; ?>> Links</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['positie_horizontaal_9'] ) && $this->divtag_cookie_consent_options['positie_horizontaal_9'] === 'center') ? 'selected' : '' ; ?>
			<option value="center" <?php echo $selected; ?>> In het midden</option>
		</select> <?php
	}

  public function transitie_11_callback() {
		?> <select name="divtag_cookie_consent_option_name[transitie_11]" id="transitie_11">
			<?php $selected = (isset( $this->divtag_cookie_consent_options['transitie_11'] ) && $this->divtag_cookie_consent_options['transitie_11'] === 'slide') ? 'selected' : '' ; ?>
			<option value="slide" <?php echo $selected; ?>> Slide</option>
			<?php $selected = (isset( $this->divtag_cookie_consent_options['transitie_11'] ) && $this->divtag_cookie_consent_options['transitie_11'] === 'zoom') ? 'selected' : '' ; ?>
			<option value="zoom" <?php echo $selected; ?>> Zoom</option>
		</select> <?php
	}

	public function draai_knoppen_om_10_callback() {
		printf(
			'<input type="checkbox" name="divtag_cookie_consent_option_name[draai_knoppen_om_10]" id="draai_knoppen_om_10" value="draai_knoppen_om_10" %s> <label for="draai_knoppen_om_10">Draai de acceptatie en weiger knoppen om</label>',
			( isset( $this->divtag_cookie_consent_options['draai_knoppen_om_10'] ) && $this->divtag_cookie_consent_options['draai_knoppen_om_10'] === 'draai_knoppen_om_10' ) ? 'checked' : ''
		);
	}

  }
  if ( is_admin() )
  $divtag_cookie_consent = new DivtagCookieConsent();

/* 
* Retrieve this value with:
* $divtag_cookie_consent_options = get_option( 'divtag_cookie_consent_option_name' ); // Array of All Options
* $forceer_consent_0 = $divtag_cookie_consent_options['forceer_consent_0']; // Forceer consent
* $donkere_modus_1 = $divtag_cookie_consent_options['donkere_modus_1']; // Donkere modus
* $titel_nl_2 = $divtag_cookie_consent_options['titel_nl_2']; // Titel - NL
* $uitleg_nl_3 = $divtag_cookie_consent_options['uitleg_nl_3']; // Uitleg - NL
* $titel_en_4 = $divtag_cookie_consent_options['titel_en_4']; // Titel - EN
* $uitleg_en_5 = $divtag_cookie_consent_options['uitleg_en_5']; // Uitleg - EN
* $contact_url_6 = $divtag_cookie_consent_options['contact_url_6']; // Contact URL
* $layout_7 = $divtag_cookie_consent_options['layout_7']; // Layout
* $positie_verticaal_8 = $divtag_cookie_consent_options['positie_verticaal_8']; // Positie verticaal
* $positie_horizontaal_9 = $divtag_cookie_consent_options['positie_horizontaal_9']; // Positie horizontaal
* $draai_knoppen_om_10 = $divtag_cookie_consent_options['draai_knoppen_om_10']; // Draai knoppen om
* $transitie_11 = $divtag_cookie_consent_options['transitie_11']; // Transitie
* $knoppen_kleur_12 = $divtag_cookie_consent_options['knoppen_kleur_12']; // Knoppen kleur
*/



?>
