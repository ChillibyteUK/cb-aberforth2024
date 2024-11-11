<?php
$l = get_field('cta_link');
?>
<section class="wide_cta py-5">
    <div class="container-xl py-4 text-center">
        <h2><?=get_field('cta_title')?></h2>
        <div class="w-constrained mx-auto pb-4"><?=get_field('cta_content')?></div>
        <a href="<?=$l['url']?>" target="<?=$l['target']?>" class="button"><?=$l['title']?></a>
    </div>
</section>