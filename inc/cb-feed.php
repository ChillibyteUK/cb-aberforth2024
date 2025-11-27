<?php
/**
 * CSV Feed Management Functions
 *
 * Handles CSV data files that are FTP'd directly to /www/aberforthcouk_699/public/feed/
 *
 * @package cb-aberforth2024
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define CSV files list.
define(
	'CSV_FILES',
	array(
		'/feed/AFUND_Dividends.csv',
		'/feed/AGVIT_Dividends.csv',
		'/feed/ASCOT_Dividends.csv',
		'/feed/ASLIT_Dividends.csv',
		'/feed/AFUND_IndustryWeights.csv',
		'/feed/AGVIT_IndustryWeights.csv',
		'/feed/ASCOT_IndustryWeights.csv',
		'/feed/ASLIT_IndustryWeights.csv',
		'/feed/AFUND_CompoundPerformance.csv',
		'/feed/AFUND_CumulativePerformance.csv',
		'/feed/AFUND_DiscretePerformance.csv',
		'/feed/AGVIT_CompoundPerformance.csv',
		'/feed/AGVIT_CumulativePerformance.csv',
		'/feed/AGVIT_DiscretePerformance.csv',
		'/feed/ASCOT_CompoundPerformance.csv',
		'/feed/ASCOT_CumulativePerformance.csv',
		'/feed/ASCOT_DiscretePerformance.csv',
		'/feed/ASLIT_CompoundPerformance.csv',
		'/feed/ASLIT_CumulativePerformance.csv',
		'/feed/ASLIT_DiscretePerformance.csv',
		'/feed/AFUND_PortfolioHoldings.csv',
		'/feed/AGVIT_PortfolioHoldings.csv',
		'/feed/ASCOT_PortfolioHoldings.csv',
		'/feed/ASLIT_PortfolioHoldings.csv',
	)
);

/**
 * Display CSV feed status information.
 *
 * Shows a table with file information for all CSV files in the feed directory.
 *
 * @return string HTML output for the feed status display.
 */
function display_csv_feed_status() {
	$file_path = $_SERVER['DOCUMENT_ROOT'] . '/feed/';
	
	$output  = '<div class="mb-5">';
	$output .= '<h2>CSV Data Files Feed</h2>';
	$output .= '<p>Files are FTP\'d directly to: <code>' . esc_html( $file_path ) . '</code></p>';

	$files = scandir( $file_path );

	// Filter to only include actual files.
	$files = array_filter(
		$files,
		function ( $file ) use ( $file_path ) {
			return is_file( $file_path . $file );
		}
	);

	if ( ! empty( $files ) ) {
		$output .= '<table class="table table-sm fs-300 mb-4">';
		$output .= '<thead>';
		$output .= '<tr>';
		$output .= '<th>File Name</th>';
		$output .= '<th>Size</th>';
		$output .= '<th>Last Modified</th>';
		$output .= '<th>Age</th>';
		$output .= '</tr>';
		$output .= '</thead>';
		$output .= '<tbody>';

		foreach ( $files as $file ) {
			$file_full_path         = $file_path . $file;
			$file_modification_time = filemtime( $file_full_path );
			$file_date              = gmdate( 'Y-m-d H:i:s', $file_modification_time );
			$file_size              = filesize( $file_full_path );
			$file_size_formatted    = number_format( $file_size / 1024, 2 ) . ' KB';

			// Check if file is older than 6 hours 10 minutes.
			$file_age_seconds = time() - $file_modification_time;
			$file_old         = $file_age_seconds > ( 6 * 60 * 60 + 10 * 60 );
			$row_class        = $file_old ? ' class="table-warning"' : '';
			$warning_icon     = $file_old ? '<i class="far fa-clock"></i>&nbsp;' : '';
			$warning_msg      = $file_old ? 'title="File is older than 6 hours - check FTP upload"' : '';

			// Format age as human-readable.
			$hours   = floor( $file_age_seconds / 3600 );
			$minutes = floor( ( $file_age_seconds % 3600 ) / 60 );
			$age     = sprintf( '%dh %dm', $hours, $minutes );

			$output .= '<tr' . $row_class . '>';
			$output .= '<td title="' . esc_attr( $file_full_path ) . '">' . esc_html( $file ) . '</td>';
			$output .= '<td>' . esc_html( $file_size_formatted ) . '</td>';
			$output .= '<td ' . $warning_msg . '>' . $warning_icon . esc_html( $file_date ) . '</td>';
			$output .= '<td>' . esc_html( $age ) . '</td>';
			$output .= '</tr>';
		}

		$output .= '</tbody>';
		$output .= '</table>';
	} else {
		$output .= '<p class="text-danger">No files found in the directory.</p>';
	}

	$output .= '</div>';

	return $output;
}
