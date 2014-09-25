<?php

// Base EPP objects
include_once('Protocols/EPP/eppConnection.php');
include_once('Protocols/EPP/eppRequests/eppIncludes.php');
include_once('Protocols/EPP/eppResponses/eppIncludes.php');
include_once('Protocols/EPP/eppData/eppIncludes.php');
// Connection objects to registry servers - this contains your userids and passwords!
include_once('sidnEppConnection.php');

// Base EPP commands: hello, login and logout
include_once('base.php');
include_once "functions.php";

$CSVFilename='domain_order_frequency.csv';

print "Dit script is bedoeld om op de commandline gedraaid te worden.<br>\n";
print "vb: php update.php<br>\n";

$domains=readCsvDomainPeriod($CSVFilename);

try
{
    $conn = new sidnEppConnection(true);

    // Connect to the EPP server
    if ($conn->connect()){
        if (login($conn)){
			  if(is_array($domains)){
				  foreach($domains as $period){
					  foreach($period as $dates){
						  foreach($dates as $domainname){
							  $result=getDomainPeriod($conn, $domainname);
							  writeCsvDomainPeriod($CSVFilename,$domainname,$result['period']);
						  }
					  }
				  }
			  }
			  logout($conn);
		  }
	 }else{
		 echo "ERROR CONNECTING\n";
	 }
}
catch (eppException $e)
{
	echo "ERROR: ".$e->getMessage()."\n\n";
}



?>
