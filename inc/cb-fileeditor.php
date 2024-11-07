<?php

add_action('admin_menu', 'fileed_admin_menu');

function fileed_admin_menu() {
    // Use the add_menu_page function to add a new menu item
    add_menu_page(
        'File Editor',        // Page title
        'Document Editor',                // Menu title
        'manage_options',              // Capability required to access this menu
        'fileed-admin-page',        // Menu slug
        'fileed_admin_page_html',   // Function to display the page content
        'dashicons-admin-generic',     // Icon (optional, can be dashicons or custom URL)
        99                              // Position in the menu order
    );
}

// Function that renders the content of the custom admin page
function fileed_admin_page_html() {
    // Security check to make sure the user has the required permissions
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if CSV download request is made via URL parameter
    if (isset($_GET['action']) && $_GET['action'] === 'fileed_download_csv') {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'fileed_download_csv_action')) {
            die('Security check failed');
        }
        fileed_download_csv();
        return; // Make sure no further HTML is output
    }

    echo '<div class="wrap">';
    echo '<h1>File Editor Admin</h1>';
    echo '<p>Tool for bulk update of file titles, date, categories, etc.</p>';
    echo '<p><strong>DO NOT</strong> modify Doc ID, File ID, or Filename fields in the CSV.<br>';
    echo 'Modify the Title, Category, or Type as needed.</p>';
    
    // LIST doccat and doctype TAXONOMY SLUGS HERE
    echo '<h3>Acceptable doccat and doctype slugs:</h3>';
    echo '<ul>';

    // Get all terms for 'doccat' taxonomy
    $doccat_terms = get_terms(array(
        'taxonomy' => 'doccat',
        'hide_empty' => false,
    ));
    if (!is_wp_error($doccat_terms)) {
        echo '<li><strong>doccat:</strong> ';
        $doccat_slugs = wp_list_pluck($doccat_terms, 'slug');
        echo implode(', ', $doccat_slugs);
        echo '</li>';
    }

    // Get all terms for 'doctype' taxonomy
    $doctype_terms = get_terms(array(
        'taxonomy' => 'doctype',
        'hide_empty' => false,
    ));
    if (!is_wp_error($doctype_terms)) {
        echo '<li><strong>doctype:</strong> ';
        $doctype_slugs = wp_list_pluck($doctype_terms, 'slug');
        echo implode(', ', $doctype_slugs);
        echo '</li>';
    }

    echo '</ul>';

    // DOWNLOAD
    echo '<h2>Download File Data as CSV</h2>';
    echo '<form method="get">';
    echo '<input type="hidden" name="page" value="fileed-admin-page" />';
    echo '<input type="hidden" name="action" value="fileed_download_csv" />';
    echo '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce('fileed_download_csv_action') . '" />';
    echo '<input type="submit" value="Download CSV" class="button button-primary" />';
    echo '</form>';

    // UPLOAD
    echo '<h2>Upload CSV for Update</h2>';
    echo '<form method="post" enctype="multipart/form-data">';
    echo '<input type="hidden" name="fileed_nonce" value="' . wp_create_nonce('fileed_nonce_action') . '" />';
    echo '<input type="file" name="uploaded_csv" accept=".csv" />';
    echo '<input type="submit" name="upload_csv" value="Upload CSV" class="button button-primary" />';
    echo '</form>';

    echo '</div>';

    // Process CSV upload request
    if (isset($_POST['upload_csv'])) {
        if (!isset($_POST['fileed_nonce']) || !wp_verify_nonce($_POST['fileed_nonce'], 'fileed_nonce_action')) {
            die('Security check failed');
        }
        fileed_handle_file_upload();
    }
}

// Function to download WP_Query data as CSV
function fileed_download_csv() {
    // Clean output buffer to prevent unwanted headers or whitespace
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Custom WP_Query to get data (example: getting posts)
    $args = array(
        'post_type' => 'document',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);

    // Prepare CSV headers
    $csv_data = array();
    $csv_data[] = array('Doc ID', 'File ID', 'Date Created', 'Title', 'Filename', 'Category', 'Type');

    // Loop through posts and add to CSV data
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $doccat = '';
            $doctype = '';
            $doccat_terms = get_the_terms(get_the_ID(), 'doccat');

            if (!is_wp_error($doccat_terms) && !empty($doccat_terms)) {
                // Return the slug of the first term in the list
                $doccat = $doccat_terms[0]->slug;
            }
            $doctype_terms = get_the_terms(get_the_ID(), 'doctype');

            if (!is_wp_error($doctype_terms) && !empty($doctype_terms)) {
                // Return the slug of the first term in the list
                $doctype = $doctype_terms[0]->slug;
            }

            $attachment_id = get_field('file', get_the_ID());
            $attachment_filename = ($attachment_id) ? basename(get_attached_file($attachment_id)) : '';

            $csv_data[] = array(get_the_ID(), $attachment_id, get_the_date('d-M-y'), get_the_title(),  $attachment_filename, $doccat, $doctype);
        }
        wp_reset_postdata();
    }

    // Set headers to download file
    if (empty($csv_data)) {
        echo '<div class="error"><p>No data available for download.</p></div>';
        return;
    }

    // Create filename with Zulu time
    $datetime = gmdate('Y-m-d\TH-i-s\Z');
    $filename = 'Aberforth_Files_' . $datetime . '.csv';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Output the CSV data
    $output = fopen('php://output', 'w');
    foreach ($csv_data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);

    // Ensure nothing else is sent
    ob_clean();
    flush();
    exit;
}

