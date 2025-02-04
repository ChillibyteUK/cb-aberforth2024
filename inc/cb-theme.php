<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

require_once CB_THEME_DIR . '/inc/cb-utility.php';
require_once CB_THEME_DIR . '/inc/cb-blocks.php';
require_once CB_THEME_DIR . '/inc/cb-fileeditor.php';
// require_once CB_THEME_DIR . '/inc/cb-news.php';
// require_once CB_THEME_DIR . '/inc/cb-careers.php';


// Remove unwanted SVG filter injection WP
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

define('CSV_HOST', 'https://ap01.chillihosting.co.uk');

define('CSV_FILES', [
    '/sftp/uploads/csv/Dividends/AFUND_Dividends.csv',
    '/sftp/uploads/csv/Dividends/AGVIT_Dividends.csv',
    '/sftp/uploads/csv/Dividends/ASCOT_Dividends.csv',
    '/sftp/uploads/csv/Dividends/ASLIT_Dividends.csv',
    '/sftp/uploads/csv/IndustryWeights/AFUND_IndustryWeights.csv',
    '/sftp/uploads/csv/IndustryWeights/AGVIT_IndustryWeights.csv',
    '/sftp/uploads/csv/IndustryWeights/ASCOT_IndustryWeights.csv',
    '/sftp/uploads/csv/IndustryWeights/ASLIT_IndustryWeights.csv',
    '/sftp/uploads/csv/Performance/AFUND_CompoundPerformance.csv',
    '/sftp/uploads/csv/Performance/AFUND_CumulativePerformance.csv',
    '/sftp/uploads/csv/Performance/AFUND_DiscretePerformance.csv',
    '/sftp/uploads/csv/Performance/AGVIT_CompoundPerformance.csv',
    '/sftp/uploads/csv/Performance/AGVIT_CumulativePerformance.csv',
    '/sftp/uploads/csv/Performance/AGVIT_DiscretePerformance.csv',
    '/sftp/uploads/csv/Performance/ASCOT_CompoundPerformance.csv',
    '/sftp/uploads/csv/Performance/ASCOT_CumulativePerformance.csv',
    '/sftp/uploads/csv/Performance/ASCOT_DiscretePerformance.csv',
    '/sftp/uploads/csv/Performance/ASLIT_CompoundPerformance.csv',
    '/sftp/uploads/csv/Performance/ASLIT_CumulativePerformance.csv',
    '/sftp/uploads/csv/Performance/ASLIT_DiscretePerformance.csv',
    '/sftp/uploads/csv/PortfolioHoldings/AFUND_PortfolioHoldings.csv',
    '/sftp/uploads/csv/PortfolioHoldings/AGVIT_PortfolioHoldings.csv',
    '/sftp/uploads/csv/PortfolioHoldings/ASCOT_PortfolioHoldings.csv',
    '/sftp/uploads/csv/PortfolioHoldings/ASLIT_PortfolioHoldings.csv',
]);

// Remove comment-reply.min.js from footer
function remove_comment_reply_header_hook()
{
    wp_deregister_script('comment-reply');
}
add_action('init', 'remove_comment_reply_header_hook');

add_action('admin_menu', 'remove_comments_menu');
function remove_comments_menu()
{
    remove_menu_page('edit-comments.php');
}

add_filter('theme_page_templates', 'child_theme_remove_page_template');
function child_theme_remove_page_template($page_templates)
{
    // unset($page_templates['page-templates/blank.php'],$page_templates['page-templates/empty.php'], $page_templates['page-templates/fullwidthpage.php'], $page_templates['page-templates/left-sidebarpage.php'], $page_templates['page-templates/right-sidebarpage.php'], $page_templates['page-templates/both-sidebarspage.php']);
    unset($page_templates['page-templates/blank.php'], $page_templates['page-templates/empty.php'], $page_templates['page-templates/left-sidebarpage.php'], $page_templates['page-templates/right-sidebarpage.php'], $page_templates['page-templates/both-sidebarspage.php']);
    return $page_templates;
}
add_action('after_setup_theme', 'remove_understrap_post_formats', 11);
function remove_understrap_post_formats()
{
    remove_theme_support('post-formats', array('aside', 'image', 'video', 'quote', 'link'));
}

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
            'page_title'     => 'Site-Wide Settings',
            'menu_title'    => 'Site-Wide Settings',
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
        )
    );
}

