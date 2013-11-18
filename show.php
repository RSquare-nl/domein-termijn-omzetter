<?php
print '<!DOCTYPE html>';
print '<html>';
print '<link rel="stylesheet" href="style.css" type="text/css">';
print '<body>';

$CSVFilename='domain_period.csv';

include "functions.php";


if(isset($_POST['submit'])){
	if(is_array($_POST['domains'])){
		foreach($_POST['domains'] as $domain){
			changeDomainPeriod($domain,$_POST['period']);
			writeCsvDomainPeriod($CSVFilename,$domain,$_POST['period']);
		}
	}
}

//Lees Domeinlijst in
$domains=readCsvDomainPeriod($CSVFilename);

//print_r($domains);

print '<div id="wrapper">';
print '<div id="header">';
if (!file_exists('auth.php')){
	print "<h2>Error: auth.php niet gevonden, ga naar home voor installatie uitleg</h2>";
}
if (!file_exists('domain_period.csv')){
	print "<h2>Error: domain_period.csv niet gevonden, ga naar home voor installatie uitleg</h2>";
}
include 'menu.html';
print '</div>';
print '<div id="content">';
print '<div id="content-left">';
print '<h2>1 maand</h2>';
print '<h3>Maand / Dag - Domeinnaam</h3>';
print '<form name="formperiod1" action="show.php" method="post">';
if(is_array($domains[1])){
	foreach($domains[1] as $creationdate=>$creationdates){
		foreach($creationdates as $domain){
			$list[date('m/d',strtotime($creationdate))][]=$domain;
		}
	}
	ksort($list);
	foreach($list as $date=>$dom){
		if(is_array($dom))
		foreach($dom as $domain){
			print '<input type="checkbox" name="domains[]" value="'.$domain.'"> '.$date.' - '.$domain.'<br>';
		}
	}
	unset($list);
}
print '<br>';
print '<SELECT name="period">';
print '<OPTION selected value="3">3 maanden</OPTION>';
print '<OPTION value="12">12 maand</OPTION>';
print '</SELECT>';
print '<input name="submit" type="submit" value="Verander" />';
print '</form>';
print '</div>';
print '<div id="content-main">';
print '<h2>3 maanden</h2>';
print '<h3>Maand / Dag - Domeinnaam</h3>';
print '<form name="formperiod3" action="show.php" method="post">';
if(is_array($domains[3])){
	foreach($domains[3] as $creationdate=>$creationdates){
		foreach($creationdates as $domain){
			$list[date('m/d',strtotime($creationdate))][]=$domain;
		}
	}
	ksort($list);
	foreach($list as $date=>$dom){
		if(is_array($dom))
		foreach($dom as $domain){
			print '<input type="checkbox" name="domains[]" value="'.$domain.'"> '.$date.' - '.$domain.'<br>';
		}
	}
	unset($list);
}
print '<br>';
print '<SELECT name="period">';
print '<OPTION selected value="1">1 maand</OPTION>';
print '<OPTION value="12">12 maanden</OPTION>';
print '</SELECT>';
print '<input name="submit" type="submit" value="Verander" />';
print '</form>';
print '</div>';
print '<div id="content-right">';
print '<h2>12 maanden</h2>';
print '<h3>Maand / Dag - Domeinnaam</h3>';
print '<form name="formperiod12" action="show.php" method="post">';
if(is_array($domains[12])){
	foreach($domains[12] as $creationdate=>$creationdates){
		foreach($creationdates as $domain){
			$list[date('m/d',strtotime($creationdate))][]=$domain;
		}
	}
	ksort($list);
	foreach($list as $date=>$dom){
		if(is_array($dom))
		foreach($dom as $domain){
			print '<input type="checkbox" name="domains[]" value="'.$domain.'"> '.$date.' - '.$domain.'<br>';
		}
	}
	unset($list);
}
print '<br>';
print '<SELECT name="period">';
print '<OPTION selected value="1">1 maand</OPTION>';
print '<OPTION value="3">3 maanden</OPTION>';
print '</SELECT>';
print '<input name="submit" type="submit" value="Verander" />';
print '</form>';
print '</div>';
print '</div>';
print '<div id="footer"></div>';
print '<div id="bottom"></div>';
print '</div>';

print '</body>';
print '</html>';
?>
