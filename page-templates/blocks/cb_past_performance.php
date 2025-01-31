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
if (!function_exists('parse_csv_to_array')) {
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
}

function render_csv_as_table($csv_data, $tab)
{
    if (empty($csv_data) || isset($csv_data['error'])) {
        return '<p>Error loading CSV data.</p>';
    }

    // Identify column indices to exclude
    $excluded_columns = [];
    foreach ($csv_data[0] as $index => $header) {
        if ($header === 'MonthendDate') {
            $excluded_columns[] = $index;
        }
        if ($header === 'Monthend') {
            $excluded_columns[] = $index;
        }
        if ($header === 'PerfDate') {
            $excluded_columns[] = $index;
        }
    }

    $html = '<div class="table-responsive"><table class="table mt-3">';

    // Output header row
    $html .= '<thead><tr>';
    foreach ($csv_data[0] as $index => $header) {
        if (!in_array($index, $excluded_columns)) {

            $tclass = ' class="text-end"';
            if ($header === 'PerfPeriod') {
                $header = 'Period';
                $tclass = '';
            }
            elseif ($header === 'DNSCI') {
                $header = 'DNSCI (XIC)';
            }

            if ($tab != 'discrete' && $header == 'PerformancePeriod') {
                $date = $csv_data[1][4];
                $date = new DateTime($date);
                $formattedDate = $date->format("d F Y");
                $header = 'Period to ' . $formattedDate;
                $tclass = '';
            }

            $html .= '<th' . $tclass . '>' . htmlspecialchars($header) . '</th>';
        }
    }
    $html .= '</tr></thead>';

    // Output data rows
    $html .= '<tbody>';
    for ($i = 1; $i < count($csv_data); $i++) {
        $html .= '<tr>';
        $tclass = '';
        foreach ($csv_data[$i] as $index => $cell) {
            if (!in_array($index, $excluded_columns)) {
                if (is_numeric($cell)) {
                    $formatted_value = number_format((float) $cell * 100, 1, '.', '');
                    if ((float) $formatted_value < 0) {
                        $formatted_value = '(' . number_format(abs($formatted_value), 1, '.', '') . ')';
                    }
                    $html .= '<td' . $tclass . '>' . htmlspecialchars($formatted_value) . '</td>';
                }
                else {
                    $html .= '<td' . $tclass . '>' . htmlspecialchars($cell) . '</td>';
                }
            }
            $tclass = ' class="text-end"';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table></div>';

    return $html;
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
        <?php
        if (get_field('preamble') ?? null) {
        ?>
            <div class="mb-4"><?= get_field('preamble') ?></div>
        <?php
        }
        ?>
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
                    echo render_csv_as_table($table_data, $tab_ids[$index]);
                }

                echo '</div>';
            }
            ?>
            <div class="small mt-4">
                <strong>Notes</strong><br>
                <?= get_field('notes') ?? null ?>
            </div>
        </div>
    </div>
</section>