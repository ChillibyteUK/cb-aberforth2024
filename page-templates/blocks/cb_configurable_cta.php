<?php
$l = get_field('link');
$classes = $block['className'] ?? null;
?>
<section class="wide_cta py-5 <?= $classes ?>">
    <div class="container-xl py-4 text-center">
        <h2><?= get_field('title') ?></h2>
        <div class="w-constrained mx-auto pb-4"><?= get_field('content') ?></div>
        <a href="<?= $l['url'] ?>" target="<?= $l['target'] ?>" class="button"><?= $l['title'] ?></a>
    </div>
</section>