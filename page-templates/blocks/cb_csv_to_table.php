<?php
$class = $block['className'] ?? 'py-5';
$bg = get_field('background') ?? '';
?>
<section class="csv_to_table <?= $class ?> <?= $bg ?>">
    <div class="container-xl">
<?php
if (get_field('title') ?? null) {
    echo '<h2>' . get_field('title') . '</h2>';
}

$csv_input = get_field('data');

$rows = array_map("str_getcsv", explode("\n", $csv_input));
$rows = array_filter($rows, function($row) { return !empty(array_filter($row)); }); // Remove empty rows

if (empty($rows)) {
    return '<p>No data available.</p>';
}

$html = '<div class="table-responsive"><table class="table table-sm">';

// Output header row
$html .= '<thead><tr>';
foreach ($rows[0] as $index => $header) {
    $class = ($index === 0) ? '' : ' class="text-end"';
    $html .= '<th' . $class . '>' . htmlspecialchars(trim($header)) . '</th>';
}
$html .= '</tr></thead>';

// Output data rows
$html .= '<tbody>';
for ($i = 1; $i < count($rows); $i++) {
    $html .= '<tr>';
    foreach ($rows[$i] as $index => $cell) {
        $class = ($index === 0) ? '' : ' class="text-end"';
        $html .= '<td' . $class . '>' . htmlspecialchars(trim($cell)) . '</td>';
    }
    $html .= '</tr>';
}

$html .= '</tbody></table></div>';

echo $html;
?>
    </div>
</section>