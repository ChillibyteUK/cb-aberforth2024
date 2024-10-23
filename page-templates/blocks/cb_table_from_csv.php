<?php
$class = $block['className'] ?? 'py-5';
$bg = get_field('background') ?? '';
?>
<section class="table_from_csv <?=$class?> <?=$bg?>">
    <div class="container-xl">
        <?php

        // Get the file ID from ACF field 'portfolio_file'
        $file_id = get_field('csv_file');

        // Get the file path from the attachment ID
        if (!$file_id) {
            echo "<p>Error: No file selected.</p>";
            return;
        }

        $file_path = get_attached_file($file_id);

        if (!$file_path || !file_exists($file_path) || !is_readable($file_path)) {
            echo "<p>Error: Unable to open the file.</p>";
            return;
        }

        $file_creation_date = date("d/m/Y", filemtime($file_path));

        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            echo "<p>Error: Unable to read the file.</p>";
            return;
        }

        // Read the CSV file into an array
        $data = [];
        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            $data[] = $row;
        }
        fclose($handle);

        if (empty($data)) {
            echo "<p>No data available in the file.</p>";
            return;
        }

        // Get header from the first row of the data
        $header = $data[0];
        $rows_to_display = array_slice($data, 1);

        if (get_field('title') ?? null) {
            ?>
        <h2><?=get_field('title')?></h2>
            <?php
        }
        if (get_field('sub_title') ?? null) {
            ?>
        <h3><?=get_field('sub_title')?></h3>
            <?php
        }
        ?>
        <table id='csvTable' class='table'>
            <thead>
                <tr>
                    <?php foreach ($header as $column_name): ?>
                        <th><?= htmlspecialchars($column_name) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows_to_display as $index => $row) {
                    $hidden_class = ($index >= 10) ? ' class="hidden-row" style="display:none;"' : '';
                    echo "<tr$hidden_class>";
                    foreach ($row as $i => $cell) {
                        if (is_numeric($cell)) {
                            $cell = number_format(floatval($cell), 0, '.', ',');
                        }
                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <?php
        // Display a link to show the full table
        if (count($data) > 10) {
            echo "<a href='#' id='showFullTable'>Show Full Table (" . (count($rows_to_display) - 10) . " Rows)</a>";
        }

        ?>
    </div>
</section>
<script>
    document.getElementById('showFullTable').addEventListener('click', function(event) {
        event.preventDefault();
        const hiddenRows = document.querySelectorAll('.hidden-row');
        hiddenRows.forEach(function(row) {
            row.style.display = 'table-row';
        });
        this.style.display = 'none';
    });
</script>
