<?php
#
# Load the SIDN specific additions
#
include_once(dirname(__FILE__).'/Registries/SIDN/sidnEppCreateRequest.php');
include_once(dirname(__FILE__).'/Registries/SIDN/sidnEppPollRequest.php');
include_once(dirname(__FILE__).'/Registries/SIDN/sidnEppPollResponse.php');
include_once(dirname(__FILE__).'/Registries/SIDN/sidnEppCheckResponse.php');
include_once(dirname(__FILE__).'/Registries/SIDN/sidnEppInfoDomainResponse.php');
include_once(dirname(__FILE__).'/Registries/SIDN/sidnEppRenewRequest.php');

include "auth.php";

class sidnEppConnection extends eppConnection
{

    public function __construct()
    {
        parent::__construct(false);
        parent::setHostname(SIDNHOSTNAME);
        parent::setPort(SIDNPORT);
        parent::setUsername(SIDNUSERNAME);
        parent::setPassword(SIDNPASSWORD);
        parent::setTimeout(5);
        parent::setLanguage('en');
        parent::setVersion('1.0');
        parent::addExtension('sidn-epp-ext','http://rxsd.domain-registry.nl/sidn-ext-epp-1.0');
        parent::addCommandResponse('sidnEppPollRequest', 'sidnEppPollResponse');
        parent::addCommandResponse('sidnEppCreateRequest', 'eppCreateResponse');
        parent::addCommandResponse('eppCheckRequest', 'sidnEppCheckResponse');
        parent::addCommandResponse('eppInfoDomainRequest', 'sidnEppInfoDomainResponse');
        parent::addCommandResponse('sidnEppRenewRequest', 'eppRenewResponse');
    }

}
