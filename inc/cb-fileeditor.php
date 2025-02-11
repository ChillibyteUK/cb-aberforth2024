<?php

add_action('admin_menu', 'fileed_admin_menu');

function fileed_admin_menu()
{
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
function fileed_admin_page_html()
{
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

?>
    <div class="wrap">
        <h1>Document Meta Data Editor</h1>
        <p>This tool is for bulk updating file titles, date, categories, etc.</p>
        <div style="background-color: white; border: 1px solid #2271b1; padding: 1rem; width:max-content;">
            <p>Download the CSV, modify the Title, Category, or Type as needed, then upload to implement the changes.</p>
            <p style="background-color:yellow;display:inline;"><strong>DO NOT</strong> modify Doc ID, File ID, Filename, or Disclaimer fields in the CSV.</p>
        </div>
        <h3>Accepted doccat and doctype slugs</h3>
        <p>The category and type <em>must</em> match the slugs within the taxonomies</p>

    <?php
    // LIST doccat and doctype TAXONOMY SLUGS HERE
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

    // DELETE
    echo '<div style="background-color: white; border: 1px solid #2271b1; padding: 1rem; margin-top: 2rem; width:max-content;">
            <p>Upload a CSV in the same format as that output from the Download containing the rows you wish to remove.</p>
            <p style="background-color:yellow;display:inline;">Only include the items you wish to <strong>REMOVE PERMANENTLY</strong> in the CSV.</p>
        </div>';
    echo '<h2>Upload CSV for DELETION</h2>';
    echo '<form method="post" enctype="multipart/form-data">';
    echo '<input type="hidden" name="fileed_nonce" value="' . wp_create_nonce('fileed_nonce_action') . '" />';
    echo '<input type="file" name="uploaded_delete_csv" accept=".csv" />';
    echo '<br><label style="display: block; margin-top: 10px;">
        <input type="checkbox" id="confirm-delete-checkbox"> CONFIRM DELETION?
      </label>';
    echo '<br><input type="submit" name="upload_delete_csv" value="Upload CSV" class="button button-primary" id="delete-submit-btn" disabled />';
    echo '</form>';

    echo '<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkbox = document.getElementById("confirm-delete-checkbox");
        const submitBtn = document.getElementById("delete-submit-btn");

        checkbox.addEventListener("change", function () {
            submitBtn.disabled = !this.checked;
        });
    });
</script>';

    echo '</div>';

    // Process CSV upload request
    if (isset($_POST['upload_csv'])) {
        if (!isset($_POST['fileed_nonce']) || !wp_verify_nonce($_POST['fileed_nonce'], 'fileed_nonce_action')) {
            die('Security check failed');
        }
        fileed_handle_file_upload();
    }

    // Process CSV DELETE upload request
    if (isset($_POST['upload_delete_csv'])) {
        if (!isset($_POST['fileed_nonce']) || !wp_verify_nonce($_POST['fileed_nonce'], 'fileed_nonce_action')) {
            die('Security check failed');
        }
        fileed_handle_file_upload_delete();
    }
}

