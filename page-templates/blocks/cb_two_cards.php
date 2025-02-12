<?php
$card1_link = get_field('card1_link') ?? null;
$card2_link = get_field('card2_link') ?? null;

$bg = get_field('background') == 'Grey' ? '' : 'two_cards--blue';
$classes = $block['className'] ?? null;

?>
<section class="two_cards py-5 <?= $bg ?> <?= $classes ?>">
    <div class="container-xl">
        <div class="row g-5">
            <div class="col-md-6">
                <div class="two_cards__card">
                    <?php
                    if (get_field('card1_icon') ?? null) {
                        echo wp_get_attachment_image(get_field('card1_icon'), 'full', false, array('class' => 'two_cards__icon'));
                    } else {
                        echo '<div class="two_cards__icon"></div>';
                    }
                    ?>
                    <div class="two_cards__inner">
                        <h3><?= get_field('card1_title') ?></h3>
                        <p><?= get_field('card1_intro') ?></p>
                        <?php
                        if ($card1_link) {
                        ?>
                            <a href="<?= $card1_link['url'] ?>" target="<?= $card1_link['target'] ?>" class="button button-secondary align-self-start"><?= $card1_link['title'] ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="two_cards__card">
                    <?php
                    if (get_field('card2_icon') ?? null) {
                        echo wp_get_attachment_image(get_field('card2_icon'), 'full', false, array('class' => 'two_cards__icon'));
                    } else {
                        echo '<div class="two_cards__icon"></div>';
                    }
                    ?>
                    <div class="two_cards__inner">
                        <h3><?= get_field('card2_title') ?></h3>
                        <p><?= get_field('card2_intro') ?></p>
                        <?php
                        if ($card2_link) {
                        ?>
                            <a href="<?= $card2_link['url'] ?>" target="<?= $card2_link['target'] ?>" class="button button-secondary align-self-start"><?= $card2_link['title'] ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>