function widgets_init()
{

    register_nav_menus(array(
        'primary_nav' => __('Primary Nav', 'cb-aberforth2024'),
        'footer_menu1' => __('Footer Nav', 'cb-aberforth2024'),
    ));

    unregister_sidebar('hero');
    unregister_sidebar('herocanvas');
    unregister_sidebar('statichero');
    unregister_sidebar('left-sidebar');
    unregister_sidebar('right-sidebar');
    unregister_sidebar('footerfull');
    unregister_nav_menu('primary');

    add_theme_support('disable-custom-colors');
    add_theme_support(
        'editor-color-palette',
        array(
            array(
                'name'  => 'Dark',
                'slug'  => 'dark',
                'color' => '#333333',
            ),
            array(
                'name'  => 'Light',
                'slug'  => 'light',
                'color' => '#f9f9f9',
            ),
            array(
                'name'  => 'Grey 400',
                'slug'  => 'grey-400',
                'color' => '#666666',
            ),
            array(
                'name'  => 'Grey 200',
                'slug'  => 'grey-200',
                'color' => '#cccccc',
            ),
            array(
                'name'  => 'Yellow 400',
                'slug'  => 'yellow-400',
                'color' => '#daa807',
            ),
            array(
                'name'  => 'Blue 900',
                'slug'  => 'blue-900',
                'color' => '#202945',
            ),
            array(
                'name'  => 'Orange 400',
                'slug'  => 'orange-400',
                'color' => '#ec7c36',
            ),
            array(
                'name'  => 'Green 400',
                'slug'  => 'green-400',
                'color' => '#1a3c34',
            ),
            array(
                'name'  => 'Blue 700',
                'slug'  => 'blue-700',
                'color' => '#244062',
            ),
            array(
                'name'  => 'Blue 600',
                'slug'  => 'blue-600',
                'color' => '#7ba6db',
            ),
            array(
                'name'  => 'Blue 400',
                'slug'  => 'blue-400',
                'color' => '#c6d9f1',
            ),
            array(
                'name'  => 'Blue 200',
                'slug'  => 'blue-200',
                'color' => '#ebf2fa',
            ),
        )
    );
}
add_action('widgets_init', 'widgets_init', 11);


remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

//Custom Dashboard Widget
/*
add_action('wp_dashboard_setup', 'register_cb_dashboard_widget');
function register_cb_dashboard_widget()
{
    wp_add_dashboard_widget(
        'cb_dashboard_widget',
        'Chillibyte',
        'cb_dashboard_widget_display'
    );
}

function cb_dashboard_widget_display()
{
?>
    <div style="display: flex; align-items: center; justify-content: space-around;">
        <img style="width: 50%;"
            src="<?= get_stylesheet_directory_uri() . '/img/cb-full.jpg'; ?>">
        <a class="button button-primary" target="_blank" rel="noopener nofollow noreferrer"
            href="mailto:hello@www.chillibyte.co.uk/">Contact</a>
    </div>
    <div>
        <p><strong>Thanks for choosing Chillibyte!</strong></p>
        <hr>
        <p>Got a problem with your site, or want to make some changes & need us to take a look for you?</p>
        <p>Use the link above to get in touch and we'll get back to you ASAP.</p>
    </div>
<?php
}
*/

// add_filter('wpseo_breadcrumb_links', function( $links ) {
//     global $post;
//     if ( is_singular( 'post' ) ) {
//         $t = get_the_category($post->ID);
//         $breadcrumb[] = array(
//             'url' => '/guides/',
//             'text' => 'Guides',
//         );

//         array_splice( $links, 1, -2, $breadcrumb );
//     }
//     return $links;
// }
// );

// remove discussion metabox
// function cc_gutenberg_register_files()
// {
//     // script file
//     wp_register_script(
//         'cc-block-script',
//         get_stylesheet_directory_uri() . '/js/block-script.js', // adjust the path to the JS file
//         array('wp-blocks', 'wp-edit-post')
//     );
//     // register block editor script
//     register_block_type('cc/ma-block-files', array(
//         'editor_script' => 'cc-block-script'
//     ));
// }
// add_action('init', 'cc_gutenberg_register_files');

function understrap_all_excerpts_get_more_link($post_excerpt)
{
    if (is_admin() || ! get_the_ID()) {
        return $post_excerpt;
    }
    return $post_excerpt;
}

