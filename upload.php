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
$partnerId = PARTNER ID HERE;
$expiry = 6000;
$privileges = "";

$videoTitle = $_POST['videoTitle'];
$videoCategory = $_POST['videoCategory'];
$videoToken = $_POST['videoToken'];

// Get KS
try {
	
	$entry = new KalturaMediaEntry();
	$entry->mediaType = KalturaMediaType::VIDEO;
	$entry->name = $videoTitle;
	$entry->categoriesIds = $videoCategory;
	$entry->tags = $videoCategory;
	$createEntry = $client->media->add($entry);
	$resource = new KalturaUploadedFileTokenResource();
	$resource->token = $videoToken;
	$attachEntry = $client->media->addContent($createEntry->id, $resource);
	
} catch (Exception $e) {
	echo $e->getMessage();
}

?>
