<section class="gravityform">
    <div class="container-xl mx-auto">
        <?php
        if (get_field('form_id') ?? null) {
            echo do_shortcode('[gravityform id="' . get_field('form_id') . '" title="false"]');
        }
        ?>
    </div>
</section>