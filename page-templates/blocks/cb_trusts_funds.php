<?php
/**
 * Trusts & Funds block template.
 *
 * Displays information and statistics for Aberforth trusts and funds.
 *
 * @package cb-aberforth2024
 */

defined( 'ABSPATH' ) || exit;

$classes = $block['className'] ?? null;
?>
<section class="trusts_funds py-5 <?= esc_attr( $classes ); ?>">
    <div class="container-xl">
        <h2 class="text-center mb-5">Trusts &amp; Funds</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="trusts_funds__card theme--ascot">
                    <h3 class="trusts_funds__header">Aberforth Smaller Companies Trust plc</h3>
                    <div class="trusts_funds__inner">
                        <div><?= esc_html( get_field( 'ascot_intro', 'option' ) ); ?></div>
                        <div class="lined">
                            <?= wp_kses_post( get_field( 'ascot_info', 'option' ) ); ?>
                        </div>
                        <?php
                        $page_slug      = '/trusts-and-funds/aberforth-smaller-companies-trust-plc/performance/';
                        $ascot_page     = get_page_by_path( $page_slug );
                        $ascot_value    = null;
                        $ascot_date     = null;
                        $ascot_nav      = null;
                        $ascot_nav_date = null;
                        if ( $ascot_page ) {
                            // Get the page ID.
                            $page_id = $ascot_page->ID;

                            $blocks = parse_blocks( get_post_field( 'post_content', $page_id ) );

                            foreach ( $blocks as $block ) {
                                if ( 'acf/cb-data-table' === $block['blockName'] ) {
                                    // Retrieve the block data.
                                    $block_data = $block['attrs']['data'] ?? null;

                                    if ( $block_data ) {
                                        // Extract the number of rows from the 'rows' key.
                                        $row_count = $block_data['rows'] ?? 0;

                                        // Loop through rows using the flattened key format.
                                        for ( $i = 0; $i < $row_count; $i++ ) {
                                            $name_key  = "rows_{$i}_name";
                                            $value_key = "rows_{$i}_value";

                                            if ( isset( $block_data[ $name_key ] ) ) {
                                                $name  = $block_data[ $name_key ];
                                                $value = $block_data[ $value_key ] ?? null;

                                                // Check for "Market value of investments".
                                                if ( 'Market value of investments' === $name ) {
                                                    $cleaned       = str_replace( array( '£', 'm', ',' ), '', $value );
                                                    $rounded_value = round( (float) $cleaned );
                                                    $ascot_value   = '£' . number_format( $rounded_value ) . 'm';
                                                }

                                                // Check if the name starts with "All data as at".
                                                if ( str_starts_with( $name, 'All data as at' ) ) {
                                                    // Extract the date part using regex.
                                                    if ( preg_match( '/All data as at (.+)$/', $name, $matches ) ) {
                                                        $date_string = $matches[1]; // Extract the date part.

                                                        // Convert to DD/MM/YYYY format.
                                                        $date = DateTime::createFromFormat( 'j F Y', $date_string );
                                                        if ( $date ) {
                                                            $ascot_date     = $date->format( 'd M Y' );
                                                            $ascot_nav_date = $ascot_date;
                                                        }
                                                    }
                                                }

                                                if ( str_starts_with( $name, 'Ordinary Share NAV' ) ) {
                                                    $ascot_nav = $value;
                                                }
                                            }

                                            // Break the loop if both values are found.
                                            if ( $ascot_value && $ascot_date && $ascot_nav ) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            echo 'Page not found.';
                        }

                        $xml_content      = get_option( 'ascot_pricing_data' ) ?? null;
                        $ascot_price      = null;
                        $ascot_price_date = null;
                        $ascot_change     = null;

                        if ( $xml_content ) {
                            // Parse the XML data.
                            $xml = simplexml_load_string( $xml_content );
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
                                    $symbol        = $data['Symbol'];
                                    $current_price = $data['CurrentPrice'];
                                    $change        = $data['Change'];
                                    $date          = $data['Date'];

                                    $change_status = ( $change >= 0 ) ? 'ticker__change--up' : 'ticker__change--down';

                                    $ascot_price_date = $date;
                                    $ascot_price      = $current_price;
                                    $ascot_change     = $change;
                                } else {
                                    echo 'Error: No data found.';
                                }
                            }
                        }
                        ?>
                        <div class="stats">
                            <div class="stats--ascot span-2">
                                <div class="stats__title">Market Value of Investments</div>
                                <div class="stats__date"><?= esc_html( $ascot_date ); ?></div>
                                <div class="stats__value"><?= esc_html( $ascot_value ); ?></div>
                            </div>
                            <div class="stats--ascot">
                                <div class="stats__title">Ordinary Share Price</div>
                                <div class="stats__date"><?= esc_html( $ascot_price_date ); ?></div>
                                <div class="stats__value"><?= esc_html( $ascot_price ); ?>p</div>

                            </div>
                            <div class="stats--ascot">
                                <div class="stats__title">Ordinary Share NAV</div>
                                <div class="stats__date"><?= esc_html( $ascot_nav_date ); ?></div>
                                <div class="stats__value"><?= esc_html( $ascot_nav ); ?></div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="/trusts-and-funds/aberforth-smaller-companies-trust-plc/" class="button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="trusts_funds__card theme--agvit">
                    <h3 class="trusts_funds__header">Aberforth Geared Value &amp; Income Trust plc</h3>
                    <div class="trusts_funds__inner">
                        <div><?= esc_html( get_field( 'agvit_intro', 'option' ) ); ?></div>
                        <div class="lined">
                            <span><?= wp_kses_post( get_field( 'agvit_info', 'option' ) ); ?></span>
                        </div>
                        <div class="tickers">
                            <?php
                            $xml_content = get_option( 'agvit_pricing_data' ) ?? null;

                            // Parse the XML data.
                            $xml = simplexml_load_string( $xml_content );
                            if ( false === $xml ) {
                                die( 'Error: Failed to parse XML.' );
                            }

                            $shares = isset( $xml->share ) ? $xml->share : array( $xml );

                            // Display the data for each share.
                            foreach ( $shares as $share ) {
                                // Convert XML to JSON and then to an associative array for easy access.
                                $json = wp_json_encode( $share );
                                $data = json_decode( $json, true );

                                if ( $data ) {
                                    $symbol        = $data['Symbol'];
                                    $current_price = $data['CurrentPrice'];
                                    $change        = $data['Change'];
                                    $date          = gmdate( 'j M Y', strtotime( str_replace( '/', '-', $data['Date'] ) ) );

                                    switch ( $symbol ) {
                                        case 'AGVI.L':
                                            $symbol = 'Ordinary Share Price';
                                            break;
                                        case 'AGZI.L':
                                            $symbol = 'ZDP Share Price';
                                            break;
                                    }
                            		?>
                                    <div class="ticker mb-4">
                                        <div class="ticker__symbol"><?= esc_html( $symbol ); ?></div>
                                        <div class="ticker__date"><?= esc_html( $date ); ?></div>
                                        <div class="ticker__price"><?= esc_html( $current_price ); ?>p</div>
                                    </div>
                            		<?php
                                } else {
                                    echo 'Error: No data found.';
                                }
                            }

                            $page_slug  = '/trusts-and-funds/aberforth-geared-value-income-trust-plc/performance/';
                            $agvit_page = get_page_by_path( $page_slug );
                            $osnav      = null;
                            $zdpnav     = null;
                            $agvit_date = null;
                            if ( $agvit_page ) {
                                $page_id = $agvit_page->ID;
                                $blocks  = parse_blocks( get_post_field( 'post_content', $page_id ) );
                                foreach ( $blocks as $block ) {
                                    if ( 'acf/cb-data-table' === $block['blockName'] ) {
                                        // Retrieve the block data.
                                        $block_data = $block['attrs']['data'] ?? null;

                                        if ( $block_data ) {
                                            // Extract the number of rows from the 'rows' key.
                                            $row_count = $block_data['rows'] ?? 0;
                                            // Loop through rows using the flattened key format.
                                            for ( $i = 0; $i < $row_count; $i++ ) {
                                                $name_key  = "rows_{$i}_name";
                                                $value_key = "rows_{$i}_value";

                                                if ( isset( $block_data[ $name_key ] ) ) {
                                                    $name  = $block_data[ $name_key ];
                                                    $value = $block_data[ $value_key ] ?? null;

                                                    if ( 'Ordinary Share NAV (including current year revenue)' === $name ) {
                                                        $osnav = $value;
                                                    }
                                                    /* if ( 'Zero Dividend Preference Share NAV (accounts basis)' === $name ) { */
                                                    if ( 'Zero Dividend Preference Share NAV (accrued entitlement per the Articles)' === $name ) {
                                                        $zdpnav = $value;
                                                    }
                                                    // Check if the name starts with "All data as at".
                                                    if ( str_starts_with( $name, 'All data as at' ) ) {
                                                        // Extract the date part using regex.
                                                        if ( preg_match( '/All data as at (.+)$/', $name, $matches ) ) {
                                                            $date_string = $matches[1]; // Extract the date part.

                                                            // Convert to DD/MM/YYYY format.
                                                            $date = DateTime::createFromFormat( 'j F Y', $date_string );
                                                            if ( $date ) {
                                                                $agvit_date = $date->format( 'd M Y' );
                                                            }
                                                        }
                                                    }

                                                    // Break the loop if values are found.
                                                    if ( $osnav && $zdpnav ) {
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            ?>
                            <div class="stats">
                                <div class="stats--agvit">
                                    <div class="stats__title">Ordinary Share NAV</div>
                                    <div class="stats__date"><?= esc_html( $agvit_date ); ?></div>
                                    <div class="stats__value"><?= esc_html( $osnav ); ?></div>
                                </div>
                            </div>
                            <div class="stats">
                                <div class="stats--agvit">
                                    <div class="stats__title">ZDP Share NAV (accrued entitlement per the Articles)</div>
                                    <div class="stats__date"><?= esc_html( $agvit_date ); ?></div>
                                    <div class="stats__value"><?= esc_html( $zdpnav ); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end span-2">
                            <a href="/trusts-and-funds/aberforth-geared-value-income-trust-plc/" class="button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> <!--  flex-grow-1 d-flex flex-column -->
                <div class="trusts_funds__card theme--afund h-100">
                    <h3 class="trusts_funds__header">Aberforth UK Small Companies Fund</h3>
                    <div class="trusts_funds__inner  justify-content-between">
                        <p class="trusts_funds__content span-2"><?= wp_kses_post( get_field( 'afund_intro', 'option' ) ); ?></p>
                        <div class="lined span-2 py-2">
                            <span>Launched: 20 March 1991</span>
                        </div>
                        <?php
                        $page_slug      = '/trusts-and-funds/aberforth-uk-small-companies-fund/performance/';
                        $afund_page     = get_page_by_path( $page_slug );
                        $afund_date     = null;
                        $afund_value    = null;
                        $afund_acc_buy  = null;
                        $afund_acc_sell = null;
                        $afund_inc_buy  = null;
                        $afund_inc_sell = null;

                        if ( $afund_page ) {
                            // Get the page ID.
                            $page_id = $afund_page->ID;

                            $blocks = parse_blocks( get_post_field( 'post_content', $page_id ) );

                            foreach ( $blocks as $block ) {
                                if ( 'acf/cb-data-table' === $block['blockName'] ) {
                                    $block_title = $block['attrs']['data']['title'];
                                    // Check if the name starts with "Valuation Date:".
                                    if ( str_starts_with( $block_title, 'Valuation Date: ' ) ) {
                                        // Extract the date part using regex.
                                        if ( preg_match( '/Valuation Date: (.+)$/', $block_title, $matches ) ) {
                                            $date_string = $matches[1]; // Extract the date part.

                                            // Convert to DD/MM/YYYY format.
                                            $date = DateTime::createFromFormat( 'j F Y', $date_string );
                                            if ( $date ) {
                                                $afund_date = $date->format( 'd M Y' );
                                            }
                                        }
                                    }
                                    // Retrieve the block data.
                                    $block_data = $block['attrs']['data'] ?? null;

                                    if ( $block_data ) {
                                        // Extract the number of rows from the 'rows' key.
                                        $row_count = $block_data['rows'] ?? 0;


                                        // Loop through rows using the flattened key format.
                                        for ( $i = 0; $i < $row_count; $i++ ) {
                                            $name_key  = "rows_{$i}_name";
                                            $value_key = "rows_{$i}_value";

                                            if ( isset( $block_data[ $name_key ] ) ) {
                                                $name  = $block_data[ $name_key ];
                                                $value = $block_data[ $value_key ] ?? null;

                                                if ( 'Accumulation Buying Price' === $name ) {
                                                    $afund_acc_buy = $value;
                                                }
                                                if ( 'Accumulation Selling Price' === $name ) {
                                                    $afund_acc_sell = $value;
                                                }
                                                if ( 'Income Buying Price' === $name ) {
                                                    $afund_inc_buy = $value;
                                                }
                                                if ( 'Income Selling Price' === $name ) {
                                                    $afund_inc_sell = $value;
                                                }
                                                // Break the loop if values are found.
                                                if ( $afund_acc_buy && $afund_acc_sell && $afund_inc_buy && $afund_inc_sell ) {
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        ?>
                        <div class="stats__date span-2"><?= esc_html( $afund_date ); ?></div>
                        <div class="stats">
                            <div class="stats--afund">
                                <div class="stats__title">Buying Price (Acc)</div>
                                <div class="stats__value"><?= esc_html( $afund_acc_buy ); ?></div>
                            </div>
                        </div>
                        <div class="stats">
                            <div class="stats--afund">
                                <div class="stats__title">Selling Price (Acc)</div>
                                <div class="stats__value"><?= esc_html( $afund_acc_sell ); ?></div>
                            </div>
                        </div>
                        <div class="stats">
                            <div class="stats--afund">
                                <div class="stats__title">Buying Price (Inc)</div>
                                <div class="stats__value"><?= esc_html( $afund_inc_buy ); ?></div>
                            </div>
                        </div>
                        <div class="stats">
                            <div class="stats--afund">
                                <div class="stats__title">Selling Price (Inc)</div>
                                <div class="stats__value"><?= esc_html( $afund_inc_sell ); ?></div>
                            </div>
                        </div>

                        <div class="text-end mt-auto span-2">
                            <a href="/trusts-and-funds/aberforth-uk-small-companies-fund/" class="button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="trusts_funds__card theme--aslit flex-grow-1 d-flex flex-column h-100">
                    <h3 class="trusts_funds__header">Aberforth Split Level Income Trust plc</h3>
                    <div class="trusts_funds__inner flex-grow-1 d-flex flex-column justify-content-between">
                        <p class="trusts_funds__content">A split capital investment trust with two classes of share – Ordinary Shares and Zero Dividend Preference (ZDP) Shares – both of which traded on the London Stock Exchange.</p>
                        <div class="lined-top py-2">Launched: 3 July 2017</div>
                        <div class="lined-both py-2">Wound up: 1 July 2024</div>
                        <div class="pt-3 text-end mt-auto span-2">
                            <a href="/trusts-and-funds/aberforth-split-level-income-trust-plc/" class="button">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