//* Remove Yoast SEO breadcrumbs from Revelanssi's search results
add_filter('the_content', 'wpdocs_remove_shortcode_from_index');
function wpdocs_remove_shortcode_from_index($content)
{
    if (is_search()) {
        $content = strip_shortcodes($content);
    }
    return $content;
}

// GF really is pants.
/**
 * Change submit from input to button
 *
 * Do not use example provided by Gravity Forms as it strips out the button attributes including onClick
 */
function wd_gf_update_submit_button($button_input, $form)
{
    //save attribute string to $button_match[1]
    preg_match("/<input([^\/>]*)(\s\/)*>/", $button_input, $button_match);

    //remove value attribute (since we aren't using an input)
    $button_atts = str_replace("value='" . $form['button']['text'] . "' ", "", $button_match[1]);

    // create the button element with the button text inside the button element instead of set as the value
    return '<button ' . $button_atts . '><span>' . $form['button']['text'] . '</span></button>';
}
add_filter('gform_submit_button', 'wd_gf_update_submit_button', 10, 2);


function cb_theme_enqueue()
{
    $the_theme = wp_get_theme();
    // wp_enqueue_style('lightbox-stylesheet', get_stylesheet_directory_uri() . '/css/lightbox.min.css', array(), $the_theme->get('Version'));
    // wp_enqueue_script('lightbox-scripts', get_stylesheet_directory_uri() . '/js/lightbox-plus-jquery.min.js', array(), $the_theme->get('Version'), true);
    // wp_enqueue_script('lightbox-scripts', get_stylesheet_directory_uri() . '/js/lightbox.min.js', array(), $the_theme->get('Version'), true);
    // wp_enqueue_style('aos-style', "https://unpkg.com/aos@2.3.1/dist/aos.css", array());
    // wp_enqueue_script('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), null, true);
    wp_deregister_script('jquery');
    // wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.3.min.js', array(), null, true);
    // wp_enqueue_script('parallax', get_stylesheet_directory_uri() . '/js/parallax.min.js', array('jquery'), null, true);

}
add_action('wp_enqueue_scripts', 'cb_theme_enqueue');


function add_custom_menu_item($items, $args)
{
    if ($args->theme_location == 'primary_nav') {
        $new_item = '<li class="menu-item menu-item-type-post_tyep menu-item-object-page nav-item"><a href="' . esc_url(home_url('/search/')) . '" class="nav-link" title="Search"><span class="icon-search"></span></a></li>';
        $items .= $new_item;
    }

    return $items;
}
add_filter('wp_nav_menu_items', 'add_custom_menu_item', 10, 2);


