<?php
/*
Plugin Name: CSE310 Data Analysis
Description: Custom plugin for data analysis inside WP Admin
Version: 1.0
Author: Sergio Bravo
*/

// Prevent direct access (Security Best Practice)
if (!defined('ABSPATH')) {
    exit;
}

define('CSE310_PLUGIN_FILE', __FILE__);
define('CSE310_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once CSE310_PLUGIN_DIR . 'includes/class-cse310-plugin.php';

CSE310_Plugin::init();