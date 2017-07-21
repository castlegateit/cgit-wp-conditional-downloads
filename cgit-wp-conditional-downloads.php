<?php

/*

Plugin Name: Castlegate IT WP Conditional Downloads
Plugin URI: https://github.com/castlegateit/cgit-wp-conditional-downloads
Description: Add conditions to protect file downloads
Version: 1.0.3
Author: Castlegate IT
Author URI: https://www.castlegateit.co.uk/
License: MIT

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

define('CGIT_CONDO_PLUGIN', __FILE__);

require_once __DIR__ . '/classes/autoload.php';

$plugin = new \Cgit\Condo\Plugin();

do_action('cgit_condo_loaded');