// pricing and feed data jobs
// Add custom interval for cron jobs
function custom_cron_schedule($schedules)
{
    // Pricing Data
    if (!isset($schedules["2min"])) {
        $schedules["2min"] = array(
            'interval' => 120, // 2 minutes in seconds
            'display'  => __('Every 2 Minutes')
        );
    }
    // Feed Data
    if (!isset($schedules["6hours"])) {
        $schedules["6hours"] = array(
            'interval' => 6 * 3600, // 6 hours in seconds
            'display'  => __('Every 6 Hours')
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_schedule');

// Schedule the pricing event if it's not already scheduled
function schedule_pricing_check()
{
    if (!wp_next_scheduled('check_pricing_data')) {
        wp_schedule_event(time(), '2min', 'check_pricing_data');
    }
}
add_action('wp', 'schedule_pricing_check');

// Schedule the feed event if it's not already scheduled
function schedule_feed_download()
{
    if (!wp_next_scheduled('download_feed_files')) {
        wp_schedule_event(time(), '6hours', 'download_feed_files');
    }
}
add_action('wp', 'schedule_feed_download');


// Fetch data from both Pricing URLs and update respective options
function fetch_and_update_pricing_data()
{
    // Feed URLs
    $ascot_feed = get_field('ascot_feed_url', 'option') ?? 'https://irs.tools.investis.com/clients/uk/aberforth/xml/xml.aspx';
    $agvit_feed = get_field('agvit_feed_url', 'option') ?? 'https://irs.tools.investis.com/Clients/uk/aberforth_geared_value/XML/xml.aspx';
    $feeds = [
        'ascot_pricing_data' => $ascot_feed,
        'agvit_pricing_data' => $agvit_feed
    ];

    // Loop through each feed and fetch the data
    foreach ($feeds as $option_name => $url) {
        // Fetch the data from the URL
        $response = wp_remote_get($url);
        $current_time = current_time('Y-m-d H:i:s');

        // Check if the request was successful
        if (is_wp_error($response)) {
            // Log or handle the error (optional)
            error_log('Failed to fetch ' . $option_name . ': ' . $response->get_error_message());
            update_option($option_name . '_last_failure', $current_time);
            continue; // Skip this feed if the request fails
        }

        // Check if the response code is 200 (OK)
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code != 200) {
            // Log or handle the error
            error_log('Failed to fetch ' . $option_name . ': HTTP ' . $response_code);
            update_option($option_name . '_last_failure', $current_time);
            continue; // Skip this feed if the response is not OK
        }

        // Retrieve the body content (the XML data)
        $data = wp_remote_retrieve_body($response);

        // Store the data in WordPress options
        update_option($option_name, $data); // Store XML in the respective option
        update_option($option_name . '_last_success', $current_time);
    }
}
add_action('check_pricing_data', 'fetch_and_update_pricing_data');


function display_pricing_data_status()
{
    // Get the latest data from WordPress options
    $ascot_data = get_option('ascot_pricing_data');
    $agvit_data = get_option('agvit_pricing_data');
    $ascot_last_success = get_option('ascot_pricing_data_last_success');
    $agvit_last_success = get_option('agvit_pricing_data_last_success');
    $ascot_last_failure = get_option('ascot_pricing_data_last_failure');
    $agvit_last_failure = get_option('agvit_pricing_data_last_failure');

    // Initialise the output variable
    $output = '<div class="pricing-data-status mb-5">';

    // Check and display Ascot data
    if ($ascot_data) {
        $output .= '<h3>ASCOT Pricing Data:</h3>';
        $output .= '<div class="fs-300 mb-4 bg-light p-2"><code>' . esc_html($ascot_data) . '</code></div>';
        $output .= '<p>Last successful update: ' . ($ascot_last_success ? esc_html($ascot_last_success) : 'N/A') . '</p>';
    } else {
        $output .= '<h3>Ascot Pricing Data:</h3>';
        $output .= '<p style="color: red;">Failed to fetch the Ascot pricing data.</p>';
        $output .= '<p>Last failure: ' . ($ascot_last_failure ? esc_html($ascot_last_failure) : 'N/A') . '</p>';
    }

    // Check and display AGVIT data
    if ($agvit_data) {
        $output .= '<h3 class="mt-5">AGVIT Pricing Data:</h3>';
        $output .= '<div class="fs-300 mb-4 bg-light p-2"><code>' . esc_html($agvit_data) . '</code></div>';
        $output .= '<p>Last successful update: ' . ($agvit_last_success ? esc_html($agvit_last_success) : 'N/A') . '</p>';
    } else {
        $output .= '<h3>AGVIT Pricing Data:</h3>';
        $output .= '<p style="color: red;">Failed to fetch the AGVIT pricing data.</p>';
        $output .= '<p>Last failure: ' . ($agvit_last_failure ? esc_html($agvit_last_failure) : 'N/A') . '</p>';
    }

    $output .= '</div>';
    $output .= <<<EOT
<div class="mb-5">
<h2>CSV Data Files Feed</h2>
EOT;

    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/feed/';
    $files = scandir($file_path);

    // Filter out '.' and '..' to only include actual files/directories
    $files = array_filter($files, function ($file) use ($file_path) {
        return is_file($file_path . $file); // Include only files
    });
    if (!empty($files)) {
        $output .= <<<EOT
<table class="table table-sm fs-300 mb-4">
    <thead>
        <tr>
            <th>File Name</th>
            <th>Local Size</th>
            <th>Remote Size</th>
            <th>Local Date</th>
            <th>Remote Date</th>
        </tr>
    </thead>
    <tbody>
EOT;
        foreach ($files as $file) {

            $remote_file = null;
            foreach (CSV_FILES as $file_with_path) {
                if (basename($file_with_path) === $file) {
                    $remote_file = $file_with_path;
                    break;
                }
            }

            $response = wp_remote_get(CSV_HOST . $remote_file , array('method' => 'HEAD'));
            if (is_wp_error($response)) {
                echo 'Error fetching remote file metadata: ' . $response->get_error_message();
                return;
            }
            $headers = wp_remote_retrieve_headers($response);
            if (isset($headers['content-length'])) {
                $remote_size = intval($headers['content-length']); // remote size in bytes
                $remote_size = number_format($remote_size / 1024, 2) . ' KB'; // Convert to KB
            } else {
                $remote_size = 'Unknown';
            }
            
            if (isset($headers['last-modified'])) {
                $remote_modification_time = strtotime($headers['last-modified']); // Convert to Unix timestamp
                $remote_date = date('Y-m-d H:i:s', $remote_modification_time); // Format the modification date
            } else {
                $remote_date = 'Unknown';
            }
            

            $file_full_path = $file_path . $file;

            $file_modification_time = filemtime($file_full_path);
            $local_date = date('Y-m-d H:i:s', $file_modification_time);

            $local_size = filesize($file_full_path);
            $local_size = number_format($local_size / 1024, 2) . ' KB';

            $size_mismatch = $remote_size == $local_size ? '' : ' class="table-warning"';
            $size_msg = $remote_size == $local_size ? '' : 'title="Local size is different to remote size - Run CSV download"';

            $local_old = (time() - $file_modification_time) > (6 * 60 * 60 + 10 * 60); // 6h10m (7 * 60 * 60);
            $local_old = $local_old ? ' class="table-warning"' : '';
            $local_icon = $local_old ? '<i class="far fa-clock"></i>&nbsp;' : '';
            $local_msg = $local_old ? 'title="Local file is too old - Run CSV download"' : '';

            $remote_old = (time() - $remote_modification_time) > (6 * 60 * 60 + 10 * 60); // 6h10m (12 * 60 * 60);
            $remote_old = $remote_old ? ' class="table-warning"' : '';
            $remote_icon = $remote_old ? ' <i class="far fa-clock"></i>&nbsp;' : '';
            $remote_msg = $remote_old ? 'title="Remote file is too old - Check sFTP job"' : '';

            $output .= '<tr>';
            $output .= "<td title='{$file_full_path}'>{$file}</td>";
            $output .= "<td {$size_mismatch} {$size_msg}>{$local_size}</td>";
            $output .= "<td {$size_mismatch} {$size_msg}>{$remote_size}</td>";
            $output .= "<td {$local_old} {$local_msg}>{$local_icon}{$local_date}</td>";
            $output .= "<td {$remote_old} {$remote_msg}>{$remote_icon}{$remote_date}</td>";
            $output .= '</tr>';
        }
        $output .= "</tbody></table>";
    } else {
        $output .= "No files found in the directory.";
    }

    $output .= <<<EOT
    <button id="triggerButton" class="button">Run Data CSV Download Now</button>
    <div id="outputDiv" class="mt-4"></div>
</div>
<script>
document.getElementById('triggerButton').addEventListener('click', function () {
    const outputDiv = document.getElementById('outputDiv');
    outputDiv.textContent = 'Loading...'; // Display a loading message while fetching

    fetch('/?trigger_feed_download=run')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            outputDiv.textContent = data; // Display the returned string in the div
        })
        .catch(error => {
            outputDiv.textContent = 'An error occurred: ' + error.message; // Display error message
        });
});
</script>
EOT;

    return $output;
}
add_shortcode('pricing_data_status', 'display_pricing_data_status');

