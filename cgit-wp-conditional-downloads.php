<?php

/*

Plugin Name: Castlegate IT WP Conditional Downloads
Plugin URI: https://github.com/castlegateit/cgit-wp-conditional-downloads
Description: Add conditions to protect file downloads
Version: 1.0.2
Author: Castlegate IT
Author URI: https://www.castlegateit.co.uk/
License: MIT

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

// Constants
define('CGIT_CONDO_PLUGIN', __FILE__);

// Load classes and functions
require_once __DIR__ . '/classes/autoload.php';

// Initialization
$condo = new \Cgit\Condo\Plugin();
