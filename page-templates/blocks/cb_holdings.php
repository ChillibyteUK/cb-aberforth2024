<section class="holdings mt-5 py-5">
    <div class="container-xl">
        <?php

        $theme = get_field('theme');

        $filename = $theme . '_PortfolioHoldings.csv';

        $filepath = $_SERVER['DOCUMENT_ROOT'] . '/feed/' . $filename;

        if (!file_exists($filepath) || !is_readable($filepath)) {
            echo "<p>Error: Unable to open the file.</p>";
            return;
        }

        // $file_creation_date = date("d/m/Y", filemtime($filepath));

        $handle = fopen($filepath, 'r');
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

        // Display all rows but only show the first 10 initially
        // $header = $data[0];
        // $rows_to_display = array_slice($data, 1);

        $header = array_slice($data[0], 0, 3);
        $rows_to_display = array_slice($data, 1);

        $file_creation_date = isset($data[1]) ? date('d/m/Y', strtotime($data[1][array_search('HoldingDate', $data[0])])) : null;

        ?>
        <h2>Holdings as at <?= $file_creation_date ?></h2>
        <table id='csvTable' class='table'>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Security</th>
                    <th>Portfolio Weight</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows_to_display as $index => $row) {
                    $hidden_class = ($index >= 10) ? ' class="hidden-row" style="display:none;"' : '';
                    echo "<tr$hidden_class>";
                    foreach (array_slice($row, 0, 3) as $i => $cell) {
                        if ($i == 2) { // Assuming the third column is PortfolioWeight
                            $cell = number_format(floatval($cell), 1, '.', '') . '%';
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
            echo "<a href='#' id='showFullTable'>Show Full Table (" . (count($rows_to_display) - 10) . ") Rows)</a>";
        }

        ?>
        <div class="mt-3">
            <small><strong>Note:</strong> The above weightings are calculated as a percentage of total investments.</small>
        </div>
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