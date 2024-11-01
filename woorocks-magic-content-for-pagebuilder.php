<?php
/**
 * Plugin Name: Magic Content for Siteorigin
 * Plugin URI: http://woorocks.com
 * Description: WooRocks Magic Content lets you control output of content created inside Siteorigins Page Builder Rows using criterias like User has to be logged in or member of a certain role in WordPress to view the content of the selected row.
 * Version: 1.0.1
 * Author: Andreas Kviby
 * Text Domain: woorocks-magic-content-for-pagebuilder
 * Author URI: http://woorocks.com
 * License: GPL2
 */
 // Create a helper function for easy SDK access.
function wmcfp_fs() {
    global $wmcfp_fs;

    if ( ! isset( $wmcfp_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $wmcfp_fs = fs_dynamic_init( array(
            'id'                  => '730',
            'slug'                => 'woorocks-magic-content-for-pagebuilder',
            'type'                => 'plugin',
            'public_key'          => 'pk_df9328999a8bfc122a944eb40b7d2',
            'is_premium'          => false,
            'has_premium_version' => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'       => 'wmcfpb-settings',
            ),
        ) );
    }

    return $wmcfp_fs;
}

// Init Freemius.
wmcfp_fs();

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

libxml_use_internal_errors(true);

$GLOBALS['wmcfpb__buffer_variable'] = '';

add_action( 'plugins_loaded', 'wmcfpb_load_plugin_textdomain' );

add_action('admin_menu', 'wmcfpb_plugin_menu');

function wmcfpb_plugin_menu() {
	add_menu_page('Magic Content Support', 'Magic Content', 'administrator', 'wmcfpb-settings', 'wmcfpb_settings_page', 'dashicons-admin-generic');
}

function wmcfpb_settings_page() {
  //?>
<div class="wrap" style="padding:10px;background-color:#ffffff;-webkit-box-shadow: 2px 3px 6px 0px rgba(0,0,0,0.6);
-moz-box-shadow: 2px 3px 6px 0px rgba(0,0,0,0.1);
box-shadow: 2px 3px 6px 0px rgba(0,0,0,0.1);">
<h2><?php echo _e('Magic Content Support','wp-pro-counter')?></h2>
<p>
  <?php echo _e('Welcome to the supportpage for Magic Content for Siteorigins Pagebuilder.','woorocks-magic-content-for-pagebuilder')?>
</p>
<hr>
<p><?php echo _e('Below you will find all issues, faq and links to support for this plugin.','woorocks-magic-content-for-pagebuilder')?></p>
<hr>
<h3><?php echo _e('Common useful links:','woorocks-magic-content-for-pagebuilder')?></h3>
<ul>
  <li>Demos & Support</li>
    <li><a href="http://woorocks.com">Demosite for live demos</a></li>
    <li><a href="https://siteorigin.com/page-builder/">Siteorigins Pagebuilder Plugin</a></li>
</ul>
<hr>
<h2><?php echo _e('Contact the developer','wp-pro-counter')?></h2>
Andreas Kviby can be contacted in person on <a href="mailto:andreas@uggadugg.com">andreas@uggadugg.com</a>
<hr>

</div>
  <?php
}

if( !function_exists('is_plugin_active') ) {

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		}

