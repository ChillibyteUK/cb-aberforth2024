<div class="section latest_nav py-5">
    <div class="container-xl">
        <h2>Latest NAV</h2>
        <h3>Latest Net Asset Value &amp; Financial Information</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>All data as at <?= get_field('date') ?></th>
                    <th>Values</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Ordinary Share NAV
                    </td>
                    <td>
                        <?= number_format(get_field('ordinary_share_nav')) ?>p
                    </td>
                </tr>
                <tr>
                    <td>
                        Market value of investments
                    </td>
                    <td>
                        £<?= number_format(get_field('market_value_of_investments')) ?>m
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Shareholders' funds
                    </td>
                    <td>
                        £<?= number_format(get_field('total_shareholder_funds')) ?>m
                    </td>
                </tr>
                <tr>
                    <td>
                        Gearing
                    </td>
                    <td>
                        <?= get_field('gearing') ?>%
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="small mt-4">
            <strong>Notes</strong><br>
            Ordinary Share NAV includes current year revenue.<br>
            Past performance is not a guide to future performance, or a reliable indicator of future results or performance.
        </div>
    </div>
</div>