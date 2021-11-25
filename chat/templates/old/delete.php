<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

foreach($_REQUEST as $key=>$value){
		if(iconv('utf-8', 'cp1251', $value)){
			$_REQUEST[$key]=iconv('utf-8', 'cp1251', $value);
		}
	}
?>
<?
// Получаем данные со всех сообщений

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
//echo '<pre>'; print_r($arUser["LOGIN"]); echo "</pre>";


//Сохраняем сообщение
$obEl = new CIBlockElement();
$boolResult = $obEl->Update($_REQUEST["id"],array('ACTIVE' => 'N'));

?>