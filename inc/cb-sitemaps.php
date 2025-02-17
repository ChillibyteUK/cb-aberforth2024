<?php

// Define global sitemaps array
global $sitemaps;
$sitemaps = [
    'asl' => [
        '/trusts-and-funds/aberforth-smaller-companies-trust-plc/',
        '/trusts-and-funds/aberforth-smaller-companies-trust-plc/dividends/',
        '/trusts-and-funds/aberforth-smaller-companies-trust-plc/documents/',
        '/trusts-and-funds/aberforth-smaller-companies-trust-plc/fees-charges/',
        '/trusts-and-funds/aberforth-smaller-companies-trust-plc/performance/',
        '/trusts-and-funds/aberforth-smaller-companies-trust-plc/portfolio/'
    ],
    'agvi' => [
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/',
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/capital-structure/',
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/dividends/',
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/documents/',
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/fees-charges/',
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/launch-information/',
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/performance/',
        '/trusts-and-funds/aberforth-geared-value-income-trust-plc/portfolio/'
    ],
    'asit' => [
        '/trusts-and-funds/aberforth-split-level-income-trust-plc/',
        '/trusts-and-funds/aberforth-split-level-income-trust-plc/dividends/',
        '/trusts-and-funds/aberforth-split-level-income-trust-plc/documents/',
        '/trusts-and-funds/aberforth-split-level-income-trust-plc/performance/'
    ],
    'uk-small' => [
        '/trusts-and-funds/aberforth-uk-small-companies-fund/',
        '/trusts-and-funds/aberforth-uk-small-companies-fund/documents/',
        '/trusts-and-funds/aberforth-uk-small-companies-fund/fees-charges/',
        '/trusts-and-funds/aberforth-uk-small-companies-fund/income/',
        '/trusts-and-funds/aberforth-uk-small-companies-fund/performance/',
        '/trusts-and-funds/aberforth-uk-small-companies-fund/portfolio/'
    ]
];

// Function to get the post ID by full URL path
function get_the_ID_by_url($url) {
    // Remove the domain and extract the path
    $parsed_url = parse_url($url);
    $path = trim($parsed_url['path'], '/');

    // Use get_page_by_path() to retrieve the correct post
    $page = get_page_by_path($path, OBJECT, 'page');

    return $page ? $page->ID : null;
}

// Function to generate static sitemap files
function generate_static_sitemaps() {
    global $sitemaps;
    $upload_dir = ABSPATH; // Root directory of WordPress

    foreach ($sitemaps as $key => $urls) {
        $sitemap_file = $upload_dir . "{$key}-sitemap.xml";

        $xml_content = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml_content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml_content .= '<url>';
            $xml_content .= '<loc>' . esc_url(home_url($url)) . '</loc>';
            $xml_content .= '<lastmod>' . date("Y-m-d") . '</lastmod>';
            $xml_content .= '<changefreq>weekly</changefreq>';
            $xml_content .= '<priority>0.8</priority>';
            $xml_content .= '</url>';
        }

        $xml_content .= '</urlset>';

        // Write to static file
        file_put_contents($sitemap_file, $xml_content);
    }
}

// Hook into WP-Cron to regenerate sitemaps every 6 hours
if (!wp_next_scheduled('generate_custom_sitemaps_event')) {
    wp_schedule_event(time(), 'twicedaily', 'generate_custom_sitemaps_event');
}
add_action('generate_custom_sitemaps_event', 'generate_static_sitemaps');


// Ensure the filter runs at the correct time
add_action('init', function() {
    add_filter('wpseo_sitemap_index', 'add_custom_sitemaps_to_yoast', 10, 1);
});

function add_custom_sitemaps_to_yoast($sitemap_index) {
    $custom_sitemaps = [
        'asl-sitemap.xml',
        'agvi-sitemap.xml',
        'asit-sitemap.xml',
        'uk-small-sitemap.xml'
    ];

    $home_url = home_url('/');

    foreach ($custom_sitemaps as $sitemap) {
        $sitemap_index .= '<sitemap>
            <loc>' . esc_url($home_url . $sitemap) . '</loc>
            <lastmod>' . date('c') . '</lastmod>
        </sitemap>';
    }

    return $sitemap_index;
}

// Exclude the pages from Yoast's page-sitemap.xml
add_filter('wpseo_exclude_from_sitemap_by_post_ids', function($excluded_posts) {
    global $sitemaps;

    foreach ($sitemaps as $sitemap => $urls) {
        foreach ($urls as $url) {
            $post_id = get_the_ID_by_url($url);
            if ($post_id) {
                $excluded_posts[] = $post_id;
            }
        }
    }

    error_log("Excluded Post IDs: " . implode(', ', $excluded_posts));

    return $excluded_posts;
}, 10, 1);