// CSV Feed
function fetch_and_save_feed_files()
{

    $urls = CSV_FILES;

    foreach ($urls as $url) {
        $response = wp_remote_get(CSV_HOST . $url);

        if (is_wp_error($response)) {
            error_log("Failed to download $url: " . $response->get_error_message());
            continue;
        }

        $file_content = wp_remote_retrieve_body($response);
        if (empty($file_content)) {
            error_log("Empty file or error retrieving content from $url");
            continue;
        }

        $file_name = basename($url);
        // $upload_dir = wp_upload_dir();
        // $file_path = $upload_dir['basedir'] . '/feed/' . $file_name;

        // if (!file_exists($upload_dir['basedir'] . '/feed')) {
        //     wp_mkdir_p($upload_dir['basedir'] . '/feed');
        // }
        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/feed/' . $file_name;

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/feed')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/feed', 0755, true);
        }

        file_put_contents($file_path, $file_content);
    }
}
add_action('download_feed_files', 'fetch_and_save_feed_files');


// manual trigger
// http://aberforth.local/?trigger_feed_download=run
function trigger_feed_download()
{
    if (isset($_GET['trigger_feed_download']) && $_GET['trigger_feed_download'] == 'run') {
        fetch_and_save_feed_files();
        echo 'Feed files have been fetched and saved.';
        exit;
    }
}
add_action('init', 'trigger_feed_download');

