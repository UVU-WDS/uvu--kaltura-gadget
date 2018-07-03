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

	
$data = $_GET['categoryTag'];
$currentPage = $_GET['currentPage'];
$searchString = $_GET['searchString'];
$pageSize = $_GET['pageSize'];

if ($data == "none") {
	$data = "59117502";
}
// List media
$entryFilter = new KalturaBaseEntryFilter();
$entryFilter->tagsLike = $data;
$entryFilter->nameLike = $searchString;
$entryfilter->orderBy = KalturaBaseEntryOrderBy::CREATED_AT_DESC;
$pager = new KalturaFilterPager();
$pager->pageSize = $pageSize;
$pager->pageIndex = $currentPage;

try {
	$medialist->entries = $client->baseEntry->listAction($entryFilter, $pager);
	$medialist->entries->currentPage = $currentPage;
	$medialist->entries->perPage = $pageSize;
	$medialist->entries->searched = $searchString;
	for ($i = 0; $i < count($medialist->entries->objects); $i++) {
		$entryId = $medialist->entries->objects[$i]->id;
		$medialist->entries->objects[$i]->srtURL = $client->captionAsset->serveByEntryId($entryId);
	}
} catch (Exception $e) {
	echo $e->getMessage();
}
header('Content-type: application/json');
echo json_encode($medialist);
	



?>
