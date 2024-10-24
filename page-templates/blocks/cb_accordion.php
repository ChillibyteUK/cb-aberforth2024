<?php
$r = random_str(4);
?>
<section class="cb_accordion py-5">
    <div class="container-xl">
        <div class="accordion" id="accordion_<?=$r?>">
            <?php
            $c = 0;
            while(have_rows('section')) {
                the_row();
                ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a<?=$r?>_<?=$c?>" aria-expanded="true" aria-controls="a<?=$r?>_<?=$c?>">
                        <?=get_sub_field('section_title')?>
                    </button>
                </h2>
                <div id="a<?=$r?>_<?=$c?>" class="accordion-collapse collapse" data-bs-parent="#accordion_<?=$r?>">
                    <div class="accordion-body p-3">
                        <?php
                        if (have_rows('acc_content')) {
                            while(have_rows('acc_content')) {
                                the_row();
                                if (get_row_layout() == 'table') {
                                    if (get_sub_field('title')) {
                                        ?>
                        <h3><?=get_sub_field('title')?></h3>
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
                                        }
                                        else {
                                            $name = get_sub_field('name');
                                            $value = get_sub_field('value');
                                            echo '<tr><td>' . esc_html($name) . '</td><td>' . esc_html($value) . '</td></tr>';
                                        }
                                    }
                                    echo '</tbody>';
                                    echo '</table>';
                                    echo '</div>';
                                }
                                elseif (get_row_layout() == 'embed') {
                                    ?>
                        <iframe src="<?=get_sub_field('source_url')?>" width="100%" height="700" frameborder="0"></iframe>
                                    <?php
                                }
                                else { // words
                                    echo get_sub_field('content');
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
                <?php
                $c++;
            }
            ?>
        </div>
    </div>
</section>