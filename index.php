<?php
/**
 * gifs.benlk.com
 *
 * This project:
 * 1. reads in a list of files from the present directory
 * 2. reads in ./sources.csv
 * 3. outputs a templated structure listing all files,
 *    with description information.
 */

require 'vendor/autoload.php';
use League\Csv\Reader;

function get_files() {
	return glob( "*.{png,jpg,mp4,webp,gif}", GLOB_BRACE );
}

function get_csv() {
	$csv = Reader::createFromPath( './sources.csv', 'r' );
	$csv->setHeaderOffset(0);
	return $csv;
}

/**
 * Merge the CSV and the file list
 *
 * @param Iterator $csv   The result of Reader::getRecords
 * @param array    $files The list of files with specific filetypes in this folder, as a simple array of file names
 * @return array of filename => csv row
 */
function merge_list( $csv, $files ) {
	// $files is an array of index => filename
	// let's flip it to make search faster
	$merged = array_flip( $files );

	foreach ( $csv as $offset => $record ) {
		if ( isset( $merged[ $record['filename'] ] ) ) {
			$merged[ $record['filename'] ] = $record;
		} else {
			$merged[] = $record;
		}
	}

	return $merged;
}

function render_table() {
	$csv = get_csv();
	$files = get_files();
	$merged = merge_list( $csv, $files );
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Gifs page</title>
	</head>
	<body>
		<h1>Gifs page</h1>

		<pre><code>
		<?php
			render_table();
		?>
		</code></pre>

		<footer>
			<p>This page is <a href="https://github.com/benlk/gifs.benlk.com">powered by open-source software.</a></p>
		</footer>
	</body>
</html>
</html>