// DOCUMENT LIBRARY TAXONOMY DOMINE

// Global flag to prevent recursive delete
$delete_in_progress = false;

// 1. Create Document CPT when a PDF is uploaded and attach the PDF to the CPT
function create_document_cpt_on_pdf_upload($post_id)
{
    // Get the uploaded file's post object
    $attachment = get_post($post_id);

    // Check if the uploaded file is a PDF
    if ($attachment->post_mime_type === 'application/pdf') {
        // Get the file title to use as the CPT post title
        $document_title = $attachment->post_title;

        // Create a new post in your custom post type
        $document_args = array(
            'post_title'    => $document_title,
            'post_content'  => '',  // Leave content empty, you can always add more details later
            'post_status'   => 'publish',
            'post_type'     => 'document',  // Replace 'document' with your CPT slug if different
        );

        // Insert the new CPT post
        $document_post_id = wp_insert_post($document_args);

        if ($document_post_id) {
            // Attach the media to the newly created CPT entry
            wp_update_post(array(
                'ID' => $post_id,
                'post_parent' => $document_post_id
            ));

            // Use ACF to set the 'file' field with the attachment ID
            if (function_exists('update_field')) {
                update_field('file', $post_id, $document_post_id);
            }

            // Optionally set a default taxonomy term if applicable
            wp_set_post_terms($document_post_id, 'default-category', 'doccat', true);
        }
    }
}

add_action('add_attachment', 'create_document_cpt_on_pdf_upload');

// 2. Delete Document CPT When Attachment is Deleted
function delete_document_cpt_when_attachment_is_deleted($post_id)
{
    global $delete_in_progress;

    // Prevent recursive deletion
    if ($delete_in_progress) {
        return;
    }

    // Get the attachment post object
    $attachment = get_post($post_id);

    // Check if the deleted post is an attachment and it's a PDF
    if ($attachment && $attachment->post_type === 'attachment' && $attachment->post_mime_type === 'application/pdf') {
        // Set the flag to indicate we're in the process of deleting
        $delete_in_progress = true;

        // Query for the CPT post that uses this attachment ID
        $args = array(
            'post_type'  => 'document',
            'meta_query' => array(
                array(
                    'key'     => 'file',  // The ACF field that holds the attachment ID
                    'value'   => $post_id,
                    'compare' => '='
                )
            )
        );

        $document_posts = get_posts($args);

        // Delete the associated CPT post if found
        if (!empty($document_posts)) {
            foreach ($document_posts as $document_post) {
                wp_delete_post($document_post->ID, true);
            }
        }

        // Reset the flag after deletion is complete
        $delete_in_progress = false;
    }
}

add_action('delete_attachment', 'delete_document_cpt_when_attachment_is_deleted');

// 3. Delete the PDF Attachment When Document CPT is Deleted
function delete_pdf_on_document_delete($post_id)
{
    global $delete_in_progress;

    // Prevent recursive deletion
    if ($delete_in_progress) {
        return;
    }

    // Check if the deleted post is the document CPT
    if (get_post_type($post_id) == 'document') {  // Replace 'document' with your CPT slug if different
        // Get the attachment ID from the ACF field
        $attachment_id = get_field('file', $post_id);

        // Delete the attachment if it exists
        if ($attachment_id) {
            // Set the flag to indicate we're in the process of deleting
            $delete_in_progress = true;

            // Temporarily remove the delete_attachment hook to prevent recursion
            remove_action('delete_attachment', 'delete_document_cpt_when_attachment_is_deleted');

            wp_delete_attachment($attachment_id, true);

            // Re-add the delete_attachment hook
            add_action('delete_attachment', 'delete_document_cpt_when_attachment_is_deleted');

            // Reset the flag after deletion is complete
            $delete_in_progress = false;
        }
    }
}

add_action('before_delete_post', 'delete_pdf_on_document_delete');


