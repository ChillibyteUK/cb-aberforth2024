<?php
$r = random_str(4);
$classes = $block['className'] ?? null;
?>
<section class="cb_accordion py-5 <?= $classes ?>">
    <div class="container-xl">
        <div class="accordion" id="accordion_<?= $r ?>">
            <?php
            $c = 0;
            $expanded = "false";
            $collapsed = "collapsed";
            $show = '';
            while (have_rows('section')) {
                the_row();
                $slug = acf_slugify(get_sub_field('section_title'));
            ?>
                <div class="accordion-item" id="<?= $slug ?>">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?= $collapsed ?>" type="button" data-bs-toggle="collapse" data-bs-target="#a<?= $r ?>_<?= $c ?>" aria-expanded="<?= $expanded ?>" aria-controls="a<?= $r ?>_<?= $c ?>">
                            <?= get_sub_field('section_title') ?>
                        </button>
                    </h2>
                    <div id="a<?= $r ?>_<?= $c ?>" class="accordion-collapse collapse <?= $show ?>" data-bs-parent="#accordion_<?= $r ?>">
                        <div class="accordion-body p-3">
                            <?php
                            if (have_rows('acc_content')) {
                                while (have_rows('acc_content')) {
                                    the_row();
                                    if (get_row_layout() == 'table') {
                                        if (get_sub_field('title')) {
                            ?>
                                            <h3><?= get_sub_field('title') ?></h3>
                                        <?php
                                        }
                                        echo '<div class="table-responsive mb-3">';
                                        echo '<table class="table">';
                                        $first = true;
                                        while (have_rows('rows')) {
                                            the_row();
                                            if ($first) {
                                                // Output thead from the first row
                                                $name_header = get_sub_field('name');
                                                $value_header = get_sub_field('value');
                                                echo '<thead><tr><th>' . esc_html($name_header) . '</th><th>' . esc_html($value_header) . '</th></tr></thead>';
                                                echo '<tbody>';
                                                $first = false;
                                            } else {
                                                $name = get_sub_field('name');
                                                $value = get_sub_field('value');
                                                echo '<tr><td>' . esc_html($name) . '</td><td>' . esc_html($value) . '</td></tr>';
                                            }
                                        }
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                    } elseif (get_row_layout() == 'embed') {
                                        ?>
                                        <iframe src="<?= get_sub_field('source_url') ?>" width="100%" height="900" frameborder="0"></iframe>
                                        <?php
                                    } elseif (get_row_layout() == 'cards') {
                                        if (get_sub_field('title')) {
                                        ?>
                                            <h3><?= get_sub_field('title') ?></h3>
                                        <?php
                                        }
                                        $link1 = get_sub_field('cta_card1') ?? null;
                                        $link2 = get_sub_field('cta_card2') ?? null;
                                        $link3 = get_sub_field('cta_card3') ?? null;
                                        ?>
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="fund_accordion__card d-grid">
                                                    <div>
                                                        <h3 class="mb-4"><?= get_sub_field('title_card1') ?></h3>
                                                        <p><?= get_sub_field('content_card1') ?></p>
                                                    </div>
                                                    <?php
                                                    if ($link1) {
                                                    ?>
                                                        <a href="<?= $link1['url'] ?>" target="<?= $link1['target'] ?>" class="button button-secondary align-self-end"><?= $link1['title'] ?></a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fund_accordion__card">
                                                    <h3 class="mb-4"><?= get_sub_field('title_card2') ?></h3>
                                                    <p><?= get_sub_field('content_card2') ?></p>
                                                    <?php
                                                    if ($link2) {
                                                    ?>
                                                        <a href="<?= $link2['url'] ?>" target="<?= $link2['target'] ?>" class="button button-secondary align-self-end"><?= $link2['title'] ?></a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fund_accordion__card d-grid">
                                                    <div>
                                                        <h3 class="mb-4"><?= get_sub_field('title_card3') ?></h3>
                                                        <p><?= get_sub_field('content_card3') ?></p>
                                                    </div>
                                                    <?php
                                                    if ($link3) {
                                                    ?>
                                                        <a href="<?= $link3['url'] ?>" target="<?= $link3['target'] ?>" class="button button-secondary align-self-end"><?= $link3['title'] ?></a>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                            <?php
                                    } else { // words
                                        echo get_sub_field('content');
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
                $expanded = 'false';
                $collapsed = 'collapsed';
                $show = '';
                $c++;
            }
            ?>
        </div>
    </div>
</section>