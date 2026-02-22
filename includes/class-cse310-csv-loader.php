<?php
/**
 * Load CSV Data
 */

if (!defined('ABSPATH')) {
    exit;
}

class CSE310_CSV_Loader {

    public static function cse310_load_csv_data() {

        $file_path = CSE310_PLUGIN_DIR . 'data/shipments.csv';

        if (!file_exists($file_path)) {
            return [];
        }

        $data = [];
        
        if (($handle = fopen($file_path, 'r')) !== false) {

            $headers = fgetcsv($handle, 1000, ",");

            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $combined = array_combine($headers, $row);
                $data[] = self::normalize_row($combined);
            }

            fclose($handle);
        }

        return $data;
    }

    public static function filter_delivered($data) {

        return array_values(array_filter($data, function($row) {
            return isset($row['status']) && strcasecmp(trim((string) $row['status']), 'Delivered') === 0;
        }));
    }

    public static function sort_by_cost_desc($data) {
        usort($data, function($a, $b) {
            return $b['cost_usd'] <=> $a['cost_usd'];
        });

        return $data;
    }

    private static function normalize_row($row) {

        return [
            'shipment_id'     => self::normalize_text($row['shipment_id'] ?? ''),
            'date'            => self::normalize_text($row['date'] ?? ''),
            'origin_city'     => self::normalize_text($row['origin_city'] ?? ''),
            'destination_city'=> self::normalize_text($row['destination_city'] ?? ''),
            'container_type'  => self::normalize_text($row['container_type'] ?? ''),
            'weight_tons'     => self::normalize_float($row['weight_tons'] ?? null),
            'distance_km'     => self::normalize_float($row['distance_km'] ?? null),
            'cost_usd'        => self::normalize_float($row['cost_usd'] ?? null),
            'status'          => self::normalize_text($row['status'] ?? ''),
            'carrier'         => self::normalize_text($row['carrier'] ?? ''),
        ];
    }

    private static function normalize_text($value) {

        return trim((string) $value);
    }

    private static function normalize_float($value) {

        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);

        if ($normalized === '') {
            return null;
        }

        $normalized = str_replace(' ', '', $normalized);

        if (strpos($normalized, ',') !== false && strpos($normalized, '.') !== false) {
            $normalized = str_replace(',', '', $normalized);
        } else {
            $normalized = str_replace(',', '.', $normalized);
        }

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    //¿Cuál es el total cost y el average cost de los envíos Delivered?
    public static function calculate_total_cost($data) {

        return array_reduce($data, function($carry, $row) {
            return $carry + $row['cost_usd'];
        }, 0);
    }

    public static function calculate_average_cost($data) {

        $count = count($data);

        if ($count === 0) {
            return 0;
        }

        $total = self::calculate_total_cost($data);

        return $total / $count;
    }

    //GROUP BY destination_city
    public static function group_by_destination_city($data) {

        $grouped = [];

        foreach ($data as $row) {
            $city = $row['destination_city'] ?? 'Unknown';
            
            if (!isset($grouped[$city])) {
                $grouped[$city] = [];
            }
            $grouped[$city][] = $row;
        }

        return $grouped;
    }

    public static function group_by_destination($data) {

        $grouped = [];

        foreach ($data as $row) {
            $city = $row['destination_city'];

            if (!isset($grouped[$city])) {
                $grouped[$city] = [
                    'destination_city' => $city,
                    'total_cost' => 0,
                    'count' => 0
                ];
            }

            $grouped[$city]['total_cost'] += $row['cost_usd'];
            $grouped[$city]['count']++;
        }

        return array_values($grouped);
    }

    //Ordena los destinos por total cost descendente
    public static function sort_destinations_by_total_cost($grouped) {
        usort($grouped, function($a, $b) {
            return $b['total_cost'] <=> $a['total_cost'];
        });

        return $grouped;
    }
}
