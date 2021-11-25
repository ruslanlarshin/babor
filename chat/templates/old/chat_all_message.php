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


//Получаем список сообщений формата логин, дата, текст


$arAllMessage=array();

$arSelect = Array("NAME", "ID","PROPERTY_USER", "PROPERTY_CHAT_TEXT","DATE_ACTIVE_FROM","ACTIVE");
$arFilter = Array("IBLOCK_ID"=>7, "ACTIVE_DATE"=>"Y");
$res = CIBlockElement::GetList(Array("ACTIVE_FROM" => "ASC"), $arFilter, false,   false, $arSelect);
while($ob = $res->GetNextElement())
{
  $arFields = $ob->GetFields();
  $arOneMessage=array();
  $arOneMessage["ID"]=$arFields["ID"];
  $arOneMessage["USER_ID"]=$arFields['PROPERTY_USER_VALUE'];
  if($arFields['PROPERTY_USER_VALUE']==$USER->GetID()){
	$arOneMessage['AUTHOR']="YES";
  }
  $rsUser = CUser::GetByID($arFields['PROPERTY_USER_VALUE']);
  $arUser = $rsUser->Fetch();
  $arOneMessage["USER_LOGIN"]=$arUser["LOGIN"];
  $arOneMessage["MESSAGE"]=$arFields['PROPERTY_CHAT_TEXT_VALUE']['TEXT'];
  $arOneMessage["DATE"]=$arFields['ACTIVE_FROM'];
  $Date=explode(' ',$arFields['ACTIVE_FROM']);
  $DateExplodeDay=explode('.',$Date[0]);
  $arOneMessage["DATEF"]=$Date[0];
  $arOneMessage["DATEDAY"]=$DateExplodeDay[0];
  $arOneMessage["DATEMONTH"]=$DateExplodeDay[1];
  $arOneMessage["DATEYEAR"]=$DateExplodeDay[2];
  $DateExplodeTime=explode(':',$Date[1]);
  $arOneMessage["DATEHOUR"]=$DateExplodeTime[0];
  $arOneMessage["DATEMINUTES"]=$DateExplodeTime[1];
  $arOneMessage["ACTIVE"]=$arFields['ACTIVE'];
  $arAllMessage[]=$arOneMessage;
  //echo '<pre>'; print_r($arOneMessage); echo "</pre>";
}
?>


<!-- Вывод самих соощений-->

<?foreach($arAllMessage as $item){?>
	<?//echo '<pre>'; print_r($item); echo "</pre>";?>
	<div class='active return <?if($item['ACTIVE']=="N" && $item["USER_ID"]==$USER->GetID()){ echo "return_active";}else{echo "hidden";}?> active_<?=$item["ID"]?>' data-id='<?=$item["ID"]?>'>
		<img  class='RETURN' src='/images/return.png' >
	</div>
	<div class='message_in <?if($item['ACTIVE']=="N" && $item["USER_ID"]==$USER->GetID()){ echo "no_active";}else{if($item['ACTIVE']=="N"){echo "hidden";}}?>' >
		<table class='message_table'>
			<tr>
				<td class='message_td_info'>
					<div class='user_and_date'>
						<div class='message' style='width: 120px;'>
							<div class='message_user <?if($item['AUTHOR']=="YES"){ echo "author";}?>'><?=$item['USER_LOGIN']?></div>
							<div class='message_date'><?=$item['DATE']?></div>
						</div>
					</div>
				</td>
				<td class='message_td_text'>
					<div class='message message_<?=$item["ID"]?>' style='/*width: 650px;*/'>
						<div class='message_text message_<?=$item["ID"]?>' data-id='<?=$item["ID"]?>'>
							<div class='author_panel'>
								<?if($item['AUTHOR']=="YES"){?>
									<table class='table_author_chat'>
										<tr>
											<td>
												<div class='yes hidden yes_<?=$item["ID"]?>' data-id='<?=$item["ID"]?>'>
													<img  class='YES' src='/images/yes.png' >
												</div>
											</td>
											<td>
												<div class='no hidden no_<?=$item["ID"]?>' data-id='<?=$item["ID"]?>'>
													<img  class='NO' src='/images/no.png' >
												</div>
											</td>
											<td>
												<div class='update update_<?=$item["ID"]?>' data-id='<?=$item["ID"]?>'>
													<img  class='UPDATE' src='/images/pen.png' >
												</div>
											</td>
											
											<td>
												<div class='delete delete_<?=$item["ID"]?>' data-id='<?=$item["ID"]?>'>
													<img  class='DELETE' src='/images/delete.png' >
												</div>
											</td>
											
										</tr>
									</table>
								<?}?>
							</div>
							<textarea class='chat_add_texts update_text area_<?=$item["ID"]?> hidden'  ><?=$item['MESSAGE']?></textarea>
							<span class='text_<?=$item["ID"]?>'><?=$item['MESSAGE']?></span>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<?//echo '<pre>'; print_r($arOneMessage); echo "</pre>";?>
		
	</div>
<?}?>

<style>
	.return_active{
		margin-bottom: -33px;
		float: right;
		margin-right: 15px;
		top: 13px;
		z-index: 505;
		position: relative;
	}
	.no_active .active{
		display: block !important;
	}
	.no_active .update,
	.no_active .delete{
		display: none;
	}
	
	.no_active{
		opacity: 0.5;
	}
	.update_text{
		width: 100% !important;
		margin-top: 10px;
	}
	
	.hidden{
		display:none;
	}
	
	.author{
		color: red;
	}
	
	.table_author_chat{
		position: relative;
		float: right;
	}
	
	.ACTIVE, .UPDATE , .DELETE , .YES, .NO , .RETURN{
		width: 20px;
		border-radius: 20px;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
		-khtml-border-radius: 20px;
		box-shadow: #00a9ee 1px 1px 2px 0px;
		cursor: pointer;
	}
	
	.UPDATE:hover , .DELETE:hover{
		box-shadow: red 1px 1px 2px 0px;
	}
	
	.RETURN:hover , .YES:hover , .NO:hover{
		box-shadow: red 1px 1px 2px 0px;
	}
	
	.message_table{
		width: 100%;
		padding-bottom: 20px;
		
	}
	
	.message_in{
		border-radius: 20px;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
		-khtml-border-radius: 20px;
		
	}
	.message{
		background-color: rgb(216, 238, 253);
		background: linear-gradient(to bottom left, rgb(216, 238, 253) 50%,#00a9ee 180%);
		border-radius: 20px;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
		-khtml-border-radius: 20px;
		padding: 10px  20px;
		box-shadow: rgb(153, 153, 153) 7px 7px 20px 0px;
		word-wrap: break-word;
		margin-right: -5px;
	}
	.message_td_info{
		width: 150px;
		vertical-align: top;
	}
	
	.message_date{
		font-size: 11px;
	}
	.message_td_text{
		vertical-align: top;
		padding-left: 20px;
	}
</style>