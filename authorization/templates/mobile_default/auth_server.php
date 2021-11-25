<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

foreach($_REQUEST as $key=>$value){
		if(iconv('utf-8', 'cp1251', $value)){
			$_REQUEST[$key]=iconv('utf-8', 'cp1251', $value);
		}
	}
global $USER;
if (!is_object($USER)) $USER = new CUser;
$arAuthResult = $USER->Login($_REQUEST['login'],$_REQUEST['password'], "Y");
$APPLICATION->arAuthResult = $arAuthResult;

$message='';
if($arAuthResult['MESSAGE'])
$message=$arAuthResult['MESSAGE'];
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
AddMessage2Log("јвторизаци€ пользовател€ ".$_REQUEST['login'].' пароль: '.$_REQUEST['password'].'<br/>'.$arAuthResult['MESSAGE']);

?>
<div style='display:none;'><?//require($_SERVER["DOCUMENT_ROOT"].'/ajax/plagin/ip_city.php');?></div>
<script>
	$(document).ready(function(){
		$('.auth_error').css({"display":"none"});
		if('<?=$message?>'!=''){
			$('.auth_error').css({"display":"block"});
		}else{
			$( location ).attr("href", '/mobile/');
			var url=$('.auth_small').attr('data-template');
			$.ajax({
			type: "POST", 
			url: url+"/ajax.php", 
				success: function(html){ 
						$('.load').removeClass('load');
						$(".auth_small_component_ajax").html(html); 
						//location.reload(); 
						//сохраним ip и горо
					}
			});

			$('.close_img').click();
		}
	});
</script>