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

$searchString = $_GET['searchString'];

// List media
$entryFilter = new KalturaBaseEntryFilter();
$entryFilter->nameLike = $searchString;
$entryfilter->orderBy = KalturaBaseEntryOrderBy::CREATED_AT_DESC;
$pager = new KalturaFilterPager();
$pager->pageSize = 500;
$pager->pageIndex = 1;

try {
	$medialist->entries = $client->baseEntry->listAction($entryFilter, $pager);
	$medialist->entries->searched = $searchString;
} catch (Exception $e) {
	echo $e->getMessage();
}

header('Content-type: application/json');
echo json_encode($medialist);

?>
