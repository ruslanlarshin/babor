<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

foreach($_REQUEST as $key=>$value){
		if(iconv('utf-8', 'cp1251', $value)){
			$_REQUEST[$key]=iconv('utf-8', 'cp1251', $value);
		}
	}
//��������� ����� ������������
$user = new CUser;
$arFields = Array(
  "NAME"              => $_REQUEST['name'],
  "LAST_NAME"         => $_REQUEST['family'],
  "EMAIL"             => $_REQUEST['email'],
 // "SECOND_NAME"       => $_REQUEST['order_new_otchestvo'],
 // "PERSONAL_PHONE "   => $_REQUEST['order_new_phone'],
  "LOGIN"             => $_REQUEST['login'],
 // "UF_NAMECOMP"       => $_REQUEST['order_new_kompany'],
 // "LID"               => "ru",
  "ACTIVE"            => "Y",
  "GROUP_ID"          => array(2,5),
  "PASSWORD"          => $_REQUEST['password'],
  "CONFIRM_PASSWORD"  => $_REQUEST['password2'],
);

$ID = $user->Add($arFields);



if(!$ID){
?>

<?
}
if(!$ID){
	$ID=$USER->GetID();
}
global $USER;
if (!is_object($USER)) $USER = new CUser;
$arAuthResult = $USER->Login($_REQUEST['login'],$_REQUEST['password'], "Y");
//������� ������������ ��� ����
$el = new CIBlockElement;
$PROP = array();
$PROP[31] = $ID;  // �������� � ����� 12 ����������� �������� "�����"
$PROP['PASSWORD']=$_REQUEST['password'];
$arLoadProductArray = Array(  
   'MODIFIED_BY' => $GLOBALS['USER']->GetID(), // ������� ������� ������� �������������  
   'IBLOCK_SECTION_ID' => false, // ������� ����� � ����� �������  
   'IBLOCK_ID' => 6,
   'PROPERTY_VALUES' => $PROP,  
   'NAME' => $_REQUEST['name'].' '.$_REQUEST['family'],  
   'ACTIVE' => 'Y', // �������  
   'PREVIEW_TEXT' => '',  
   'DETAIL_TEXT' => '',  
   'DETAIL_PICTURE' => $_FILES['DETAIL_PICTURE'] // ��������, ����������� �� ��������� ���� ���-����� � ������ DETAIL_PICTURE
);

$PRODUCT_ID = $el->Add($arLoadProductArray);
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");
AddMessage2Log("����������� ������������ ".$_REQUEST['name'].' '.$_REQUEST['family'].' ������: '.$_REQUEST['password'].' e-mail: '.$_REQUEST['email'].' ����� '.$_REQUEST['login']);

?>
<div style='display:none;'><?//require($_SERVER["DOCUMENT_ROOT"].'/ajax/plagin/ip_city.php');?></div>
<div>�� ������� ������������������</div>
<script>
$(document).ready(function(){
	setTimeout(function(){
		$('.close_img').click();
		location.reload(); 
	},2000);
	$(".load").removeClass('load'); 
	var url=$('.auth_small').attr('data-template');
	$.ajax({
	type: "POST", 
	url: url+"/ajax.php", 
		success: function(html){ 
			$(".auth_small_component_ajax").html(html); 
		}
	});
});
</script>