// Function to download WP_Query data as CSV
function fileed_download_csv()
{
    // Clean output buffer to prevent unwanted headers or whitespace
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Custom WP_Query to get data (example: getting posts)
    $args = array(
        'post_type'      => 'document',
        'posts_per_page' => -1,
    );
    $query = new WP_Query($args);

    // Prepare CSV headers
    $csv_data = [];
    $headings = ['Doc ID', 'File ID', 'Date Created', 'Title', 'Filename', 'Category', 'Type'];

    // Retrieve all possible disclaimers from the options page
    $disclaimer_headings = [];

    if (have_rows('disclaimers', 'option')) {
        while (have_rows('disclaimers', 'option')) {
            the_row();
            $disclaimer_headings[] = get_sub_field('disclaimer_name');
        }
    }

    // Merge disclaimers into the headers
    $headings = array_merge($headings, $disclaimer_headings);
    $csv_data[] = $headings;

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

            // Fetch selected disclaimers for this document
            $selected_disclaimers = get_field('disclaimers_selection', get_the_ID()) ?? [];

            // Ensure $selected_disclaimers is an array
            if (!is_array($selected_disclaimers)) {
                $selected_disclaimers = [];
            }

            // Create a row with default empty disclaimer columns
            $row = [
                get_the_ID(),
                $attachment_id,
                get_the_date('d-M-y'),
                get_the_title(),
                $attachment_filename,
                $doccat,
                $doctype
            ];

            // Add 'X' if the disclaimer is selected
            foreach ($disclaimer_headings as $disclaimer) {
                $row[] = in_array($disclaimer, $selected_disclaimers) ? 'X' : '';
            }

            $csv_data[] = $row;
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
function fileed_handle_file_upload()
{
    if (!empty($_FILES['uploaded_csv']['tmp_name'])) {
        $file = $_FILES['uploaded_csv']['tmp_name'];
        $handle = fopen($file, 'r');

        // Skip the header row
        fgetcsv($handle);

        // Accumulate output messages
        $output_messages = "";
        $has_updates = false;

        // Loop through CSV rows
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $doc_id = intval($data[0]);

            if (empty($doc_id)) {
                continue;
            }

            $date_created = sanitize_text_field($data[2]);
            $title = sanitize_text_field($data[3]);
            $doccat_slug = sanitize_text_field($data[5]);
            $doctype_slug = sanitize_text_field($data[6]);

            // Update post fields if changed
            $post = get_post($doc_id);
            if ($post) {
                $updated_fields = [];
                $updated_post = array('ID' => $doc_id);

                // Check if title needs updating
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

                // Only update post if there are changes
                if (!empty($updated_fields)) {
                    $result = wp_update_post($updated_post, true);
                    if (is_wp_error($result)) {
                        $output_messages .= '<div class="error"><p>Error updating Doc ID ' . $doc_id . ': ' . $result->get_error_message() . '</p></div>';
                    } else {
                        $output_messages .= '<div class="updated"><p>Updated Doc ID ' . $doc_id . ': ' . implode(', ', $updated_fields) . ' changed.</p></div>';
                        $has_updates = true;
                    }
                }

                // Update taxonomies if changed
                $current_doccat_terms = wp_get_post_terms($doc_id, 'doccat', array('fields' => 'slugs'));
                if (!empty($doccat_slug) && (!in_array($doccat_slug, $current_doccat_terms))) {
                    $doccat_term = get_term_by('slug', $doccat_slug, 'doccat');
                    if ($doccat_term) {
                        $result = wp_set_post_terms($doc_id, [$doccat_term->term_id], 'doccat', false);
                        if (is_wp_error($result)) {
                            $output_messages .= '<div class="error"><p>Error updating Doc ID ' . $doc_id . ': Category change to ' . $doccat_slug . ' failed.</p></div>';
                        } else {
                            $output_messages .= '<div class="updated"><p>Updated Doc ID ' . $doc_id . ': Category changed to ' . $doccat_slug . '.</p></div>';
                            $has_updates = true;
                        }
                    } else {
                        $output_messages .= '<div class="error"><p>Category ' . $doccat_slug . ' not found for Doc ID ' . $doc_id . '.</p></div>';
                    }
                }

                $current_doctype_terms = wp_get_post_terms($doc_id, 'doctype', array('fields' => 'slugs'));
                if (!empty($doctype_slug) && (!in_array($doctype_slug, $current_doctype_terms))) {
                    $doctype_term = get_term_by('slug', $doctype_slug, 'doctype');
                    if ($doctype_term) {
                        $result = wp_set_post_terms($doc_id, [$doctype_term->term_id], 'doctype', false);
                        if (is_wp_error($result)) {
                            $output_messages .= '<div class="error"><p>Error updating Doc ID ' . $doc_id . ': Type change to ' . $doctype_slug . ' failed.</p></div>';
                        } else {
                            $output_messages .= '<div class="updated"><p>Updated Doc ID ' . $doc_id . ': Type changed to ' . $doctype_slug . '.</p></div>';
                            $has_updates = true;
                        }
                    } else {
                        $output_messages .= '<div class="error"><p>Type ' . $doctype_slug . ' not found for Doc ID ' . $doc_id . '.</p></div>';
                    }
                }
            } else {
                $output_messages .= '<div class="error"><p>Document with ID ' . $doc_id . ' not found.</p></div>';
            }
        }
        fclose($handle);

        echo $output_messages;

        echo '<div class="updated"><p>CSV file processed successfully.</p></div>';
    } else {
        echo '<div class="error"><p>Please upload a valid CSV file.</p></div>';
    }
}

// Function to handle CSV file upload and update posts
function fileed_handle_file_upload_delete()
{
    if (!empty($_FILES['uploaded_delete_csv']['tmp_name'])) {
        $file = $_FILES['uploaded_delete_csv']['tmp_name'];
        $handle = fopen($file, 'r');

        // Skip the header row
        fgetcsv($handle);

        // Accumulate output messages
        $output_messages = "";

        // Loop through CSV rows
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $doc_id = intval($data[0]);

            if (empty($doc_id)) {
                continue;
            }

            // Check if post exists before deleting
            if (get_post_status($doc_id)) {
                wp_delete_post($doc_id, true); // Force delete
                $output_messages .= '<div class="updated"><p>Deleted Document (CPT) with ID ' . $doc_id . '.</p></div>';
            } else {
                $output_messages .= '<div class="error"><p>Document ID ' . $doc_id . ' not found.</p></div>';
            }
        }

        fclose($handle);

        echo $output_messages;

        echo '<div class="updated"><p>CSV file processed successfully.</p></div>';
    } else {
        echo '<div class="error"><p>Please upload a valid CSV file.</p></div>';
    }
}

    ?>