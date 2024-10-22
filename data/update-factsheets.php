<?php
// Load WordPress environment
require_once(dirname(__FILE__, 3) . '/wp-load.php');

// Define the CSV file path
$csv_file = dirname(__FILE__) . '/factsheets.csv';

// Initialise counters for reporting
$updated_count = 0;
$failed_rows = [];
$row_number = 1;

// Open the CSV file
if (($handle = fopen($csv_file, 'r')) !== FALSE) {
    // Skip the first row (headers)
    fgetcsv($handle, 1000, ',');
    $row_number++;

    // Loop through each row in the CSV
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        list($dateCreated, $doccat, $doctype, $filename, $title) = $data;

        // Search for the attachment by filename
        $args = [
            'post_type'   => 'attachment',
            'meta_query'  => [
                [
                    'key'     => '_wp_attached_file',
                    'value'   => $filename,
                    'compare' => 'LIKE'
                ]
            ],
            'posts_per_page' => 1
        ];
        $attachments = get_posts($args);

        if ($attachments) {
            $attachment_id = $attachments[0]->ID;

            // Update post date and title
            $update_data = [
                'ID'         => $attachment_id,
                'post_date'  => $dateCreated,
                'post_title' => $title
            ];
            wp_update_post($update_data);

            // Update taxonomy terms
            wp_set_object_terms($attachment_id, $doccat, 'doccat');
            wp_set_object_terms($attachment_id, $doctype, 'doctype');

            echo "Updated attachment ID: $attachment_id (Filename: $filename)<br>";
            $updated_count++;
        } else {
            echo "No attachment found for filename: $filename (Row: $row_number)<br>";
            $failed_rows[] = $row_number;
        }
        $row_number++;
    }

    fclose($handle);

    // Output summary report
    echo "<br><strong>Summary Report:</strong><br>";
    echo "Total Updated: $updated_count<br>";
    if (!empty($failed_rows)) {
        echo "Failed Rows: " . implode(', ', $failed_rows) . "<br>";
    } else {
        echo "No failures encountered.<br>";
    }
} else {
    echo "Could not open the CSV file.";
}
