<?php
/**
 * Block Name: Fund Hero
 *
 * This is the template that displays the Fund Hero block.
 *
 * @package cb-aberforth2024
 */

defined( 'ABSPATH' ) || exit;

$fund  = strtolower( get_field( 'theme' ) );
$theme = 'fund_subnav--' . $fund;
$sheet = get_field( $fund . '_factsheet', 'option' );

$funds = array(
    'ascot' => 'Aberforth Smaller Companies Trust plc (ASL)',
    'agvit' => 'Aberforth Geared Value & Income Trust plc (AGVI)',
    'afund' => 'Aberforth UK Small Companies Fund',
    'aslit' => 'Aberforth Split Level Income Trust plc',
);

// Page-specific overrides.
// FOR THE LOVE OF ODIN STOP USING PAGE IDS!!!
if ( is_page( '/trusts-and-funds/aberforth-smaller-companies-trust-plc/portfolio/' ) ) {
    $funds['ascot'] = 'Aberforth Smaller Companies Trust plc - Portfolio';
}
if ( is_page( '/trusts-and-funds/aberforth-smaller-companies-trust-plc/fees-charges/' ) ) {
    $funds['ascot'] = 'Aberforth Smaller Companies Trust plc - Fees & Charges';
}
if ( is_page( '/trusts-and-funds/aberforth-smaller-companies-trust-plc/performance/' ) ) {
    $funds['ascot'] = 'Aberforth Smaller Companies Trust plc - Share Price';
}
if ( is_page( '/trusts-and-funds/aberforth-smaller-companies-trust-plc/dividends/' ) ) {
    $funds['ascot'] = 'Aberforth Smaller Companies Trust plc - Dividends';
}
if ( is_page( '/trusts-and-funds/aberforth-smaller-companies-trust-plc/documents/' ) ) {
    $funds['ascot'] = 'Aberforth Smaller Companies Trust plc - Documents';
}
if ( is_page( '/trusts-and-funds/aberforth-geared-value-income-trust-plc/capital-structure/' ) ) {
    $funds['agvit'] = 'Aberforth Geared Value & Income Trust - Capital Structure';
}
if ( is_page( '/trusts-and-funds/aberforth-geared-value-income-trust-plc/dividends/' ) ) {
    $funds['agvit'] = 'Aberforth Geared Value & Income Trust plc - Dividends';
}
if ( is_page( '/trusts-and-funds/aberforth-geared-value-income-trust-plc/performance/' ) ) {
    $funds['agvit'] = 'Aberforth Geared Value & Income Trust plc - Share Price';
}
if ( is_page( '/trusts-and-funds/aberforth-geared-value-income-trust-plc/portfolio/' ) ) {
    $funds['agvit'] = 'Aberforth Geared Value & Income Trust plc - Portfolio';
}
if ( is_page( '/trusts-and-funds/aberforth-geared-value-income-trust-plc/fees-charges/' ) ) {
    $funds['agvit'] = 'Aberforth Geared Value & Income Trust plc - Fees & Charges';
}
if ( is_page( '/trusts-and-funds/aberforth-geared-value-income-trust-plc/launch-information/' ) ) {
    $funds['agvit'] = 'Aberforth Geared Value & Income Trust plc - Launch Information';
}
if ( is_page( '/trusts-and-funds/aberforth-geared-value-income-trust-plc/documents/' ) ) {
    $funds['agvit'] = 'Aberforth Geared Value & Income Trust plc - Documents';
}
if ( is_page( '/trusts-and-funds/aberforth-uk-small-companies-fund/portfolio/' ) ) {
    $funds['afund'] = 'Aberforth UK Small Companies Fund - Portfolio';
}
if ( is_page( '/trusts-and-funds/aberforth-uk-small-companies-fund/income/' ) ) {
    $funds['afund'] = 'Aberforth UK Small Companies Fund - Income';
}
if ( is_page( '/trusts-and-funds/aberforth-uk-small-companies-fund/fees-charges/' ) ) {
    $funds['afund'] = 'Aberforth UK Small Companies Fund - Fees & Charges';
}
if ( is_page( '/trusts-and-funds/aberforth-uk-small-companies-fund/documents/' ) ) {
    $funds['afund'] = 'Aberforth UK Small Companies Fund - Documents';
}
if ( is_page( '/trusts-and-funds/aberforth-uk-small-companies-fund/performance/' ) ) {
    $funds['afund'] = 'Aberforth UK Small Companies Fund - Performance';
}

$fund_options = array(
    'ascot' => 'ascot_pricing_data',
    'agvit' => 'agvit_pricing_data',
);

