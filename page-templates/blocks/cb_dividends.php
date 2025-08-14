<?php

// Get the filename from user input
$theme = get_field('theme');
$filename = $theme . '_Dividends.csv';
$filepath = $_SERVER['DOCUMENT_ROOT'] . '/feed/' . $filename;

// Read the CSV file
if (!file_exists($filepath) || !is_readable($filepath)) {
    echo "Unable to open the file.";
    exit;
}

$data = array_map('str_getcsv', file($filepath));
$headers = array_shift($data);

$years = [];
$interim = [];
$final = [];
$special = [];

foreach ($data as $row) {
    $years[] = $row[0];
    $interim[] = $row[1] !== '' ? (float)$row[1] : 0;
    $final[] = $row[2] !== '' ? (float)$row[2] : 0;
    $special[] = $row[3] !== '' ? (float)$row[3] : 0;
}

$json_data = [
    'labels' => $years,
    'datasets' => [
        [
            'label' => 'Interim',
            'data' => $interim,
            'backgroundColor' => '#173150',
            'stack' => 'stack1',
			'maxBarThickness' => '100',
        ],
        [
            'label' => 'Final',
            'data' => $final,
            'backgroundColor' => '#cae2ff',
            'stack' => 'stack1',
			'maxBarThickness' => '100',

        ]
    ]
];

if (array_sum($special) > 0) {
    $json_data['datasets'][] = [
        'label' => 'Special',
        'data' => $special,
        'backgroundColor' => '#e5d7b2',
        'stack' => 'stack1',
		'maxBarThickness' => '100',
    ];
}

$class = $block['className'] ?? null;

?>
<section class="dividends py-5 <?=$class?>">
    <div class="container-xl">
        <h2>Payment History</h2>
        <h3>Dividend history since inception</h3>
        <div id="chartContainer" data-chart="<?= htmlspecialchars(json_encode($json_data), ENT_QUOTES, 'UTF-8') ?>"></div>
        <div class="small mt-4">
            <strong>Notes</strong><br>
            <?= get_field('notes') ?? null ?>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js configuration to create a stacked bar chart from the data in the chartContainer

    document.addEventListener('DOMContentLoaded', function() {
        const chartContainer = document.getElementById('chartContainer');
        if (chartContainer) {
            const jsonData = JSON.parse(chartContainer.getAttribute('data-chart'));

            const ctx = document.createElement('canvas');
            chartContainer.appendChild(ctx);

            const options = {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Dividend (p)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            };

            new Chart(ctx, {
                type: 'bar',
                data: jsonData,
                options: options
            });
        }
    });
</script>