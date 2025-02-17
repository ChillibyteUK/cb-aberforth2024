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

function generate_custom_sitemap($sitemap_name) {
    global $sitemaps; // Access the global sitemaps array

    if (!isset($sitemaps[$sitemap_name])) {
        return;
    }

    header("Content-Type: application/xml; charset=utf-8");
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    foreach ($sitemaps[$sitemap_name] as $url) {
        echo '<url>';
        echo '<loc>' . site_url() . esc_url($url) . '</loc>';
        echo '<lastmod>' . date("Y-m-d") . '</lastmod>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.8</priority>';
        echo '</url>';
    }

    echo '</urlset>';
    exit;
}

add_action('init', function() {
    if (isset($_GET['custom_sitemap'])) {
        generate_custom_sitemap($_GET['custom_sitemap']);
    }
});

function get_the_ID_by_url($url) {
    // Remove the domain and extract the path
    $parsed_url = parse_url($url);
    $path = trim($parsed_url['path'], '/');

    // Use get_page_by_path() to retrieve the correct post
    $page = get_page_by_path($path, OBJECT, 'page'); // Looks for a page with this full path

    if ($page) {
        error_log("Correct Post ID found for $url: " . $page->ID);
        return $page->ID;
    }

    error_log("No post found for path: $path");
    return null;
}

add_filter('wpseo_exclude_from_sitemap_by_post_ids', function($excluded_posts) {
    global $sitemaps; // Use the global sitemap array

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


add_filter('wpseo_sitemap_index', function($sitemap_index) {
    $custom_sitemaps = [
        'asl' => 'custom_sitemap=asl',
        'agvi' => 'custom_sitemap=agvi',
        'asit' => 'custom_sitemap=asit',
        'uk-small' => 'custom_sitemap=uk-small',
    ];

    $home_url = home_url('/');

    foreach ($custom_sitemaps as $key => $query) {
        $sitemap_index .= '<sitemap>
            <loc>' . esc_url($home_url . '?' . $query) . '</loc>
            <lastmod>' . date('c') . '</lastmod>
        </sitemap>';
    }

    return $sitemap_index;
});
