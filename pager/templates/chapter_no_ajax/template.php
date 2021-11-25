<link rel="stylesheet" type="text/css" href="<?=$this->GetFolder()?>/style.css">
<div class='pager_for_cahpter'>
	<div class='main_nav bottom_nav'>
		<?foreach($arResult['NAV']['PAGES'] as $value){?>
			<a href='/mobile/bible/<?=$arResult['PARAM']["BOOK"]?>/<?=$value["VAL"]?>/'>
				<div  class='nav_pages nav_<?=$value["VAL"]?> <?if($arResult['PARAM']['PAGE']==$value["VAL"]*1){ echo "active";}?> <?if($value["NAME"]=='...'){ echo "points";}?>' data-page='<?=$value["VAL"]?>'><?=$value["NAME"]?></div>
			</a>
		<?}?>
	</div>
</div>
<?//echo "<pre>"; print_r($arResult); echo '</pre>';?>
<style>
.pager_for_cahpter .main_nav{
	margin-top: 10px;
	width: 100%;
}
.pager_for_cahpter .nav_pages{
	font-size: 14px;
	width: 23px; 
	height: 23px;
	background: linear-gradient(#69D4FF, #fcfcfc 100%);
	display: inline-block;
	text-align: center ;
	box-shadow: #00a9ee 1px 1px 1px 0px;
	cursor: pointer;
	border-top-right-radius: 15px;
	border-bottom-right-radius: 15px;
	border-bottom-left-radius: 15px;
	user-select: none; 
	line-height: 23px;
}
.pager_for_cahpter .nav_pages.points{
	background:none !important;
	box-shadow: none !important
}
.pager_for_cahpter .nav_pages a,.no_href{
	text-decoration: none !important;
	color: black !important;
	position: relative;
	top: 3px;
	user-select: none; 
}
.pager_for_cahpter .nav_pages.active{
	box-shadow: #00a9ee 1px 1px 1px 0px !important;
	cursor: default !important;
	background: linear-gradient( #00C5FF ,rgb(255, 255, 255) 130%);
}
.pager_for_cahpter .nav_pages.active a{
	cursor: default;
}
.pager_for_cahpter{
	margin-bottom: 10px;
	user-select: none; 
	-moz-user-select: -moz-none;
	-o-user-select: none;
	-khtml-user-select: none;
	-webkit-user-select: none;
	user-select: none;
}
.main_nav a{
	text-decoration: none;
	color:black;
}
</style>