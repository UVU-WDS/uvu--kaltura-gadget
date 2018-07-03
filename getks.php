<?php
require_once('lib/KalturaClient.php');

$config = new KalturaConfiguration(PARTNER_ID_HERE);
$config->serviceUrl = 'https://www.kaltura.com';
$client = new KalturaClient($config);
$ks = $client->session->start(
	"INSERT_KS_HERE",
	"ADMIN_EMAIL_HERE",
	KalturaSessionType::ADMIN,
	PARTNER_ID_HERE);
$client->setKS($ks);
$secret = "INSERT_SECRET_HERE";
$userId = "ADMIN_EMAIL_HERE";
$type = KalturaSessionType::USER;
$partnerId = PARTNER_ID_HERE;
$expiry = 6000;
$privileges = "";

// Get KS
try {
	$getks = $client->session->start(
		$secret, 
		$userId, 
		$type, 
		$partnerId, 
		$expiry, 
		$privileges);
} catch (Exception $e) {
	echo $e->getMessage();
}
header('Content-type: text/plain');
echo $getks;

?>
