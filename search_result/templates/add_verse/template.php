<?//$APPLICATION->SetAdditionalCSS($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/style.css',true);?>
<link rel="stylesheet" type="text/css" href="<?=$this->GetFolder()?>/style.css">
<?if(!in_array($this->GetFolder().'/script_my.js',$_SESSION['script'])){
	$_SESSION['script'][]=$this->GetFolder().'/script_my.js';
	?>
	<script type='text/javascript' src='<?=$this->GetFolder()?>/script_my.js'></script>
<?}?>
<div  class='serach_result_compomemt' data-template='<?=$this->GetFolder()?>'>
<?$arSearchOption=array('sort_book','hight_search','in_book_search','all_result');
?>
<div class='request_options hidden'>
	<div data-class='text'  class='text'><?=$_REQUEST['text']?></div>
	<?foreach($arSearchOption as $option){?>
		<div data-class='<?=$option?>'  class='<?=$option?>'><?=$_REQUEST[$option]?></div>
	<?}?>
</div>
<div class='result_string'>По запросу <b>"<?=$_REQUEST['text']?>"</b> найдено (<?=$arResult['COUNT']?>) <?=$arResult['RES_TEXT']?></div>

<?//хитрость для получения количества без доп цикла?>
	<?//Вывод при группировке по книгам?>
	<?if(!$_REQUEST['sort_book']){?>
		<?arsort($arResult['POINT']);$arResult['COUNT']=0;?>
			<div class='result_list'>
			<?foreach($arResult['POINT'] as $point){?>
				<?if($_REQUEST['all_result'] || $point>($arResult['MAX_POINT']/2)){?>
					<?//echo "<b><h1>".$point."</h1></b>";?>
					<?foreach($arResult['ITEM'] as $key=>$result){?>
						<?foreach($result as $stih){?>
							<?if($point==$stih['POINT']*1){?>
								<div class='search_stih'><span class='serach_stih_path' data-book='<?=$stih['BOOK']?>' data-chapter='<?=$stih['CHAPTER_NUMBER']?>' data-stix='<?=$stih['STIX_NUMBER']?>' ><?=$stih['PATH']?></span> <?=$stih['TEXT']?></div>
								<?$arResult['COUNT']++;?>
							<?}?>
						<?}?>
					<?}?>
				<?}?>
			<?}?>
		</div>
	<?}else{?>
		<?//Удалим лишние результаты из группмровки по книгам?>
		<?foreach($arResult['ITEM'] as $key=>$result){
			foreach($result as $keyStih=>$stih){
				if($_REQUEST['all_result'] || $stih['POINT']>($arResult['MAX_POINT']/2)){
				}else{
					unset($arResult['ITEM'][$key][$keyStih]);
				}
			}
		}
		foreach($arResult['ITEM'] as $key=>$result){
			if(count($result)==0){
				unset($arResult['ITEM'][$key]);
			}
		}
		?>
		<?if(count($arResult['ITEM'])>2 && $arResult['COUNT']>=50){?>
			<div class='book_navigation'>
				<?foreach($arResult['ITEM'] as $key=>$result){
					foreach($result as $key2 => $value){?>
						<?if(count($result)!=0){?>
							<a href='#book_<?=$value['BOOK_number']?>' ><span><nobr><span class='dotted'><?=$value['BOOK']?></span>(<?=count($result)?>)</nobr></span></a>
						<?break;}?>
					<?}?>
				<?}?>
			</div>
		<?}?>
		<div class='result_list'>
			<?foreach($arResult['ITEM'] as $key=>$result){?>
				<?
				foreach($result as $key2 => $value){?>
					<?if(count($result)!=0){?>
						<div class='serch_book_name'><a name='book_<?=$result[$key2]['BOOK_number']?>'><b><?=$result[$key2]['FULL_NAME']?>(<?=count($result)?>)</b></a></div>
					<?break;
					}?>
				<?}?>
				<?foreach($result as $stih){?>
					<?if($_REQUEST['all_result'] || $stih['POINT']>($arResult['MAX_POINT']/2)){?>
						<div class='search_stih'><span class='serach_stih_path'><?=$stih['PATH']?></span> <?=$stih['TEXT']?> </div>
					<?}?>
				<?}?>
			<?}?>
		</div>
	<?}?>
</div>
