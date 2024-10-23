<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

require_once CB_THEME_DIR . '/inc/cb-posttypes.php';
require_once CB_THEME_DIR . '/inc/cb-taxonomies.php';
require_once CB_THEME_DIR . '/inc/cb-utility.php';
require_once CB_THEME_DIR . '/inc/cb-blocks.php';
// require_once CB_THEME_DIR . '/inc/cb-news.php';
// require_once CB_THEME_DIR . '/inc/cb-careers.php';


// Remove unwanted SVG filter injection WP
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');


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


// pricing data job
// Add custom interval for cron jobs (2 minutes)
function custom_cron_schedule($schedules)
{
    if (!isset($schedules["2min"])) {
        $schedules["2min"] = array(
            'interval' => 120, // 120 seconds = 2 minutes
            'display'  => __('Every 2 Minutes')
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_schedule');

// Schedule the event if it's not already scheduled
function schedule_pricing_check()
{
    if (!wp_next_scheduled('check_pricing_data')) {
        wp_schedule_event(time(), '2min', 'check_pricing_data');
    }
}
add_action('wp', 'schedule_pricing_check');

// Fetch data from both URLs and update respective options
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
    $output = '<div class="pricing-data-status">';

    // Check and display Ascot data
    if ($ascot_data) {
        $output .= '<h3>Ascot Pricing Data:</h3>';
        $output .= '<pre>' . esc_html($ascot_data) . '</pre>';
        $output .= '<p>Last successful update: ' . ($ascot_last_success ? esc_html($ascot_last_success) : 'N/A') . '</p>';
    } else {
        $output .= '<h3>Ascot Pricing Data:</h3>';
        $output .= '<p style="color: red;">Failed to fetch the Ascot pricing data.</p>';
        $output .= '<p>Last failure: ' . ($ascot_last_failure ? esc_html($ascot_last_failure) : 'N/A') . '</p>';
    }

    // Check and display AGVIT data
    if ($agvit_data) {
        $output .= '<h3>AGVIT Pricing Data:</h3>';
        $output .= '<pre>' . esc_html($agvit_data) . '</pre>';
        $output .= '<p>Last successful update: ' . ($agvit_last_success ? esc_html($agvit_last_success) : 'N/A') . '</p>';
    } else {
        $output .= '<h3>AGVIT Pricing Data:</h3>';
        $output .= '<p style="color: red;">Failed to fetch the AGVIT pricing data.</p>';
        $output .= '<p>Last failure: ' . ($agvit_last_failure ? esc_html($agvit_last_failure) : 'N/A') . '</p>';
    }

    $output .= '</div>';

    return $output;
}
add_shortcode('pricing_data_status', 'display_pricing_data_status');


// DOCUMENT LIBRARY TAXONOMY DOMINE
function enqueue_custom_taxonomy_modal_script()
{
    // Enqueue the script only in the admin
    if (is_admin()) {
        wp_enqueue_script(
            'custom-taxonomy-modal',
            get_stylesheet_directory_uri() . '/js/custom-taxonomy-modal.js',
            array('jquery'),
            false,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_taxonomy_modal_script');

function fetch_taxonomy_terms_for_modal()
{
    $attachment_id = intval($_POST['attachment_id']);
    $taxonomy = sanitize_text_field($_POST['taxonomy']);

    if (!empty($taxonomy) && taxonomy_exists($taxonomy)) {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ));

        $selected_terms = wp_get_object_terms($attachment_id, $taxonomy, array('fields' => 'ids'));

        ob_start();
        foreach ($terms as $term) {
            echo '<label>';
            echo '<input type="checkbox" name="attachments[' . esc_attr($attachment_id) . '][' . esc_attr($taxonomy) . '][]" value="' . esc_attr($term->term_id) . '" ' . checked(in_array($term->term_id, $selected_terms), true, false) . '>';
            echo esc_html($term->name);
            echo '</label><br>';
        }
        $terms_html = ob_get_clean();

        wp_send_json_success(array('terms_html' => $terms_html));
    }

    wp_send_json_error();
}
add_action('wp_ajax_fetch_taxonomy_terms', 'fetch_taxonomy_terms_for_modal');

// function save_taxonomy_terms_for_attachments($post_id)
// {
//     // Check if this is an attachment
//     if (get_post_type($post_id) !== 'attachment') {
//         return;
//     }

//     // Define the taxonomies you want to save
//     $taxonomies = ['doccat', 'doctype']; // Add your taxonomy slugs here

//     foreach ($taxonomies as $taxonomy) {
//         // Check if the taxonomy input is set and save the terms
//         if (isset($_POST['attachments'][$post_id][$taxonomy])) {
//             $terms = $_POST['attachments'][$post_id][$taxonomy];
//             if (is_array($terms)) {
//                 // Save the taxonomy terms
//                 wp_set_object_terms($post_id, array_map('intval', $terms), $taxonomy, false);
//             }
//         } else {
//             // If no terms are set, remove all terms for this taxonomy
//             wp_set_object_terms($post_id, [], $taxonomy, false);
//         }
//     }
// }
// add_action('edit_attachment', 'save_taxonomy_terms_for_attachments');
function save_taxonomy_terms_for_attachments($post_id) {
    // Check if this is an attachment
    if (get_post_type($post_id) !== 'attachment') {
        return;
    }

    // Define the taxonomies you want to save
    $taxonomies = ['doccat', 'doctype']; // Add your taxonomy slugs here

    foreach ($taxonomies as $taxonomy) {
        // Check if the taxonomy input is set and save the terms
        if (isset($_POST['tax_input'][$taxonomy]) && !empty($_POST['tax_input'][$taxonomy])) {
            $terms = $_POST['tax_input'][$taxonomy];
            if (is_array($terms)) {
                wp_set_object_terms($post_id, array_map('intval', $terms), $taxonomy, false);
            }
        }
    }
}
add_action('save_post_attachment', 'save_taxonomy_terms_for_attachments');

// function update_attachment_taxonomies($post_id) {
//     if (get_post_type($post_id) === 'attachment') {
//         if (isset($_POST['tax_input']['doccat'])) {
//             wp_set_object_terms($post_id, $_POST['tax_input']['doccat'], 'doccat');
//         }
//         if (isset($_POST['tax_input']['doctype'])) {
//             wp_set_object_terms($post_id, $_POST['tax_input']['doctype'], 'doctype');
//         }
//     }
// }
// add_action('edit_attachment', 'update_attachment_taxonomies');

// DOC LIBRARY SEARCH REGISTRATION
// Add a rewrite rule to ensure the search query on a specific page works as expected.
// add_action('init', 'add_custom_search_rewrite_rule');
// function add_custom_search_rewrite_rule()
// {
//     add_rewrite_rule(
//         '^literature-library/([^/]*)/?',
//         'index.php?pagename=literature-library&s=$matches[1]',
//         'top'
//     );
//     flush_rewrite_rules();
// }

// // Allow the 's' query variable for our specific template.
// add_filter('query_vars', 'add_custom_query_vars');
// function add_custom_query_vars($vars)
// {
//     $vars[] = 's'; // Adding the search query var so it's properly recognized.
//     return $vars;
// }


// add_action('template_redirect', 'debug_custom_template');
// function debug_custom_template()
// {
//     if (is_404()) {
//         error_log('DEBUG: 404 triggered with URL: ' . $_SERVER['REQUEST_URI']);
//     }
// }

// add_filter('template_include', 'use_literature_library_template', 99);
// function use_literature_library_template($template)
// {
//     if (is_page('literature-library') && isset($_GET['s'])) {
//         $new_template = locate_template(array('page-templates/literature_library.php'));
//         if ($new_template) {
//             return $new_template;
//         }
//     }
//     return $template;
// }

/*
add_action('pre_get_posts', 'add_search_to_custom_template');
function add_search_to_custom_template($query)
{
    // Ensure it's the main query and we're on the right page
    if (!is_admin() && $query->is_main_query() && (is_page('literature-library') || isset($_GET['s']))) {
        // Set the post type to attachments and status to inherit for search queries on this page
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $query->set('post_type', 'attachment');
            $query->set('post_status', 'inherit');
        }
    }
}

add_action('init', 'custom_search_rewrite_rule');
function custom_search_rewrite_rule()
{
    add_rewrite_rule(
        '^literature-library/?$',
        'index.php?pagename=literature-library',
        'top'
    );
    flush_rewrite_rules();
}

add_action('template_redirect', 'custom_template_redirect');
function custom_template_redirect()
{
    if (is_page('literature-library') && get_query_var('s')) {
        // Force WordPress to use the template for search queries on this page
        include(get_page_template());
        exit;
    }
}

function add_query_vars_filter($vars)
{
    $vars[] = "s";
    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

add_filter('template_include', 'use_literature_template_for_search', 99);
function use_literature_template_for_search($template)
{
    if (is_page('literature-library') && get_query_var('s')) {
        $new_template = locate_template(array('page-templates/literature_library.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    return $template;
}
    
*/


// black thumbnails - fix alpha channel
/**
 * Patch to prevent black PDF backgrounds.
 *
 * https://core.trac.wordpress.org/ticket/45982
 */
// require_once ABSPATH . 'wp-includes/class-wp-image-editor.php';
// require_once ABSPATH . 'wp-includes/class-wp-image-editor-imagick.php';

// // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
// final class ExtendedWpImageEditorImagick extends WP_Image_Editor_Imagick
// {
//     /**
//      * Add properties to the image produced by Ghostscript to prevent black PDF backgrounds.
//      *
//      * @return true|WP_error
//      */
//     // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
//     protected function pdf_load_source()
//     {
//         $loaded = parent::pdf_load_source();

//         try {
//             $this->image->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
//             $this->image->setBackgroundColor('#ffffff');
//         } catch (Exception $exception) {
//             error_log($exception->getMessage());
//         }

//         return $loaded;
//     }
// }

// /**
//  * Filters the list of image editing library classes to prevent black PDF backgrounds.
//  *
//  * @param array $editors
//  * @return array
//  */
// add_filter('wp_image_editors', function (array $editors): array {
//     array_unshift($editors, ExtendedWpImageEditorImagick::class);

//     return $editors;
// });
?>