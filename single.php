<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
get_header();
$img = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>
<main id="main" class="blog">
    <?php
    $content = get_the_content();
    $blocks = parse_blocks($content);
    $sidebar = array();
    $after;
?>
    <section class="breadcrumbs container-xl">
        <?php
if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
}
?>
    </section>
    <div class="container-xl">
        <div class="row g-4 pb-4">
            <div class="col-lg-9">
                <h1 class="blog__title"><?=get_the_title()?></h1>
                <img src="<?=$img?>" alt="" class="blog__image">
                <?php
        $count = estimate_reading_time_in_minutes(get_the_content(), 200, true, true) ?? null;
        if ($count) {
            echo $count;
        }

foreach ($blocks as $block) {
    echo render_block($block);
}
?>
            </div>
            <div class="col-lg-3">
                <div class="sidebar">

                    <div class="h5 d-none d-lg-block">Related News</div>
                    <div class="related">
                        <?php
        $cats = get_the_category();
$ids = wp_list_pluck($cats, 'term_id');
$r = new WP_Query(array(
    'category__in' => $ids,
    'posts_per_page' => 4,
    'post__not_in' => array(get_the_ID())
));
while ($r->have_posts()) {
    $r->the_post();
    ?>
                        <a class="related__card"
                            href="<?=get_the_permalink()?>">
                            <img src="<?=get_the_post_thumbnail_url(get_the_ID(), 'large')?>"
                                alt="" class="related__image">
                            <div class="related__content">
                                <h3 class="related__title">
                                    <?=get_the_title()?>
                                </h3>
                            </div>
                        </a>
                        <?php
}
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
get_footer();
?>