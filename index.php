<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

$page_for_posts = get_option('page_for_posts');
$bg = get_the_post_thumbnail_url($page_for_posts, 'full');

get_header();
?>
<main id="main" class="theme--blue">
    <section class="hero hero--short" data-parallax="scroll"
        data-image-src="<?=get_the_post_thumbnail_url(get_option('page_for_posts'), 'full')?>">
        <div class="container-bg bg--left">
            <div class="container-xl pe-0">
                <div class="hero__content">
                    <h1 data-aos="fade-right">Reports &amp; News</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-xl py-5">
        <?php
        if (get_the_content(null, false, $page_for_posts)) {
            echo '<div class="mb-5">' . get_the_content(null, false, $page_for_posts) . '</div>';
        }

        /*
        $cats = get_categories(array('exclude' => array(32)));
        ?>
        <div class="filters mb-4">
            <?php
        echo '<button class="btn btn-outline-primary active me-2 mb-2" data-filter="*">All</button>';
        foreach ($cats as $cat) {
            echo '<button class="btn btn-outline-primary me-2 mb-2" data-filter=".' . cbslugify($cat->name) . '">' . $cat->cat_name . '</button>';
        }
        ?>
        </div>
        <?php
        */
?>
        <div class="row w-100" id="newsGrid">
            <?php
    while (have_posts()) {
        the_post();
        $img = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$img) {
            $img = get_stylesheet_directory_uri() . '/img/default-blog.jpg';
        }
        $cats = get_the_category();
        $category = wp_list_pluck($cats, 'name');
        $flashcat = cbslugify($category[0]);
        $catClass = implode(' ', array_map('cbslugify', $category));
        $category = implode(', ', $category);

        if (has_category('event')) {
            $the_date = get_field('start_date', get_the_ID());
        } else {
            $the_date = get_the_date('jS F, Y');
        }

        ?>
            <div
                class="grid_item col-lg-4 col-md-6 px-1 <?=$catClass?>">
                <a href="<?=get_the_permalink()?>"
                    class="news_grid__item mb-2 mx-1"
                    style="background-image:url(<?=$img?>)"
                    data-aos="fade">
                    <div class="overlay <?=$catClass?>"></div>
                    <!-- div class="catflash <?=$catClass?>">
                    <?=$flashcat?>
            </div -->
            <h3><?=get_the_title()?></h3>
            <div class="news_meta">
                <div class="news_meta__date">
                    <?=get_the_date('j F Y')?>
                </div>
            </div>
            </a>
        </div>
        <?php
    }
?>
    </div>
    <!--        <div class="mt-5">
        <?php
        // numeric_posts_nav();
?>
    </div>
    -->
    </div>
</main>
<?php
add_action('wp_footer', function () {
    ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"
    integrity="sha512-Zq2BOxyhvnRFXu0+WE6ojpZLOU2jdnqbrM1hmVdGzyeCa1DgM3X5Q4A/Is9xA1IkbUeDd7755dNNI/PzSf2Pew=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    (function($) {

        var $grid = $('#newsGrid').isotope({
            itemSelector: '.grid_item',
            percentPosition: true,
            layoutMode: 'fitRows',
        });

        $('.filters').on('click', 'button', function() {
            var filterValue = $(this).attr('data-filter');
            $('.filters').find('.active').removeClass('active');
            $(this).addClass('active');
            $grid.isotope({
                filter: filterValue
            });
        });



    })(jQuery);
</script>
<?php
}, 9999);

get_footer();
?>