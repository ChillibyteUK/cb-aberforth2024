<section class="home_hero d-flex flex-column justify-content-center">
    <div class="container-xl">
        <div class="row g-5">
            <div class="col-lg-8 my-auto">
                <h1 class="mb-5"><?=get_field('title')?></h1>
                <img src="<?=get_stylesheet_directory_uri()?>/img/scale.svg" alt="">
            </div>
            <div class="col-lg-4 my-auto">
                <?=wp_get_attachment_image(get_field('image'),'full')?>
            </div>
        </div>
    </div>
</section>