if ( is_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) ) {
  //IF SITEORIGIN PAGEBUILDER IS ACTIVE

  // Add Custom Class Options to SiteOrigin Rows
  function woorocks_custom_loggedin($fields) {
      $row_styles = array(
              'default-value-style' => 'Not enabled',
              'woorocks-loggedin' => 'Logged in',
              'woorocks-notloggedin' => 'Not Logged in'
          );

      $fields['row_styles'] = array(
      'name'        => __('<h4>Magic Content Properties</h4>Display for', 'woorocks-magic-content-for-pagebuilder'),
          'type'        => 'select',
          'options' => $row_styles,
          'group'       => 'attributes',
          'description' => __('Choose to whom content will be displayed', 'woorocks-magic-content-for-pagebuilder'),
          'priority'    => 1,
      );

    return $fields;
  }
  add_filter( 'siteorigin_panels_row_style_fields', 'woorocks_custom_loggedin' );



  function woorocks_loggedin_attributes( $attributes, $args ) {
      if( $args['row_styles'] != 'default-value-style' ) {
          array_push($attributes['class'], $args['row_styles'] );
      }

      return $attributes;
  }

  add_filter('siteorigin_panels_row_style_attributes', 'woorocks_loggedin_attributes', 10, 2);

  function woorocks_custom_roles($rolefields) {
      $role_styles = array(
              'default-value-style' => 'Not enabled',
              'woorocks-role-administrator' => 'Administrators',
              'woorocks-role-editor' => 'Editors',
              'woorocks-role-contributor' => 'Contributors',
              'woorocks-role-author' => 'Authors',
              'woorocks-role-subscriber' => 'Subscribers'
          );

      $rolefields['role_styles'] = array(
      'name'        => __('Display for roles', 'woorocks-magic-content-for-pagebuilder'),
          'type'        => 'select',
          'options' => $role_styles,
          'group'       => 'attributes',
          'description' => __('Choose to which role content will be displayed', 'woorocks-magic-content-for-pagebuilder'),
          'priority'    => 2,
      );

    return $rolefields;
  }
  add_filter( 'siteorigin_panels_row_style_fields', 'woorocks_custom_roles' );


  function woorocks_roles_attributes( $attributes, $args ) {
      if( $args['role_styles'] != 'default-value-style' ) {
          array_push($attributes['class'], $args['role_styles'] );
      }

      return $attributes;
  }
  add_filter('siteorigin_panels_row_style_attributes', 'woorocks_roles_attributes', 10, 2);

  // GEO CITY
  function woorocks_custom_city($cityfields) {
      $cityfields['city_styles'] = array(
      'name'        => __('Display for cities', 'woorocks-magic-content-for-pagebuilder'),
          'type'        => 'text',
          'group'       => 'attributes',
          'description' => __('Choose to which GEO location by city content will be displayed', 'woorocks-magic-content-for-pagebuilder'),
          'priority'    => 3,
      );

    return $cityfields;
  }
  add_filter( 'siteorigin_panels_row_style_fields', 'woorocks_custom_city' );


  function woorocks_city_attributes( $attributes, $args ) {
      if( $args['city_styles'] != '' ) {
          array_push($attributes['class'], 'woorocks-geo-city-' . $args['city_styles'] );
      }

      return $attributes;
  }
  add_filter('siteorigin_panels_row_style_attributes', 'woorocks_city_attributes', 10, 2);

// GEO REGION
function woorocks_custom_region($regionfields) {
    $regionfields['region_styles'] = array(
    'name'        => __('Display for regions', 'woorocks-magic-content-for-pagebuilder'),
        'type'        => 'text',
        'group'       => 'attributes',
        'description' => __('Choose to which GEO location by region content will be displayed', 'woorocks-magic-content-for-pagebuilder'),
        'priority'    => 3,
    );

  return $regionfields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'woorocks_custom_region' );


function woorocks_region_attributes( $attributes, $args ) {
    if( $args['region_styles'] != '' ) {
        array_push($attributes['class'], 'woorocks-geo-region-' . $args['region_styles'] );
    }

    return $attributes;
}
add_filter('siteorigin_panels_row_style_attributes', 'woorocks_region_attributes', 10, 2);

// GEO COUNTRY
function woorocks_custom_country($countryfields) {
    $countryfields['country_styles'] = array(
    'name'        => __('Display for countries', 'woorocks-magic-content-for-pagebuilder'),
        'type'        => 'text',
        'group'       => 'attributes',
        'description' => __('Choose to which GEO location by twoletter countrycode content will be displayed<hr>', 'woorocks-magic-content-for-pagebuilder'),
        'priority'    => 3,
    );

  return $countryfields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'woorocks_custom_country' );


function woorocks_country_attributes( $attributes, $args ) {
    if( $args['country_styles'] != '' ) {
        array_push($attributes['class'], 'woorocks-geo-country-' . $args['country_styles'] );
    }

    return $attributes;
}
add_filter('siteorigin_panels_row_style_attributes', 'woorocks_region_attributes', 10, 2);

}
else {
  add_action( 'admin_notices', 'wmcfpb_dashboard_message' );
}
 /**
  * Load gettext translate for our text domain.
  */
function wmcfpb_load_plugin_textdomain() {
   load_plugin_textdomain( 'woorocks-magic-content-for-pagebuilder' );
}
/*
 * Below is the filters that will change the content output depending on the
 * classes fixed in Elementor Page Editor.
*/
function wmcfpb_buffer_start() {
  if (!is_admin()){
    ob_start("wmcfpb_callback");
   }
}

