<?php

$headings = get_gutenberg_h2_headings_from_page();

$class = $block['className'] ?? ' mb-5';

?>
<section class="inpage_hero bg--blue-200 <?=$class?>">
    <div class="container-xl">
        <nav class="inpage-nav">
        <?php
        foreach ( $headings as $m ) {
            if ($m['id'] ?? null) {
            ?>
            <a href="#<?=$m['id']?>"><?=$m['content']?></a>
            <?php
            }
        }
        ?>
        </nav>
    </div>
</section>