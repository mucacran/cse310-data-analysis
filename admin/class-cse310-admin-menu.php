<?php

if (!defined('ABSPATH')) {
    exit;
}

class CSE310_Admin_Menu {
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
    }

    public static function enqueue_assets($hook_suffix) {
        if ($hook_suffix !== 'toplevel_page_cse310-data-analysis') {
            return;
        }

        wp_enqueue_style(
            'cse310-admin-dashboard',
            plugin_dir_url(CSE310_PLUGIN_FILE) . 'admin/assets/dashboard.css',
            array(),
            '1.0.0'
        );
    }

    public static function add_admin_menu() {
        add_menu_page(
            'CSE310 Data Analysis',
            'Data Analysis',
            'manage_options',
            'cse310-data-analysis',
            array(__CLASS__, 'admin_page'),
            'dashicons-chart-bar',
            25
        );
    }

    public static function admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $shipments = CSE310_CSV_Loader::cse310_load_csv_data();
        $total_records = count($shipments);

        $selected_origin = isset($_GET['origin_city']) ? sanitize_text_field($_GET['origin_city']) : '';
        $selected_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

        /******************************************
         * Apply filters based on user selection  *
         ******************************************/
        $filtered = $shipments;

        if (!empty($selected_origin)) {
            $filtered = array_filter($filtered, function($row) use ($selected_origin) {
                return isset($row['origin_city']) && $row['origin_city'] === $selected_origin;
            });
        }

        if (!empty($selected_status)) {
            $filtered = array_filter($filtered, function($row) use ($selected_status) {
                return isset($row['status']) && $row['status'] === $selected_status;
            });
        }

        /************************************************************************
         * Reindex the filtered array to ensure proper indexing after filtering *
         ************************************************************************/
        $filtered = array_values($filtered);

        $analysis_status = !empty($selected_status) ? $selected_status : 'Delivered';
        $analysis_data = !empty($selected_status)
            ? $filtered
            : CSE310_CSV_Loader::filter_delivered($filtered);

        $total_delivered = count($analysis_data);
        $sorted = CSE310_CSV_Loader::sort_by_cost_desc($analysis_data);

        $total_cost = CSE310_CSV_Loader::calculate_total_cost($analysis_data);
        $average_cost = CSE310_CSV_Loader::calculate_average_cost($analysis_data);

        $grouped = CSE310_CSV_Loader::group_by_destination($analysis_data);
        $sorted_destinations = CSE310_CSV_Loader::sort_destinations_by_total_cost($grouped);
        $top_destinations = array_slice($sorted_destinations, 0, 3);
        $top_shipments = array_slice($sorted, 0, 5);
        
        require CSE310_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
}
