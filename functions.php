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

/**
 * readDomainPeriod
 * Leest de csv file in en geeft een array terug
 * @return array
 **/
function readCsvDomainPeriod($filename){
	$f = fopen($filename, 'r');
	while ( ($line = fgets($f)) !== false ) { //Lees de file in regel voor regel
			$pos=strpos($line,',');
			$pos2=strpos($line,',',$pos+1);
			$domainname=substr($line,0,$pos); //Haal de domeinnaam eruit
			$period=trim(substr($line,$pos+1,$pos2-$pos-1)); //Haal de domeinnaam eruit
			$creationdate=trim(substr($line,$pos2+1)); //Haal de domeinnaam eruit
			$result[$period][$creationdate][]=$domainname;
	}
	fclose($f);
	return $result;
}

/**
* changeDomainPeriod()
* @param string domainname
* @param int period
**/
function changeDomainPeriod($domainname,$period){
	try
	{
		$conn = new sidnEppConnection(false);

		// Connect to the EPP server
		if ($conn->connect())
		{
			if (login($conn))
			{
				$epp = new eppDomain($domainname);
				$renew = new sidnEppRenewRequest($epp,date('Y-m-d'),$period);//date wordt niet gebruikt bij sidn
				if ((($response = $conn->writeandread($renew)) instanceof eppRenewResponse) && ($response->Success()))
				{
					//print_r($response);
				}
				else
				{
					echo "ERROR2\n";
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
}

/**
* writeCsvDomainPeriod()
* @param string csvfilename
* @param string domainname
* @param int period
**/
function writeCsvDomainPeriod($CSVFilename,$domainname,$period){
	$f = fopen($CSVFilename, 'r');
	while ( ($line = fgets($f)) !== false ) { //Lees de file in regel voor regel
		if(strpos($line,$domainname.',')!==false){
			$pos=strpos($line,',');
			$pos2=strpos($line,',',$pos+1);
			$line=substr_replace($line,$period,$pos+1,$pos2-$pos-1);
		}
		$domains[]=$line;
	}
	fclose($f);
	$f = fopen($CSVFilename, 'w');
	foreach($domains as $line){
		$line = fwrite($f,$line);
	}
	fclose($f);
}
?>
