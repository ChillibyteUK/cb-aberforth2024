<?php

function generate_custom_sitemap($sitemap_name, $urls) {
    header("Content-Type: application/xml; charset=utf-8");
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    foreach ($urls as $url) {
        echo '<url>';
        echo '<loc>' . esc_url($url) . '</loc>';
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
        $sitemap_name = $_GET['custom_sitemap'];

        $sitemaps = [
            'asl' => [
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-smaller-companies-trust-plc/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-smaller-companies-trust-plc/dividends/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-smaller-companies-trust-plc/documents/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-smaller-companies-trust-plc/fees-charges/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-smaller-companies-trust-plc/performance/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-smaller-companies-trust-plc/portfolio/'
            ],
            'agvi' => [
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/capital-structure/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/dividends/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/documents/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/fees-charges/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/launch-information/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/performance/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/portfolio/'
            ],
            'asit' => [
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-split-level-income-trust-plc/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-split-level-income-trust-plc/dividends/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-split-level-income-trust-plc/documents/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-split-level-income-trust-plc/performance/'
            ],
            'uk-small' => [
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-uk-small-companies-fund/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-uk-small-companies-fund/documents/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-uk-small-companies-fund/fees-charges/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-uk-small-companies-fund/income/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-uk-small-companies-fund/performance/',
                'https://www.aberforth.co.uk/trusts-and-funds/aberforth-uk-small-companies-fund/portfolio/'
            ]
        ];

        if (array_key_exists($sitemap_name, $sitemaps)) {
            generate_custom_sitemap($sitemap_name, $sitemaps[$sitemap_name]);
        }
    }
});


add_filter('wpseo_exclude_from_sitemap_by_post_ids', function($excluded_posts) {
    $excluded_posts = [
        get_the_ID_by_url('https://www.aberforth.co.uk/trusts-and-funds/aberforth-smaller-companies-trust-plc/'),
        get_the_ID_by_url('https://www.aberforth.co.uk/trusts-and-funds/aberforth-geared-value-income-trust-plc/'),
        get_the_ID_by_url('https://www.aberforth.co.uk/trusts-and-funds/aberforth-split-level-income-trust-plc/'),
        get_the_ID_by_url('https://www.aberforth.co.uk/trusts-and-funds/aberforth-uk-small-companies-fund/')
    ];
    return $excluded_posts;
});

function get_the_ID_by_url($url) {
    global $wpdb;
    $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", esc_url($url)));
    return $post_id ? $post_id : null;
}

