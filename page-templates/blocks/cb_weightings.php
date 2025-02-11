<?php
$theme = get_field('theme');

$fund_desc = array(
    'ASCOT' => 'Aberforth Smaller Companies Trust plc',
    'AFUND' => 'Aberforth UK Small Companies Fund',
    'ASLIT' => 'Aberforth Split Level Income Trust plc',
    'AGVIT' => 'Aberforth Geared Value & Income Trust plc'
);

$filename = $theme . '_IndustryWeights.csv';
$filepath = $_SERVER['DOCUMENT_ROOT'] . '/feed/' . $filename;

if (!file_exists($filepath) || !is_readable($filepath)) {
    echo json_encode(["error" => "Unable to open the file."]);
    return;
}

// Open CSV and extract DataDate from first data row
$handle = fopen($filepath, 'r');
if ($handle === false) {
    echo json_encode(["error" => "Unable to read the file."]);
    return;
}

$data = [];
while (($row = fgetcsv($handle, 1000, ",")) !== false) {
    $data[] = $row;
}
fclose($handle);

if (empty($data) || count($data) < 2) {
    echo json_encode(["error" => "No data available in the file."]);
    return;
}

// Extract DataDate from first data row (line 2, position 0)

$file_creation_date = isset($data[1][0]) ? date("d/m/Y", strtotime($data[1][0])) : "Unknown Date";

$classes = $block['className'] ?? null;

?>
<section class="weightings py-5 <?= $classes ?>">
    <div class="container-xl">
        <h2>Industry Weightings</h2>

        <?php
        // Read the CSV file into an array again to process data
        $header = $data[0];
        $labels = [];
        $datasets = [];
        $dataset_indices = [];
        $industry_index = null;

        // Clean header names to remove non-printable characters
        foreach ($header as $index => $column_name) {
            $column_name = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $column_name));
            if (strcasecmp($column_name, 'IndustryDescription') === 0) {
                $industry_index = $index;
            } elseif (strcasecmp($column_name, 'DataDate') !== 0) {
                $dataset_indices[$column_name] = $index;
            }
        }

        // Reorder dataset labels to Fund, HGSC, FTAS
        $original_labels = ['Fund', 'HGSC', 'FTAS'];
        $datasets = [
            [
                "label" => $theme,
                "data" => []
            ],
            [
                "label" => "DNSCI (XIC)",
                "data" => []
            ],
            [
                "label" => "FTAS (XIC)",
                "data" => []
            ]
        ];

        // Extract data for labels and datasets
        foreach ($data as $index => $row) {
            if ($index === 0) {
                continue; // Skip the header row
            }
            if (isset($row[$industry_index])) {
                $labels[] = trim($row[$industry_index]);
            }
            foreach ($datasets as $i => $dataset) {
                $original_label = $original_labels[$i];
                $column_index = $dataset_indices[$original_label];
                $value = isset($row[$column_index]) ? number_format(floatval(trim($row[$column_index])), 2, '.', '') : "0.00";
                $datasets[$i]['data'][] = $value;
            }
        }

        // Create JSON structure
        $json_data = [
            "labels" => $labels,
            "yTitle" => "Weight (%)",
            "datasets" => $datasets,
            "type" => "bar"
        ];
        ?>
        <div class="h2">Weightings as at <?= htmlspecialchars($file_creation_date) ?></div>
        <div id="chartContainer" data-chart="<?= htmlspecialchars(json_encode($json_data), ENT_QUOTES, 'UTF-8') ?>"></div>
        <div class="mt-4 small">
            <strong>Notes:</strong><br>
            Hover cursor over bars above to see underlying values.<br>
            The above weightings are calculated as a percentage of total investments.<br>
            <?= $theme ?> = <?= $fund_desc[$theme] ?><br>
            DNSCI (XIC) = Deutsche Numis Smaller Companies Index (Excluding Investment Companies)<br>
            FTAS (XIC) = FTSE All-Share Index (Excluding Investment Companies)
        </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartContainer = document.getElementById('chartContainer');
        const chartData = JSON.parse(chartContainer.getAttribute('data-chart'));

        const ctx = document.createElement('canvas');
        chartContainer.appendChild(ctx);

        new Chart(ctx, {
            type: chartData.type,
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets.map((dataset, index) => ({
                    label: dataset.label,
                    data: dataset.data,
                    backgroundColor: ['#173150', '#cae2ff', '#e5d7b2'][index % 3],
                    borderWidth: 1
                }))
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: chartData.yTitle
                        }
                    }
                }
            }
        });
    });
</script>