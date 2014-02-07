<?php // $Id$
/**
 * Simple file test.php to drop into root of Moodle installation.
 * This is the skeleton code to print a downloadable, paged, sorted table of
 * data from a sql query.
 */
require "config.php";
require "$CFG->libdir/tablelib.php";

$download = optional_param('download', '', PARAM_ALPHA);

$table = new table_sql('uniqueid');
$table->is_downloading($download, 'test',
    'testing123');
if (!$table->is_downloading()) {
    // Only print headers if not asked to download data
    // Print the page header.
    $navigation = build_navigation('Testing table class');
    print_header_simple('Testing ', 'Testing table class', $navigation);

}

// Work out the sql for the table.
$table->set_sql('*', "{$CFG->prefix}user", '1');

$table->define_baseurl("$CFG->wwwroot/test.php");

$table->out(40, true);

if (!$table->is_downloading()) {
    print_footer();
}
?>