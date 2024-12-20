<?php
$theme = strtolower(get_field('theme'));
$bg = get_field('background') == 'White' ? 'fund_nav--white' : '';
?>
<section class="fund_nav <?= $bg ?> py-5">
    <div class="container-xl">
        <div class="text-center">
            <h2>Explore Aberforth Trusts &amp; Funds</h2>
            <div class="w-constrained mx-auto mb-4">
                Aberforth currently manages two investment trusts and one unit trust. All are managed in accordance with a value style and focus on UK smaller companies.
            </div>
        </div>
        <div class="row g-4">
            <?php
            if ($theme != 'ascot') {
            ?>
                <div class="col-md-4">
                    <div class="fund_nav__card fund_nav--ascot">
                        <h3>Aberforth Smaller Companies Trust plc</h3>
                        <a href="/trusts-and-funds/aberforth-smaller-companies-trust-plc/" class="button button-secondary button--arrow">Learn more</a>
                    </div>
                </div>
            <?php
            }
            if ($theme != 'agvit') {
            ?>
                <div class="col-md-4">
                    <div class="fund_nav__card fund_nav--agvit">
                        <h3>Aberforth Geared Value &amp; Income Trust plc</h3>
                        <a href="/trusts-and-funds/aberforth-geared-value-income-trust-plc/" class="button button-secondary button--arrow">Learn more</a>
                    </div>
                </div>
            <?php
            }
            if ($theme != 'aslit') {
            ?>
                <div class="col-md-4">
                    <div class="fund_nav__card fund_nav--aslit">
                        <h3>Aberforth Split Level Income Trust plc</h3>
                        <a href="/trusts-and-funds/aberforth-split-level-income-trust-plc/" class="button button-secondary button--arrow">Learn more</a>
                    </div>
                </div>
            <?php
            }
            if ($theme != 'afund') {
            ?>
                <div class="col-md-4">
                    <div class="fund_nav__card fund_nav--afund">
                        <h3>Aberforth UK Small Companies Fund</h3>
                        <a href="/trusts-and-funds/aberforth-uk-small-companies-fund/" class="button button-secondary button--arrow">Learn more</a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>