<?php

if (!defined('ABSPATH')) {
    exit;
}

class CSE310_Plugin {
    public static function init() {
        require_once CSE310_PLUGIN_DIR . 'admin/class-cse310-admin-menu.php';
        require_once CSE310_PLUGIN_DIR . 'includes/class-cse310-csv-loader.php';
        
        CSE310_Admin_Menu::init();
    }
}
