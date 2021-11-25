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
$el = new CIBlockElement;

$PROP = array();
$PROP[29][0] = Array("VALUE" => Array ("TEXT" => $_REQUEST["text"], "TYPE" => "text"));  // свойству с кодом 12 присваиваем значение "Белый"
$PROP[30] = $USER->GetID();        // свойству с кодом 3 присваиваем значение 38

$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
  "IBLOCK_ID"      => 7,
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $arUser["LOGIN"]."_Chat",
  "ACTIVE"         => "Y",            // активен
  "DATE_ACTIVE_FROM"=>date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time()),

  );
if($_REQUEST["text"])
	$PRODUCT_ID = $el->Add($arLoadProductArray);

?>