function wmcfpb_buffer_end() {
  /*if (!is_admin()){
   ob_end_flush();
   $output = $GLOBALS['woorocks_buffer_variable'];
 }*/
}
function wmcfpb_callback($buffer) {
  // modify buffer here, and then return the updated code
  //$GLOBALS['final_html'] .= $buffer;
  //$buffer = str_replace('geolocation:sverige','geolocation:norway',$buffer);
    $woorocks_content = '';
    $doc = new DOMDocument();
    $doc->loadHTML($buffer);
    $selector = new DOMXPath($doc);
    global $current_user;

    $url = 'http://freegeoip.net/json/' . $_SERVER["REMOTE_ADDR"];
    $geoDetails = json_decode(file_get_contents($url));
    $geoCity = preg_replace('/\s+/', '', $geoDetails->city);
    $geoRegion = preg_replace('/\s+/', '', $geoDetails->region_name);
    $geoCountry = preg_replace('/\s+/', '', $geoDetails->country_code);
    //$debugContent = "<h1 style='color:#fff;'>" . $url . "</h1>";

    // GEO Filter does not rely on logged in or not, only GEO information is important here


  foreach($selector->query('//div[contains(@class, "woorocks-geo-city-") and not(contains(@class, "woorocks-geo-city-'.$geoCity.'"))]') as $e ) {
    $e->parentNode->removeChild($e);
  }

  foreach($selector->query('//div[contains(@class, "woorocks-geo-region-") and not(contains(@class, "woorocks-geo-region-'.$geoRegion.'"))]') as $e ) {
    $e->parentNode->removeChild($e);
  }

  foreach($selector->query('//div[contains(@class, "woorocks-geo-country-") and not(contains(@class, "woorocks-geo-country-'.$geoCountry.'"))]') as $e ) {
    $e->parentNode->removeChild($e);
  }

  if ( is_user_logged_in() ) {

    foreach($selector->query('//div[contains(attribute::class, "woorocks-notloggedin")]') as $e ) {
      $e->parentNode->removeChild($e);
    }

    $user_roles = $current_user->roles;
    $role = array_shift($user_roles);
    //$debugContent = "<h1 style='color:#fff;'>" . $role . "</h1>";

    if ($role == 'subscriber') {
      // Delete all other roles content and keep the content targetted to the current logged in
      foreach($selector->query('//div[contains(@class, "woorocks-role-") and not(contains(@class, "woorocks-role-subscriber"))]') as $e ) {
        $e->parentNode->removeChild($e);
      }
    } elseif ($role == 'author') {
      // Delete all other roles content and keep the content targetted to the current logged in
      foreach($selector->query('//div[contains(@class, "woorocks-role-") and not(contains(@class, "woorocks-role-author"))]') as $e ) {
        $e->parentNode->removeChild($e);
      }
    }
    elseif ($role == 'contributor') {
      // Delete all other roles content and keep the content targetted to the current logged in
      foreach($selector->query('//div[contains(@class, "woorocks-role-") and not(contains(@class, "woorocks-role-contributor"))]') as $e ) {
        $e->parentNode->removeChild($e);
      }
    }
    elseif ($role == 'editor') {
      foreach($selector->query('//div[contains(@class, "woorocks-role-") and not(contains(@class, "woorocks-role-editor"))]') as $e ) {
        $e->parentNode->removeChild($e);
      }
    }
    elseif ($role == 'administrator') {
      foreach($selector->query('//div[contains(@class, "woorocks-role-") and not(contains(@class, "woorocks-role-administrator"))]') as $e ) {
        $e->parentNode->removeChild($e);
      }
    }
  }
  else {
    // Not logged in, later GEO stuff here
    foreach($selector->query('//div[contains(attribute::class, "woorocks-loggedin")]') as $e ) {
      $e->parentNode->removeChild($e);
    }
    foreach($selector->query('//div[contains(attribute::class, "woorocks-role:")]') as $e ) {
      $e->parentNode->removeChild($e);
    }

  }


    return $doc->saveHTML($doc) ;//. $debugContent;
}
if (!is_admin() ){
  add_action('wp_loaded', 'wmcfpb_buffer_start');
  add_action('shutdown', 'wmcfpb_buffer_end');
} else {
  remove_action('wp_loaded', 'wmcfpb_buffer_start');
  remove_action('shutdown', 'wmcfpb_buffer_end');
}

/**
* Show in WP Dashboard notice about the plugin is not activated.
*/
function wmcfpb_dashboard_message() {
	$message = esc_html__( 'WOOROCKS :: Magic Content for Siteorigin Pagebuilder message. This plugin requires Siteorigins Pagebuilder to work. Please install that and try this again.', 'woorocks-magic-content' );
	$html_message = sprintf( '<div class="notice notice-error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
