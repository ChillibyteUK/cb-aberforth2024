<?php
// Reads its figures from Site-Wide Settings -> Data (see acf-json/group_644a409ec0408.json).
// Renders the same markup as CB Data Table so existing page styling is unaffected.
// The Data tab's currency/percentage fields are plain numbers (no £/p/%/m in the stored
// value) -- cb_format_stat() (inc/cb-utility.php) adds the symbol and decimal places.

$class = $block['className'] ?? 'py-5';
$theme = get_field('theme') ?: 'ASCOT';

switch ($theme) {
    case 'AGVIT':
        $date = get_field('agvit_data_date', 'option');
        $tables = array(
            array(
                'bg' => 'bg--blue-200',
                'main_title' => 'Latest NAVs',
                'title' => 'Latest Net Asset Values & Financial Information',
                'rows' => array(
                    array('All data as at ' . $date, 'Values'),
                    array('Ordinary Share NAV (excluding current year revenue)', cb_format_stat(get_field('agvit_nav_excl_revenue', 'option'), 2, '', 'p')),
                    array('Ordinary Share NAV (including current year revenue)', cb_format_stat(get_field('agvit_nav_incl_revenue', 'option'), 2, '', 'p')),
                    array('Zero Dividend Preference Share NAV (accrued entitlement per the Articles)', cb_format_stat(get_field('agvit_zdp_nav_accrued', 'option'), 2, '', 'p')),
                    array('Zero Dividend Preference Share NAV (accounts basis)', cb_format_stat(get_field('agvit_zdp_nav_accounts', 'option'), 2, '', 'p')),
                    array('Total net assets', cb_format_stat(get_field('agvit_total_net_assets', 'option'), 1, '£', 'm')),
                    array('Total Zero Dividend Preference Shares entitlement (accounts basis)', cb_format_stat(get_field('agvit_total_zdp_entitlement', 'option'), 1, '£', 'm')),
                    array("Total Shareholders' funds (Ordinary Shares)", cb_format_stat(get_field('agvit_total_shareholders_funds', 'option'), 1, '£', 'm')),
                ),
                'notes' => get_field('agvit_data_notes', 'option'),
            ),
        );
        break;

    case 'AFUND':
        $val_date = get_field('afund_valuation_date', 'option');
        $tables = array(
            array(
                'bg' => 'bg--white',
                'main_title' => 'Latest Prices & Fund Statistics',
                'title' => 'Valuation Date: ' . $val_date,
                'rows' => array(
                    array('Unit and Price Type', 'Price'),
                    array('Accumulation Buying Price', cb_format_stat(get_field('afund_acc_buy', 'option'), 2, '£')),
                    array('Accumulation Selling Price', cb_format_stat(get_field('afund_acc_sell', 'option'), 2, '£')),
                    array('Income Buying Price', cb_format_stat(get_field('afund_inc_buy', 'option'), 2, '£')),
                    array('Income Selling Price', cb_format_stat(get_field('afund_inc_sell', 'option'), 2, '£')),
                ),
                'notes' => '',
            ),
            array(
                'bg' => 'bg--light',
                'main_title' => '',
                'title' => 'Fund Statistics',
                'rows' => array(
                    array('Statistic', 'Value'),
                    array('Yield (Historic)', cb_format_stat(get_field('afund_yield', 'option'), 1, '', '%')),
                    array('Size (Mid-Basis)', cb_format_stat(get_field('afund_size', 'option'), 1, '£', 'm')),
                    array('Dealing Spread', cb_format_stat(get_field('afund_dealing_spread', 'option'), 1, '', '%')),
                    array('Initial Charge', cb_format_stat(get_field('afund_initial_charge', 'option'), 1, '', '%')),
                    array('Exit Charge', cb_format_stat(get_field('afund_exit_charge', 'option'), 1, '', '%')),
                ),
                'notes' => get_field('afund_stats_notes', 'option'),
            ),
        );
        break;

    case 'ASCOT':
    default:
        $date = get_field('ascot_data_date', 'option');
        $tables = array(
            array(
                'bg' => 'bg--white',
                'main_title' => 'Latest NAV',
                'title' => 'Latest Net Asset Values & Financial Information',
                'rows' => array(
                    array('All data as at ' . $date, 'Values'),
                    array('Ordinary Share NAV', cb_format_stat(get_field('ascot_nav', 'option'), 2)),
                    array('Market value of investments', cb_format_stat(get_field('ascot_market_value', 'option'), 1, '£')),
                    array("Total Shareholders' funds", cb_format_stat(get_field('ascot_total_shareholders_funds', 'option'), 1, '£')),
                    array('Gearing', cb_format_stat(get_field('ascot_gearing', 'option'), 1, '', '%')),
                ),
                'notes' => get_field('ascot_data_notes', 'option'),
            ),
        );
        break;
}
?>
<?php foreach ($tables as $t) { ?>
    <div class="section data_table <?= $class ?> <?= $t['bg'] ?>">
        <div class="container-xl">
            <?php if ($t['main_title']) { ?>
                <h2 class="mb-4"><?= esc_html($t['main_title']) ?></h2>
            <?php } ?>
            <?php if ($t['title']) { ?>
                <h3><?= esc_html($t['title']) ?></h3>
            <?php } ?>
            <?php if (!empty($t['rows'])) {
                $rows = $t['rows'];
                $header = array_shift($rows);
            ?>
                <table class="table">
                    <thead>
                        <tr><th><?= esc_html($header[0]) ?></th><th class="text-end"><?= esc_html($header[1]) ?></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) { ?>
                            <tr><td><?= esc_html($row[0]) ?></td><td class="text-end"><?= esc_html($row[1]) ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            <?php if ($t['notes']) { ?>
                <div class="small mt-4">
                    <strong>Notes</strong><br>
                    <?= $t['notes'] ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
