<?php

if (!defined('ABSPATH')) {
    exit;
}

?>
<!-- This file is responsible for rendering the dashboard view in the WordPress admin area. -->
 <form method="GET">
    <input type="hidden" name="page" value="cse310-data-analysis">

    <label><strong>Origin City:</strong></label>
    <select name="origin_city">
        <option value="">All</option>
        <?php
        $origins = array_unique(array_column($shipments, 'origin_city'));
        foreach ($origins as $origin):
        ?>
            <option value="<?php echo esc_attr($origin); ?>"
                <?php selected($selected_origin, $origin); ?>>
                <?php echo esc_html($origin); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label style="margin-left:20px;"><strong>Status:</strong></label>
    <select name="status">
        <option value="">All</option>
        <option value="Delivered" <?php selected($selected_status, 'Delivered'); ?>>Delivered</option>
        <option value="In Transit" <?php selected($selected_status, 'In Transit'); ?>>In Transit</option>
        <option value="Cancelled" <?php selected($selected_status, 'Cancelled'); ?>>Cancelled</option>
    </select>

    <input type="submit" class="button button-primary" value="Apply Filter">
</form>

<hr>
<!-- The dashboard displays key metrics and rankings based on the CSV data loaded and processed by the CSE310_CSV_Loader class. -->



<div class="wrap">
    <h1>CSE310 Data Analysis Dashboard</h1>
    <p>Resumen de envíos filtrados con métricas y rankings clave.</p>

    <div class="cse310-grid">
        <div class="card cse310-card">
            <p class="cse310-label">Total Records</p>
            <p class="cse310-value"><?php echo esc_html(number_format_i18n($total_records)); ?></p>
        </div>
        <div class="card cse310-card">
            <p class="cse310-label"><?php echo esc_html($analysis_status); ?> Records</p>
            <p class="cse310-value"><?php echo esc_html(number_format_i18n($total_delivered)); ?></p>
        </div>
        <div class="card cse310-card">
            <p class="cse310-label">Total <?php echo esc_html($analysis_status); ?> Cost (USD)</p>
            <p class="cse310-value"><?php echo esc_html('$' . number_format_i18n((float) $total_cost, 2)); ?></p>
        </div>
        <div class="card cse310-card">
            <p class="cse310-label">Average <?php echo esc_html($analysis_status); ?> Cost (USD)</p>
            <p class="cse310-value"><?php echo esc_html('$' . number_format_i18n((float) $average_cost, 2)); ?></p>
        </div>
    </div>

    <div class="cse310-section">
        <h2>Top 5 Most Expensive <?php echo esc_html($analysis_status); ?> Shipments</h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Shipment ID</th>
                    <th>Date</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Carrier</th>
                    <th>Status</th>
                    <th style="text-align:right;">Cost (USD)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($top_shipments)) : ?>
                    <?php foreach ($top_shipments as $row) : ?>
                        <tr>
                            <td><?php echo esc_html($row['shipment_id']); ?></td>
                            <td><?php echo esc_html($row['date']); ?></td>
                            <td><?php echo esc_html($row['origin_city']); ?></td>
                            <td><?php echo esc_html($row['destination_city']); ?></td>
                            <td><?php echo esc_html($row['carrier']); ?></td>
                            <td><?php echo esc_html($row['status']); ?></td>
                            <td style="text-align:right;"><?php echo esc_html(number_format_i18n((float) $row['cost_usd'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No shipment data available for current filters.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="cse310-section">
        <h2>Top 3 Destinations by Total Cost</h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Destination</th>
                    <th style="text-align:right;">Total Cost (USD)</th>
                    <th style="text-align:right;">Shipments</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($top_destinations)) : ?>
                    <?php foreach ($top_destinations as $destination) : ?>
                        <tr>
                            <td><?php echo esc_html($destination['destination_city']); ?></td>
                            <td style="text-align:right;"><?php echo esc_html(number_format_i18n((float) $destination['total_cost'], 2)); ?></td>
                            <td style="text-align:right;"><?php echo esc_html(number_format_i18n((int) $destination['count'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No grouped destination data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
