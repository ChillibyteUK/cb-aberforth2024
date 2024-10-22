<?php
$class = $block['className'] ?? 'py-5';
$bg = get_field('background') ?? '';
?>
<div class="section data_table <?= $class ?> <?= $bg ?>">
    <div class="container-xl">
        <?php
        if (get_field('main_title') ?? null) {
        ?>
            <h2 class="mb-4"><?= get_field('main_title') ?></h2>
        <?php
        }
        if (get_field('title') ?? null) {
        ?>
            <h3><?= get_field('title') ?></h3>
        <?php
        }
        if (have_rows('rows')) {
            echo '<table class="table">';
            // Output thead from the first row
            the_row();
            $name_header = get_sub_field('name');
            $value_header = get_sub_field('value');
            echo '<thead><tr><th>' . esc_html($name_header) . '</th><th>' . esc_html($value_header) . '</th></tr></thead>';
            echo '<tbody>';
            // Iterate over the rest of the rows
            while (have_rows('rows')) {
                the_row();
                $name = get_sub_field('name');
                $value = get_sub_field('value');
                echo '<tr><td>' . esc_html($name) . '</td><td>' . esc_html($value) . '</td></tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        if (get_field('notes') ?? null) {
        ?>
            <div class="small mt-4">
                <strong>Notes</strong><br>
                <?= get_field('notes') ?>
            </div>
        <?php
        }
        ?>
    </div>
</div>