<?
global $DB;
global $USER;
global $APPLICATION;
global $INTRANET_TOOLBAR;

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
//—амый полезный компонент дл€ примеров-это новости-сделаем компонент новостей на а€ксе!!
$arOption=array();
$arError=array(); 
$time=3600000;// врем€ жизни кеша в секундах -дл€ отключени€ и тестировани€-0
$time=0;// врем€ жизни кеша в секундах -дл€ отключени€ и тестировани€-0

$APPLICATION->SetPageProperty("title", '„ат');
$APPLICATION->SetPageProperty("description",'„ат');
$APPLICATION->SetPageProperty("keywords", '„ат');


if($this->StartResultCache($time, array($arOption))){ //кеш беретс€ по значению $arParams и $arOption-если таковых ранее не загружалось-начнетс€ загрузка компонента
	if($arError){ //если шаблон ошибочен-то кеш не запишетс€
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$arResult=array();
	$arResult['PARAM']=$arParams;
	
	 
	//$arResult["ID"]=1234567; //здесь т€жела€ серверна€ логика-которую хотим закешировать
	$this->IncludeComponentTemplate();
	//echo '«агрузилс€ весь шаблон и сохранилс€ в кеш';
	if($arError)
	{
		$this->AbortResultCache();
		ShowError("ERROR");
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
	}
}else{
	//echo 'Ўаблон вз€т и кеша!<BR>';// происходит тогда, когда загружен кеш-эффективно дл€ проверки работы кеша и скорости без него!!
}
?>