// Function to handle CSV file upload and update posts
function fileed_handle_file_upload() {
    if (!empty($_FILES['uploaded_csv']['tmp_name'])) {
        $file = $_FILES['uploaded_csv']['tmp_name'];
        $handle = fopen($file, 'r');

        // Skip the header row
        fgetcsv($handle);

        // Loop through CSV rows
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $doc_id = intval($data[0]);
            $date_created = sanitize_text_field($data[2]);
            $title = sanitize_text_field($data[3]);
            $doccat_slug = sanitize_text_field($data[5]);
            $doctype_slug = sanitize_text_field($data[6]);

            // Update post fields if changed
            $post = get_post($doc_id);
            if ($post) {
                $updated_fields = [];
                $updated_post = array('ID' => $doc_id);

                if (!empty($title) && $post->post_title !== $title) {
                    $updated_post['post_title'] = $title;
                    $updated_fields[] = 'Title';
                }

                // Compare date ignoring time
                $current_date_created = date('d-M-y', strtotime($post->post_date));
                if (!empty($date_created) && $current_date_created !== $date_created) {
                    $updated_post['post_date'] = date('Y-m-d H:i:s', strtotime($date_created));
                    $updated_fields[] = 'Date Created';
                }

                if (!empty($updated_fields)) {
                    $result = wp_update_post($updated_post, true);
                    if (is_wp_error($result)) {
                        echo '<div class="error"><p>Error updating Doc ID ' . $doc_id . ': ' . $result->get_error_message() . '</p></div>';
                    } else {
                        echo '<div class="updated"><p>Updated Doc ID ' . $doc_id . ': ' . implode(', ', $updated_fields) . ' changed.</p></div>';
                    }
                }

                // Update taxonomies if changed
                if (!empty($doccat_slug)) {
                    $doccat_term = get_term_by('slug', $doccat_slug, 'doccat');
                    if ($doccat_term) {
                        $result = wp_set_post_terms($doc_id, [$doccat_term->term_id], 'doccat', false);
                        if (is_wp_error($result)) {
                            echo '<div class="error"><p>Error updating Doc ID ' . $doc_id . ': Category change to ' . $doccat_slug . ' failed.</p></div>';
                        } else {
                            echo '<div class="updated"><p>Updated Doc ID ' . $doc_id . ': Category changed to ' . $doccat_slug . '.</p></div>';
                        }
                    } else {
                        echo '<div class="error"><p>Category ' . $doccat_slug . ' not found for Doc ID ' . $doc_id . '.</p></div>';
                    }
                }

                if (!empty($doctype_slug)) {
                    $doctype_term = get_term_by('slug', $doctype_slug, 'doctype');
                    if ($doctype_term) {
                        $result = wp_set_post_terms($doc_id, [$doctype_term->term_id], 'doctype', false);
                        if (is_wp_error($result)) {
                            echo '<div class="error"><p>Error updating Doc ID ' . $doc_id . ': Type change to ' . $doctype_slug . ' failed.</p></div>';
                        } else {
                            echo '<div class="updated"><p>Updated Doc ID ' . $doc_id . ': Type changed to ' . $doctype_slug . '.</p></div>';
                        }
                    } else {
                        echo '<div class="error"><p>Type ' . $doctype_slug . ' not found for Doc ID ' . $doc_id . '.</p></div>';
                    }
                }
            } else {
                echo '<div class="error"><p>Document with ID ' . $doc_id . ' not found.</p></div>';
            }
        }
        fclose($handle);

        echo '<div class="updated"><p>CSV file processed successfully.</p></div>';
    } else {
        echo '<div class="error"><p>Please upload a valid CSV file.</p></div>';
    }
}

?>
