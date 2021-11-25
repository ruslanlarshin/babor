<link rel="stylesheet" type="text/css" href="<?=$this->GetFolder()?>/style.css">
<?if(!in_array($this->GetFolder().'/script_my.js',$_SESSION['script'])){
	$_SESSION['script'][]=$this->GetFolder().'/script_my.js';
	?>
	<script type='text/javascript' src='<?=$this->GetFolder()?>/script_my.js'></script>
<?}?>
<?$arGroupOld=array(
	'1'=>'Пятикнижие Моисея',
	'6'=>'Исторические книги',
	'21'=>' ',
	'25'=>'Книги пророческие',
	'47'=>'Книги исторические(неканонические)',
);
$arGroupNew=array(
	'1'=>'Евангелия и Деяния',
	'6'=>'Соборные послания',
	'13'=>'Полания апостола Павла',
	'27'=>'Откровение',
);
?>
<div class='book_list_component book_list_component_main' data-template='<?=$this->GetFolder()?>'>
	<table>
		<tr>
			<td style='padding-right: 5px;'>
				<div class='book_list'>
					<div class='zavet_title'> КНИГИ ВЕТХОГО ЗАВЕТА</div>
					<div class="book_list_vethii">
						<?foreach($arResult['OLD_ZAVET_FULL'] as $key=>$value){
							if($arGroupOld[$key]){
							?>
								<?if($key!=1){echo '</div>';}?><div class='group_title'><?=$arGroupOld[$key]?></div><div class='group'>
							<?
							}
							?>
								<div class='book_title book_vethii_title book_ajax_chapter' data-value='<?=$key?>' data-name='<?=str_replace(' ','_',$arResult['OLD_ZAVET'][$key])?>'><?=$value ?></div>
							<?
							
						}?>
					</div>
				</div>
			</td>
			<td style='padding-left: 5px;'>
				<div class='book_list'>
					<div class='zavet_title'> КНИГИ НОВОГО ЗАВЕТА</div>
					<div class="book_list_novii">
						<?foreach($arResult['NEW_ZAVET_FULL'] as $key=>$value){
							if($arGroupNew[$key]){
							?>
								<?if($key!=1){echo '</div>';}?><div class='group_title'><?=$arGroupNew[$key]?></div><div class='group'>
							<?
							}
							?>
							<div class='book_title book_novii_title book_ajax_chapter'  data-value='<?=$key+39?>' data-name='<?=str_replace(' ','_',$arResult['NEW_ZAVET'][$key])?>'><?=$value ?></div>
						<?
						}?>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>