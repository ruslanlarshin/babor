<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
foreach($_REQUEST as $key=>$value){
	if(iconv('utf-8', 'cp1251', $value)){
		$_REQUEST[$key]=iconv('utf-8', 'cp1251', $value);
	}
}
	$APPLICATION->IncludeComponent("larshin:chapter_list",
		".default", 
		array(
			"IBLOCK_ID"=>47,
			"book"=>$_REQUEST['BOOK'],
		),
		false
	);
?>