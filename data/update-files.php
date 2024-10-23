<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <pre>
<?php

// Load WordPress environment
define('WP_USE_THEMES', false);
require_once(dirname(__FILE__) . '/../../../../wp-load.php');

// Path to CSV file
$csv_file = dirname(__FILE__) . '/fileinfo.csv';

if (!file_exists($csv_file)) {
    echo "CSV file not found!";
    exit;
}

// Open the CSV file
if (($handle = fopen($csv_file, 'r')) !== false) {
    // Skip the header line if there is one
    fgetcsv($handle, 1000, ',');

    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        if ($data[1] != 'afund') {
            continue;
        }
        $attachment_filename = trim($data[3]); // Filename from CSV
        $new_post_title = trim($data[4]); // Post title from CSV
        $new_post_date = trim($data[0]); // Date from CSV

        // Attempt to convert the date string using a specific format
        $date_object = DateTime::createFromFormat('d/m/Y H:i', $new_post_date);
        
        // If DateTime parsing fails, skip the date update
        if ($date_object === false) {
            echo "Invalid date format for filename: $attachment_filename, skipping date update\n";
            $new_post_date = null;
        } else {
            $new_post_date = $date_object->format('Y-m-d H:i:s');
        }

        // Find the attachment by its filename using get_posts
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'meta_query' => array(
                array(
                    'key' => '_wp_attached_file',
                    'value' => $attachment_filename,
                    'compare' => 'LIKE'
                )
            ),
            'posts_per_page' => 1,
            'post_status' => 'any'
        ));

        if (!empty($attachments)) {
            $attachment = $attachments[0];
            $attachment_id = $attachment->ID;

            // Now find the related document post using the attachment ID
            $document_args = array(
                'post_type' => 'document',
                'meta_query' => array(
                    array(
                        'key' => 'file', // ACF field that stores attachment ID
                        'value' => $attachment_id,
                        'compare' => '='
                    )
                ),
                'post_status' => 'any',
                'posts_per_page' => 1,
            );

            $document_posts = get_posts($document_args);

            if (!empty($document_posts)) {
                $document_post = $document_posts[0];
                
                // Update post_title and optionally post_date of the document post
                $updated_args = array(
                    'ID' => $document_post->ID,
                    'post_title' => $new_post_title,
                );
                
                if ($new_post_date !== null) {
                    $updated_args['post_date'] = $new_post_date;
                }

                // Update the post
                wp_update_post($updated_args);

                echo "Updated document post with ID " . $document_post->ID . "\n";
            } else {
                echo "No related document post found for attachment: $attachment_filename\n";
            }
        } else {
            echo "No attachment found with filename: $attachment_filename\n";
        }
    }

    fclose($handle);
} else {
    echo "Unable to open CSV file.";
}
?>
    </pre>
</body>
</html>