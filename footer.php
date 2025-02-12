<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?>
<div id="footer-top"></div>
<footer class="footer py-5">
    <div class="container-xl">
        <?= wp_nav_menu(array('theme_location' => 'footer_menu1', 'container_class' => 'footer__menu')) ?>
        <div class="row g-5 pb-4">
            <div class="col-md-8">
                <?= get_field('footer_disclaimer', 'option') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                &copy; <?= date('Y') ?> Aberforth Partners LLP. Authorised and regulated by the <a href="https://www.fca.org.uk/" target="_blank">Financial Conduct Authority</a> in the United Kingdom.
            </div>
            <div class="col-md-4 text-end">
                <img src="<?= get_stylesheet_directory_uri() ?>/img/scale.svg" alt="">
            </div>
        </div>
</footer>
<?php
/*
<pre>
<?= get_option('ascot_pricing_data') ?>
<?= get_option('agvit_pricing_data') ?>
</pre>
*/
?>
<?php wp_footer(); ?>
</body>

</html>