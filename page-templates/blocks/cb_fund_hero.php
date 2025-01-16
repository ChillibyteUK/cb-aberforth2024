<?php
// $fund = strtolower(get_field('theme'));
$field_value = get_field('theme');
$theme = $field_value !== null ? strtolower((string)$field_value) : null;

$theme = 'fund_subnav--' . $fund;
$sheet = get_field($fund . '_factsheet', 'option');

$funds = array(
    'ascot' => 'Aberforth Smaller Companies Trust plc',
    'agvit' => 'Aberforth Geared Value & Income Trust plc',
    'afund' => 'Aberforth UK Small Companies Fund',
    'aslit' => 'Aberforth Split Level Income Trust plc'
);

$fund_options = array(
    'ascot' => 'ascot_pricing_data',
    'agvit' => 'agvit_pricing_data'
);

// Check if the given fund exists in the options array
if (array_key_exists($fund, $fund_options)) {
    $xmlContent = get_option($fund_options[$fund]) ?? null;
} else {
    $xmlContent = null;
}

?>
<section class="fund_hero pb-5">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-8">
                <div class="h1"><?= $funds[$fund] ?></div>
                <?php
                $sheet = get_field($fund . '_factsheet', 'option') ?? null;
                if ($sheet) {
                    $file = get_field('file',$sheet);
                    $file_url = wp_get_attachment_url($file);
                    if ($file_url ?? null) { 
                        ?>
                <a href="<?=$file_url?>" target="_blank" class="button button--download"><?=get_the_title($sheet)?></a>
                        <?php
                    }
                }
                $link = get_field($fund . '_kepler_link','option') ?? null;
                if ($link ?? null) {
                    ?>
                <a href="<?=$link['url']?>" target="<?=$link['target']?>" class="button button--external"><?=$link['title']?></a>
                    <?php
                }
                ?>
            </div>
            <div class="col-md-4 d-flex gap-4 justify-content-end">
                <?php
                if ($xmlContent) {
                    // Parse the XML data
                    $xml = simplexml_load_string($xmlContent);
                    if ($xml === false) {
                        error_log("Error: Failed to parse XML.");
                    }

                    $shares = isset($xml->share) ? $xml->share : [$xml];

                    // Display the data for each share
                    foreach ($shares as $share) {
                        // Convert XML to JSON and then to an associative array for easy access
                        $json = json_encode($share);
                        $data = json_decode($json, true);

                        if ($data) {
                            $symbol = $data['Symbol'];
                            $currentPrice = $data['CurrentPrice'];
                            $change = $data['Change'];
                            $date = $data['Date'];

                            $change_status = ($change >= 0) ? 'ticker__change--up' : 'ticker__change--down';

                ?>
                            <div class="ticker">
                                <div class="ticker__date">Share Price: <?= $date ?></div>
                                <div class="ticker__symbol"><?= $symbol ?></div>
                                <div class="ticker__price"><?= $currentPrice ?></div>
                                <div class="ticker__change <?=$change_status?>"><?= $change ?></div>
                            </div>
                <?php
                        } else {
                            echo 'Error: No data found.';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
<section class=" fund_subnav <?= $theme ?>">
    <div class="container-xl">

        <?php
        // Define the parent page ID (root of the current section)
        $root_page_id = get_field($fund . '_parent', 'option'); // Replace 123 with the actual parent page ID

        // Get the current page ID
        $current_page_id = get_the_ID();

        // Get the parent page ID of the current page
        $parent_id = wp_get_post_parent_id($current_page_id);

        // Get pages to display in the navigation
        if ($current_page_id == $root_page_id) {
            // If the current page is the root page, get all child pages of the root
            $pages = get_pages(array(
                'parent'      => $root_page_id,
                'sort_column' => 'menu_order',
                'post_status' => 'publish',
            ));
            // Add the root page to the beginning of the list
            array_unshift($pages, get_post($root_page_id));
        } else {
            // If the current page is a child page, add the root page and get the sibling pages
            $pages = [get_post($root_page_id)];
            $siblings = get_pages(array(
                'parent'      => $root_page_id,
                'sort_column' => 'menu_order',
                'post_status' => 'publish'
            ));
            $pages = array_merge($pages, $siblings);
        }

        // Output the navigation block
        echo '<nav class="fund-nav">';
        foreach ($pages as $page) {
            $page_permalink = get_permalink($page->ID);
            $active_class = ($current_page_id == $page->ID) ? ' class="active"' : '';
            $title = ($page->ID == $root_page_id) ? 'Information' : get_the_title($page->ID);
            echo '<a href="' . $page_permalink . '"' . $active_class . '>' . $title . '</a>';
        }
        echo '</nav>';
        ?>


    </div>
</section>