// Check if the given fund exists in the options array.
if ( array_key_exists( $fund, $fund_options ) ) {
    $xmlContent = get_option( $fund_options[ $fund ] ) ?? null;
} else {
    $xmlContent = null;
}

$classes = $block['className'] ?? null;

?>
<section class="fund_hero pb-5 <?= esc_attr( $classes ); ?>">
    <div class="container-xl">
        <div class="row g-4">
            <div class="col-md-8">
                <h1><?= esc_html( $funds[ $fund ] ); ?></h1>
                <?php
                $sheet = get_field( $fund . '_factsheet', 'option' ) ?? null;
                if ( $sheet ) {
                    $file     = get_field( 'file', $sheet );
                    $file_url = wp_get_attachment_url( $file );
                    if ( $file_url ?? null ) {
                		?>
                        <a href="<?= esc_url( $file_url ); ?>" target="_blank" class="button button--download"><?= esc_html( get_the_title( $sheet ) ); ?></a>
                    	<?php
                    }
                }
                $link = get_field( $fund . '_kepler_link', 'option' ) ?? null;
                if ( $link ?? null ) {
                    ?>
                    <a href="<?= esc_url( $link['url'] ); ?>" target="<?= esc_attr( $link['target'] ); ?>" class="button button--external"><?= esc_html( $link['title'] ); ?></a>
                	<?php
                }
                ?>
            </div>
            <div class="col-md-4 d-flex gap-4 justify-content-md-end">
                <?php
                if ( $xmlContent ) {
                    // Parse the XML data.
                    $xml = simplexml_load_string( $xmlContent );
                    if ( false === $xml ) {
                        error_log( 'Error: Failed to parse XML.' );
                    }

                    $shares = isset( $xml->share ) ? $xml->share : array( $xml );

                    // Display the data for each share.
                    foreach ( $shares as $share ) {
                        // Convert XML to JSON and then to an associative array for easy access.
                        $json = wp_json_encode( $share );
                        $data = json_decode( $json, true );
                        if ( $data ) {
                            $symbol       = $data['Symbol'];
                            $currentPrice = $data['CurrentPrice'];
                            $change       = $data['Change'];
                            $date         = $data['Date'];

                            $change_status = ( $change >= 0 ) ? 'ticker__change--up' : 'ticker__change--down';

                			?>
                            <div class="ticker">
                                <div class="ticker__date">Share Price: <?= esc_html( $date ); ?></div>
                                <div class="ticker__symbol"><?= esc_html( $symbol ); ?></div>
                                <div class="ticker__price"><?= esc_html( $currentPrice ); ?></div>
                                <div class="ticker__change <?= esc_attr( $change_status ); ?>"><?= esc_html( $change ); ?></div>
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
<section class=" fund_subnav <?= esc_attr( $theme ); ?>">
    <div class="container-xl px-md-0">

        <?php
        // Define the parent page ID (root of the current section).
        $root_page_id = get_field( $fund . '_parent', 'option' ); // Replace 123 with the actual parent page ID.

        // Get the current page ID.
        $current_page_id = get_the_ID();

        // Get the parent page ID of the current page.
        $parent_id = wp_get_post_parent_id( $current_page_id );

        // Get pages to display in the navigation.
        if ( $current_page_id === $root_page_id ) {
            // If the current page is the root page, get all child pages of the root.
            $pages = get_pages(
				array(
					'parent'      => $root_page_id,
					'sort_column' => 'menu_order',
					'post_status' => 'publish',
				)
			);
            // Add the root page to the beginning of the list.
            array_unshift( $pages, get_post( $root_page_id ) );
        } else {
            // If the current page is a child page, add the root page and get the sibling pages.
            $pages    = array( get_post( $root_page_id ) );
            $siblings = get_pages(
				array(
					'parent'      => $root_page_id,
					'sort_column' => 'menu_order',
					'post_status' => 'publish',
				)
			);
            $pages    = array_merge( $pages, $siblings );
        }

        // Output the navigation block.
        echo '<nav class="fund-nav">';
        foreach ( $pages as $page ) {
            $page_permalink = get_permalink( $page->ID );
            $active_class   = ( $current_page_id === $page->ID ) ? ' class="active"' : '';
            $title          = ( $page->ID === $root_page_id ) ? 'Information' : get_the_title( $page->ID );
            echo '<a href="' . esc_url( $page_permalink ) . '"' . esc_attr( $active_class ) . '>' . esc_html( $title ) . '</a>';
        }
        echo '</nav>';
        ?>

    </div>
</section>