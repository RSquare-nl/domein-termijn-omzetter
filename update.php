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

$file='domain_handles.csv';
$filesave='domain_period.csv';

print "Dit script zet de domain_handles.csv om in domain_period.csv <br>\n";
print "Dit script is bedoeld om op de commandline gedraaid te worden.<br>\n";
print "vb: php update.php<br>\n";

try
{
    $conn = new sidnEppConnection(true);

    // Connect to the EPP server
    if ($conn->connect())
    {
        if (login($conn))
        {

			  $f = fopen($file, 'r');
			  $s = fopen($filesave, 'w');
			  $counter=0;
			  while ( ($line = fgets($f)) !== false ) { //Lees de file in regel voor regel
				  if($counter){ //Sla de eerste regel over
					  $pos=strpos($line,';');
					  $domainname=trim(substr($line,0,$pos),'"'); //Haal alleen de domeinnaam eruit
					  print "$domainname<br>\n";
					  flush();
					  $result=getDomainPeriod($conn, $domainname);
					  $newline=$domainname.','.$result['period'].','.$result['creationdate']."\n";
					  fwrite($s,$newline);
					  //break;
				  }
				  $counter++;
			  }
			  fclose($f);
			  fclose($s);
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



function getDomainPeriod($conn, $domainname)
{
	try
	{
		$epp = new eppDomain($domainname);
		$info = new eppInfoDomainRequest($epp);
		if ((($response = $conn->writeandread($info)) instanceof eppInfoDomainResponse) && ($response->Success()))
		{
			$result['period']= $response->getDomainPeriod();
			$result['creationdate']= $response->getDomainCreateDate();
			return $result;
		}
		else
		{
			echo "ERROR2\n";
		}
	}
	catch (eppException $e)
	{
		echo 'ERROR1';
		echo $e->getMessage()."\n";
	}
}

?>
