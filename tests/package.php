<?php
/**
 * Inspect a temporary ZIP from the real standalone package builder.
 * This is artifact coverage, not installed WordPress acceptance.
 *
 * @package NinjaGeolocationAutocomplete\Tests
 */

$root = dirname( __DIR__ );
$output = sys_get_temp_dir() . '/nfgeoac-package-' . bin2hex( random_bytes( 6 ) );
$command = escapeshellarg( PHP_BINARY ) . ' ' . escapeshellarg( $root . '/tools/package/build.php' ) . ' ' . escapeshellarg( $output );
exec( $command . ' 2>&1', $messages, $status );
if ( 0 !== $status ) {
	fwrite( STDERR, implode( PHP_EOL, $messages ) . PHP_EOL );
	exit( 1 );
}
$zip = new ZipArchive();
if ( true !== $zip->open( $messages[0] ) ) {
	fwrite( STDERR, 'Cannot inspect the generated ZIP.' . PHP_EOL );
	exit( 1 );
}
$prefix = 'wpgeo-address-autocomplete-for-ninja-forms/';
$files = [];
for ( $index = 0; $index < $zip->numFiles; ++$index ) {
	$files[] = $zip->getNameIndex( $index );
}
$checks = [];
$checks['one standalone root'] = ! array_filter( $files, static function ( $file ) use ( $prefix ) {
	return 0 !== strpos( $file, $prefix );
} );
foreach ( [ 'build.js', 'package.json', 'package-lock.json' ] as $file ) {
	$checks[ 'excludes build metadata: ' . $file ] = ! in_array( $prefix . $file, $files, true );
	$checks[ 'retains source build metadata: ' . $file ] = is_file( $root . '/' . $file );
}
foreach ( [
	'wpgeo-address-autocomplete-for-ninja-forms.php', 'readme.txt',
	'assets/js/autocomplete/services/address-autocomplete.js', 'assets/js/editor/form-editor.js',
	'src/Admin/Dashboard/assets/scss/dashboard.scss', 'assets/licenses/choices.txt',
	'build/js/frontend/address-autocomplete.min.js', 'build/js/admin/nfgeo-form-editor.min.js',
] as $file ) {
	$checks[ 'includes ' . $file ] = in_array( $prefix . $file, $files, true );
}
for ( $number = 1; $number <= 4; ++$number ) {
	$file = 'assets/screenshots/screenshot-' . $number . '.png';
	$checks[ 'includes ' . $file ] = in_array( $prefix . $file, $files, true );
}
$checks['no development or private directories'] = ! array_filter( $files, static function ( $file ) use ( $prefix ) {
	return preg_match( '#^(?:\.git|\.agents|\.codex|\.vscode|docs|tests|tools|vendor|node_modules|dist)/#', substr( $file, strlen( $prefix ) ) );
} );
$main = $zip->getFromName( $prefix . 'wpgeo-address-autocomplete-for-ninja-forms.php' );
$checks['Logical public plugin title'] = false !== strpos( $main, 'Plugin Name: Logical Address Autocomplete for Ninja Forms' );
$checks['internal plugin name excludes WPGeo'] = false !== strpos( $main, "define( 'NFGEOAC_PLUGIN_NAME', 'Address Autocomplete for Ninja Forms' );" );
$checks['WPGeo public translation domain'] = false !== strpos( $main, 'Text Domain: wpgeo-address-autocomplete-for-ninja-forms' );
$readme = $zip->getFromName( $prefix . 'readme.txt' );
$checks['Logical public readme title'] = false !== strpos( $readme, '=== Logical Address Autocomplete for Ninja Forms ===' );
$checks['public source and build instructions'] = false !== strpos( $readme, 'https://github.com/Fitoussi/address-autocomplete-for-ninja-forms' )
	&& false !== strpos( $readme, 'download the repository' )
	&& false !== strpos( $readme, 'npm ci' ) && false !== strpos( $readme, 'npm run build' );
$zip->close();
$checksum = hash_file( 'sha256', $messages[0] );
exec( $command . ' 2>&1', $retry_messages, $retry_status );
$checks['existing artifacts are not overwritten'] = 0 !== $retry_status && $checksum === hash_file( 'sha256', $messages[0] );
echo json_encode( $checks, JSON_PRETTY_PRINT ) . PHP_EOL;
echo 'Test artifact: ' . $messages[0] . PHP_EOL;
exit( in_array( false, $checks, true ) ? 1 : 0 );
