<section class="home_hero">
    <div class="container-xl">
        <div class="row g-5">
            <div class="col-lg-8">
                <h1 class="mb-5"><?= get_field('title') ?></h1>
                <img src="<?= get_stylesheet_directory_uri() ?>/img/scale.svg" alt="">
            </div>
            <div class="col-lg-4">
                <?= wp_get_attachment_image(get_field('image'), 'full') ?>
            </div>
        </div>
    </div>
</section>