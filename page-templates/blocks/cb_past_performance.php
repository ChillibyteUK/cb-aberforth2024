<?php

// Get the theme value from ACF field
$theme = get_field('theme');

// File paths for each CSV
$csv_files = [
    $theme . '_DiscretePerformance.csv',
    $theme . '_CompoundPerformance.csv',
    $theme . '_CumulativePerformance.csv'
];

// Function to parse CSV into an array
function parse_csv_to_array($filename)
{
    $filepath = $_SERVER['DOCUMENT_ROOT'] . '/feed/' . $filename;

    if (!file_exists($filepath) || !is_readable($filepath)) {
        return ["error" => "Unable to open the file."];
    }

    $handle = fopen($filepath, 'r');
    if ($handle === false) {
        return ["error" => "Unable to read the file."];
    }

    $data = [];
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        $data[] = $row;
    }
    fclose($handle);

    return $data;
}

// Parsing CSV files
$parsed_data = [];
foreach ($csv_files as $file) {
    $parsed_data[] = parse_csv_to_array($file);
}
?>
<section class="past_performance py-5">
    <div class="container-xl">
        <h2>Past Performance</h2>
        <ul class="nav nav-tabs" id="performanceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="discrete-tab" data-bs-toggle="tab" data-bs-target="#discrete" type="button" role="tab" aria-controls="discrete" aria-selected="true">Discrete %</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="compound-tab" data-bs-toggle="tab" data-bs-target="#compound" type="button" role="tab" aria-controls="compound" aria-selected="false">Compound %</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cumulative-tab" data-bs-toggle="tab" data-bs-target="#cumulative" type="button" role="tab" aria-controls="cumulative" aria-selected="false">Cumulative %</button>
            </li>
        </ul>
        <div class="tab-content" id="performanceTabsContent">
            <?php
            $tab_ids = ['discrete', 'compound', 'cumulative'];
            foreach ($parsed_data as $index => $table_data) {
                $is_active = $index === 0 ? 'show active' : '';
                echo '<div class="tab-pane fade ' . $is_active . '" id="' . $tab_ids[$index] . '" role="tabpanel" aria-labelledby="' . $tab_ids[$index] . '-tab">';

                if (isset($table_data['error'])) {
                    echo '<p>' . $table_data['error'] . '</p>';
                } else {
                    echo '<div class="table-responsive"><table class="table mt-3">';
                    // Output headers
                    if (!empty($table_data)) {
                        echo '<thead><tr>';
                        $ordered_columns = ['PerformancePeriod', 'Share Price', 'NAV', 'NSCI'];
                        $column_indices = [];
                        foreach ($ordered_columns as $col) {
                            foreach ($table_data[0] as $index => $header) {
                                if (strcasecmp(str_replace(' ', '', $header), str_replace(' ', '', $col)) === 0) {
                                    // Update the header to match desired naming
                                    $display_header = $header;
                                    if ($header === 'PerformancePeriod') {
                                        $display_header = 'Period';
                                    } elseif ($header === 'NSCI') {
                                        $display_header = 'NSCI (XIC)';
                                    }
                                    echo '<th>' . htmlspecialchars($display_header) . '</th>';
                                    $column_indices[$col] = $index;
                                }
                            }
                        }
                        echo '</tr></thead><tbody>';
                        // Output rows
                        for ($i = 1; $i < count($table_data); $i++) {
                            echo '<tr>';
                            foreach ($ordered_columns as $col) {
                                if (isset($column_indices[$col])) {
                                    $index = $column_indices[$col];
                                    $cell = $table_data[$i][$index];
                                    if ($col === 'PerformancePeriod') {
                                        // First column is text, output as is
                                        $formatted_value = $cell;
                                    } else {
                                        // Format numerical values
                                        // $formatted_value = number_format((float) $cell, 1);
                                        $formatted_value = number_format((float) $cell * 100, 1);
                                        if ($formatted_value < 0) {
                                            $formatted_value = '(' . abs($formatted_value) . ')';
                                        }
                                    }
                                    echo '<td>' . htmlspecialchars($formatted_value) . '</td>';
                                }
                            }
                            echo '</tr>';
                        }
                        echo '</tbody>';
                    }
                    echo '</table></div>';
                }

                echo '</div>';
            }
            ?>
            <div class="small mt-4">
                <strong>Notes</strong><br>
                <?= get_field('notes') ?>
                <div class="mt-4">Past performance is not a guide to future performance, or a reliable indicator of future results or performance.</div>
            </div>
        </div>
    </div>
</section>