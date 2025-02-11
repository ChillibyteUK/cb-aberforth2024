<?php
$r = random_str(4);
$classes = $block['className'] ?? null;

?>
<section class="video_disclaimer py-5 <?= $classes ?>">
    <div class="container-xl">
        <?php
        if (get_field('title') ?? null) {
        ?>
            <h2><?= get_field('title') ?></h2>
        <?php
        }
        if (get_field('subtext') ?? null) {
        ?>
            <div><?= get_field('subtext') ?></div>
        <?php
        }
        $vimeo_id = get_field('vimeo_id') ?? null;
        if ($vimeo_id) {
            $img = get_vimeo_data_from_id($vimeo_id, 'thumbnail_url_with_play_button');
        ?>
            <div id="player<?= $r ?>" type="button" data-bs-toggle="modal" data-bs-target="#modal<?= $r ?>" class="mt-4 ratio ratio-16x9">
                <img src="<?= $img ?>" class="img-fluid">
            </div>
            <div id="video<?= $r ?>" class="mt-4 ratio ratio-16x9 d-none">
                <!-- The iframe will be created dynamically -->
            </div>
        <?php
        }
        ?>
    </div>
    <div class="modal fade" id="modal<?= $r ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"><?= get_field('disclaimer_header') ?></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= get_field('disclaimer_copy') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button button-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="button accept-button">Accept</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.accept-button').addEventListener('click', function() {
            // Hide the player div and show the video div
            document.getElementById('player<?= $r ?>').style.display = 'none';
            document.getElementById('video<?= $r ?>').classList.remove('d-none');

            // Hide the modal using vanilla JavaScript
            document.getElementById('modal<?= $r ?>').style.display = 'none';
            document.querySelector('.modal-backdrop').remove();
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';

            // Create the Vimeo iframe dynamically and add autoplay
            var iframe = document.createElement('iframe');
            iframe.src = "https://player.vimeo.com/video/<?= $vimeo_id ?>?autoplay=1";
            iframe.allow = "autoplay; fullscreen; picture-in-picture";

            document.getElementById('video<?= $r ?>').appendChild(iframe);
        });
    });
</script>