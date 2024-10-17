<?php
$card1_link = get_field('card1_link') ?? null;
$card2_link = get_field('card2_link') ?? null;
$card3_link = get_field('card3_link') ?? null;

$has_bg = get_field('invert');
$bg = is_array($has_bg) && isset($has_bg[0]) && $has_bg[0] == 'Yes' ? 'three_cards--inverse' : '';

$class = $block['className'] ?? 'py-5';
?>
<section class="three_cards <?=$bg?> <?=$class?>">
    <div class="container-xl">
        <?php
        if (get_field('title') ?? null) {
            ?>
        <h2 class="text-center mb-4"><?=get_field('title')?></h2>
            <?php
        }
        if (get_field('intro') ?? null) {
            ?>
        <div class="text-center w-constrained mx-auto mb-5"><?=get_field('intro')?></div>
            <?php
        }
        ?>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="three_cards__card">
                    <?php
                    if (get_field('card1_icon') ?? null) {
                        echo wp_get_attachment_image(get_field('card1_icon'),'full',false,array('class' => 'three_cards__icon'));
                    }
                    else {
                        echo '<div class="three_cards__icon"></div>';
                    }
                    ?>
                    <h3><?=get_field('card1_title')?></h3>
                    <p><?=get_field('card1_intro')?></p>
                    <?php
                    if ($card1_link) {
                        ?>
                    <a href="<?=$card1_link['url']?>" target="<?=$card1_link['target']?>" class="button button-secondary"><?=$card1_link['title']?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="three_cards__card">
                    <?php
                    if (get_field('card2_icon') ?? null) {
                        echo wp_get_attachment_image(get_field('card2_icon'),'full',false,array('class' => 'three_cards__icon'));
                    }
                    else {
                        echo '<div class="three_cards__icon"></div>';
                    }
                    ?>
                    <h3><?=get_field('card2_title')?></h3>
                    <p><?=get_field('card2_intro')?></p>
                    <?php
                    if ($card2_link) {
                        ?>
                    <a href="<?=$card2_link['url']?>" target="<?=$card2_link['target']?>" class="button button-secondary"><?=$card2_link['title']?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="three_cards__card">
                    <?php
                    if (get_field('card3_icon') ?? null) {
                        echo wp_get_attachment_image(get_field('card3_icon'),'full',false,array('class' => 'three_cards__icon'));
                    }
                    else {
                        echo '<div class="three_cards__icon"></div>';
                    }
                    ?>
                    <h3><?=get_field('card3_title')?></h3>
                    <p><?=get_field('card3_intro')?></p>
                    <?php
                    if ($card3_link) {
                        ?>
                    <a href="<?=$card3_link['url']?>" target="<?=$card3_link['target']?>" class="button button-secondary"><?=$card3_link['title']?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>