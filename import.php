<?
require_once('/standardebooks.org/web/lib/Core.php');

//file_get_contents('/home/alex/donations.csv');

$csv = array_map( 'str_getcsv', file( '/home/alex/donations.csv')  );
vdd($csv);