// filterable document tax columns

// Add taxonomy filters to the admin list view for the 'document' post type
add_action('restrict_manage_posts', 'add_document_taxonomy_filters');
function add_document_taxonomy_filters()
{
    global $typenow;

    // Only add dropdowns for the 'document' post type
    if ($typenow == 'document') {

        // Add dropdown for 'doccat' taxonomy
        $taxonomy = 'doccat';
        $doccat_taxonomy = get_taxonomy($taxonomy);
        if ($doccat_taxonomy) {
            wp_dropdown_categories(array(
                'show_option_all' => __("Show All {$doccat_taxonomy->label}", 'text_domain'),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => (isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : ''),
                'hierarchical'    => true,
                'depth'           => 3,
                'show_count'      => true,
                'hide_empty'      => false,
                'value_field'     => 'slug', // Use slug as value to match the query structure
            ));
        }

        // Add dropdown for 'doctype' taxonomy
        $taxonomy = 'doctype';
        $doctype_taxonomy = get_taxonomy($taxonomy);
        if ($doctype_taxonomy) {
            wp_dropdown_categories(array(
                'show_option_all' => __("Show All {$doctype_taxonomy->label}", 'text_domain'),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => (isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : ''),
                'hierarchical'    => true,
                'depth'           => 3,
                'show_count'      => true,
                'hide_empty'      => false,
                'value_field'     => 'slug', // Use slug as value to match the query structure
            ));
        }
    }
}


// Filter the documents by taxonomy based on selected dropdowns
add_action('pre_get_posts', 'filter_documents_by_taxonomy');
function filter_documents_by_taxonomy($query)
{
    global $typenow;

    if ($typenow == 'document' && is_admin() && $query->is_main_query()) {

        // Check if the 'doccat' dropdown has a value selected
        if (!empty($_GET['doccat']) && $_GET['doccat'] != '0') {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'doccat',
                    'field'    => 'slug', // Match the dropdown value field
                    'terms'    => $_GET['doccat'],
                )
            ));
        }

        // Check if the 'doctype' dropdown has a value selected
        if (!empty($_GET['doctype']) && $_GET['doctype'] != '0') {
            $tax_query = $query->get('tax_query');

            if (!is_array($tax_query)) {
                $tax_query = array();
            }

            $tax_query[] = array(
                'taxonomy' => 'doctype',
                'field'    => 'slug', // Match the dropdown value field
                'terms'    => $_GET['doctype'],
            );

            $query->set('tax_query', $tax_query);
        }
    }
}


// Campaign Monitor Checkboxen
// add_action('gform_after_submission_2', function($entry, $form) {
//     error_log("🚀 Gravity Forms After Submission Hook Running for Form 2");

//     // Map the correct checkbox fields to their hidden storage fields
//     $field_mappings = [
//         18 => 21, // ASCOT → Hidden Field 21
//         19 => 22, // AGVIT → Hidden Field 22
//         20 => 23  // AFUND → Hidden Field 23
//     ];

//     foreach ($field_mappings as $checkbox_field_id => $hidden_field_id) {
//         $selected_values = [];

//         foreach ($form['fields'] as $field) {
//             if ($field->id == $checkbox_field_id) {
//                 $inputs = $field->get_entry_inputs();

//                 if (is_array($inputs)) {
//                     foreach ($inputs as $input) {
//                         $value = rgar($entry, (string) $input['id']);
//                         if (!empty($value)) {
//                             $selected_values[] = $value;
//                         }
//                     }
//                 } else {
//                     $value = rgar($entry, (string) $field->id);
//                     if (!empty($value)) {
//                         $selected_values[] = $value;
//                     }
//                 }
//             }
//         }

//         error_log("🔍 Retrieved values for Field {$checkbox_field_id}: " . print_r($selected_values, true));

//         if (!empty($selected_values)) {
//             // Convert array into Campaign Monitor-friendly format
//             $formatted_values = json_encode($selected_values); 

//             // Store the formatted values in the corresponding hidden field
//             GFAPI::update_entry_field($entry['id'], $hidden_field_id, $formatted_values);

//             error_log("✅ Field {$hidden_field_id} updated with: " . $formatted_values);
//         } else {
//             error_log("⚠️ No checkboxes were selected for Field {$checkbox_field_id}.");
//         }
//     }
// }, 10, 2);
