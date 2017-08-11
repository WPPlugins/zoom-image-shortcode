<?php
/*
Plugin Name: Zoomify embed for WP
Plugin URI: https://wordpress.org/plugins/zoom-image-shortcode/
Description: This plugin allows you to embed zoomify images on any page or post using a shortcode
Version: 1.1.2.3
Author: Sander de Wijs
Author URI: https://www.degrinthorst.nl/
License: GPLv2
*/

// Exit when called directly
defined( 'ABSPATH' ) or die( 'Nope!' );

define('GH_ZOOMIFY_BASE', plugin_dir_url( __FILE__ ));

/**
 * code for header css and JS
 */
function gh_zoomify_scripts_styles()
{
    wp_enqueue_script('zoomify-js', GH_ZOOMIFY_BASE . 'assets/js/ZoomifyImageViewerExpress-min.js', '', false, false);
    wp_enqueue_style('gh-zoomify', GH_ZOOMIFY_BASE . 'assets/css/zoomify-styles.css');
}
add_action('wp_enqueue_scripts', 'gh_zoomify_scripts_styles');

/**
 * Add support for uploading zif files
 */
function add_zif_support($mime_types)
{
    $mime_types['zif'] = 'image/zif';
    return $mime_types;
}
add_filter('upload_mimes', 'add_zif_support');

/**
 * @param $linkToFile
 * @param $skin
 * @param $zoomLevel
 * @return string
 */
function zoomify_container_js($linkToFile, $skin, $zoomLevel)
{
    $zSkinPath = GH_ZOOMIFY_BASE . 'assets/Skins/' . ucfirst($skin) . '/';
    return '<script type="text/javascript">Z.showImage("zoomifyContainer", "' . $linkToFile . '", "zSkinPath=' . $zSkinPath . '&zInitialZoom=' . $zoomLevel . '");</script>';
}


/**
 * code for shortcode
 * @param $atts
 * @return string
 */
function gh_zoomify_shortcode($atts)
{
    $a = shortcode_atts( array(
        'file' => 'filename',
        'skin' => 'Default',
        'zoomlevel' =>  null,
    ), $atts );


    // Use the attachment link in the Zoomify JS
    $output = zoomify_container_js($a['file'], $a['skin'], $a['zoomlevel']);
    $output .= '<div id="zoomifyContainer"></div>';

    return $output;
}

add_shortcode('zoomify', 'gh_zoomify_shortcode');