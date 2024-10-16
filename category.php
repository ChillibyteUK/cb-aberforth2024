<?php
/* Template Name: Guides Index */
// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>
<main id="main">
<header class="hero hero--short">
    <div class="hero_bg" style="background-image: url(/wp-content/uploads/2023/01/hero-home.jpg)"></div>
    <div class="container-xl">
        <h1><span><?=single_cat_title()?></span> Guides</h1>
    </div>
</header>
<div class="container-xl pb-5">
    <div class="row g-4 mb-4">
        <?php
    while (have_posts()) {
        the_post();
        ?>
        <div class="col-lg-3">
            <a class="blog_card" href="<?=get_the_permalink()?>">
                <img src="<?=get_the_post_thumbnail_url(get_the_ID(),'large')?>" alt="" class="blog_card__image">
                <div class="blog_card__content">
                    <h3 class="blog_card__title"><?=get_the_title()?></h3>
                </div>
            </a>
        </div>
        <?php
    }
    ?>
    </div>
    <?=numeric_posts_nav()?>
</div>
</main>
<?php

get_footer();
