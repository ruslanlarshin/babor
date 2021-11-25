<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$flag=0;

$UserLogin = $_REQUEST['login'];
$rsUser = CUser::GetByLogin($UserLogin);
if($arUser = $rsUser->Fetch())
{	
	$flag+=1;
}
echo $flag;
?>
