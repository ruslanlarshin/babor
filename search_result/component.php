<?
global $DB;
global $USER;
global $APPLICATION;
global $INTRANET_TOOLBAR;

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
//Самый полезный компонент для примеров-это новости-сделаем компонент новостей на аяксе!!
$arOption=array();
$arError=array(); 
if($_REQUEST['clear']!='yes'){
	$time=3600000;// время жизни кеша в секундах -для отключения и тестирования-0
}else{
	$time=0;// время жизни кеша в секундах -для отключения и тестирования-0
}
$APPLICATION->SetPageProperty("title", 'Поиск  : '.$arParams['text']);
$APPLICATION->SetPageProperty("description", 'Поиск по Библии  '.$arParams['text']);
$APPLICATION->SetPageProperty("keywords", ' симфония, поиск по Библии, поиск по полной Библии ,Поиск по Библии  '.$arParams['text']);


if($this->StartResultCache($time, array($arOption))){ //кеш берется по значению $arParams и $arOption-если таковых ранее не загружалось-начнется загрузка компонента
	if($arError){ //если шаблон ошибочен-то кеш не запишется
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$arResult=array();
	$arResult['PARAM']=$arParams;
	
		$arTranscription=array('`'=> 'ё','q'=> 'й','w'=> 'ц','e'=> 'у','r'=> 'к','t'=> 'е','y'=> 'н','u'=> 'г','i'=> 'ш','o'=> 'щ','p'=> 'з','['=> 'х',']'=> 'ъ','a'=> 'ф','s'=> 'ы','d'=> 'в','f'=> 'а','g'=> 'п','h'=> 'р','j'=> 'о','k'=> 'л','l'=> 'д',';'=> 'ж',"'"=> 'э','z'=> 'я','x'=> 'ч','c'=> 'с','v'=> 'м','b'=> 'и','n'=> 'т','m'=> 'ь',','=> 'б','.'=> 'ю','/'=> '.');
		$arParams['text']=mb_strtolower($arParams['text']);
		if(preg_match('#^[a-z\d]+$#i', $arParams['text'])){
			foreach($arTranscription as $eng=>$ru){
				$arParams['text']=str_replace($eng,$ru,$arParams['text']);
			}
		}
		$firestAlfavitId=12;
		$arAlphavit=array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я');
		$arPoint=array();
		$arWord=array();
		$text=trim($arParams['text']);
		$text=str_replace(array(',',' - ','!','.',';','--',':','?','[',']','"'),' ',$text);
		$text=str_replace('ё','е',$text);
		$arText=explode(' ',mb_strtolower($text));

		$arWordOrigin=array(); //массив реальныъх слов-балл=10
		$arWordError=array(); //слово не найдено.. но изменение одной буквы дало 1 результат(важно==1)-балл =8
		$arWordErrors=array(); //слово не найдено.. но изменение одной буквы дало >1 результат(важно>1)-балл =6
		$arWordSinonimus=array(); //слово не найдено или найдено..но этио слова найденные заменой окончания или начала-предлоги и тд, формы слова... Петр->петра,петром.... балл =4
		$alfavitAllId=array();
		for($i=0;$i<33;$i++){
			$alfavitAllId[]=12+$i;
		}
		foreach($arText as $word){
			if($word!='' && strlen($word)>2){
				//поиск синонимов, схожих слов, слов с ошиками и тд... проверка на существование
				if($arParams['hight_search']){
					//проверим существование слова, поэтому будем искать толкьо по базе данной буквы
					foreach($arAlphavit as $num=>$letter){
						if($letter==mb_substr($word,0,1)){
							$idAlphavit=$num+$firestAlfavitId;
						}
					}
					//echo $idAlphavit;
					$arSelect = Array("NAME", "ID");
					$arFilter = Array("IBLOCK_ID"=>$idAlphavit,"NAME"=>$word);
					$res = CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false,  false, $arSelect);
					if($res->SelectedRowsCount()==1){
						$arWordOrigin[]=$word;
						//поиск синонимов осуществляется в 2х случаях: оригинальное слово и единственный результат из ошибки- иначе нет
						$synonimus=array();
						$synonimus[]='%'.$word;
						$synonimus[]=$word.'%';
						if(strlen($word<=4)){
							$synonimus[]=substr($word,0,strlen($word)-1).'%';
							$synonimus[]='%'.substr($word,1,strlen($word));
						}else{
							if(strlen($word<=8)){
								$synonimus[]=substr($word,0,strlen($word)-2).'%';
								$synonimus[]='%'.substr($word,2,strlen($word));
							}else{
								$synonimus[]=substr($word,0,strlen($word)-3).'%';
								$synonimus[]='%'.substr($word,3,strlen($word));
							}
						}//надеюсь потом будут связи между словами в их синонимах
						$arSelect = Array("NAME", "ID");
						$arFilter = Array("IBLOCK_ID"=>$alfavitAllId,"NAME"=>$synonimus);
						$res = CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false,  false, $arSelect);
						while($ob = $res->GetNext()){
							$arWordSinonimus[]=$ob['NAME'];
							//давидалаттус-внимание почистить словарь-что-то там нитак!!
						}
					}else{
						$wordWithError=array();
						if(strlen($word<=4)){ //для коротких слов только по 1ой букве инчае будет слишком нмого левых результатов
							for($i=1;$i<strlen($word);$i++){
								$wordWithError[]=substr($word,0,$i).'%'.substr($word,$i+1,strlen($word));
							}
						}else{//отсавлю на потом-когда ошибок более 1
							for($i=1;$i<strlen($word);$i++){
								$wordWithError[]=substr($word,0,$i).'%'.substr($word,$i+1,strlen($word));
							}
						}
						$arSelect = Array("NAME", "ID");
						$arFilter = Array("IBLOCK_ID"=>$idAlphavit,"NAME"=>$wordWithError);
						$res = CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false,  false, $arSelect);
						if($res->SelectedRowsCount()==1){
							$ob = $res->GetNext();
							$arWordError[]=$ob["NAME"];
							$synonimus=array();
							$synonimus[]='%'.$ob["NAME"];
							$synonimus[]=$ob["NAME"].'%';
							if(strlen($ob["NAME"]<=4)){
								$synonimus[]=substr($ob["NAME"],0,strlen($ob["NAME"])-1).'%';
								$synonimus[]='%'.substr($ob["NAME"],1,strlen($ob["NAME"]));
							}else{
								if(strlen($ob["NAME"]<=8)){
									$synonimus[]=substr($ob["NAME"],0,strlen($ob["NAME"])-2).'%';
									$synonimus[]='%'.substr($ob["NAME"],2,strlen($ob["NAME"]));
								}else{
									$synonimus[]=substr($ob["NAME"],0,strlen($ob["NAME"])-3).'%';
									$synonimus[]='%'.substr($ob["NAME"],3,strlen($ob["NAME"]));
								}
							}//надеюсь потом будут связи между словами в их синонимах
							$arSelect = Array("NAME", "ID");
							$arFilter = Array("IBLOCK_ID"=>$alfavitAllId,"NAME"=>$synonimus);
							$res = CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false,  false, $arSelect);
							while($ob = $res->GetNext()){
								$arWordSinonimus[]=$ob['NAME'];
								//давидалаттус-внимание почистить словарь-что-то там нитак!!
							}
						}else{
							while($ob = $res->GetNext())
							{
								if(strlen($ob["NAME"])<=strlen($word)+1){
									$arWordErrors[]=$ob["NAME"];
								}
							}
						}
						//  а теперь ищем слова с ошибками-заменяем одну букву в сеедине и 2 окончание
					}
					//echo $word.' '.$res->SelectedRowsCount();
					//собираем общий массив для выборки-оптимизация скорости
					foreach($arWordOrigin as $value){
						if(!in_array($value,$arWord)){
							$arWord[]="%".$value.'%';
						}
						if(!in_array($value,$arText)){
							$arText[]=$value;
						}
					}
					foreach($arWordError as $value){
						if(!in_array($value,$arWord)){
							$arWord[]="%".$value.' %';
						}
						if(!in_array($value,$arText)){
							$arText[]=$value;
						}
					}
					foreach($arWordErrors as $value){
						if(!in_array($value,$arWord)){
							$arWord[]="%".$value.' %';
						}
						if(!in_array($value,$arText)){
							$arText[]=$value;
						}
					}
					foreach($arWordSinonimus as $value){
						if(!in_array($value,$arWord)){
							$arWord[]="%".$value.' %';
						}
						if(!in_array($value,$arText)){
							$arText[]=$value;
						}
					}
				}else{
					$arWord[]='%'.$word.'%';// верно только для неуглубденного поиска-иначе будет беда
				}
			}
		}
		/*echo '<pre>'; print_r($arWordOrigin); echo "<pre>";
		echo '<pre>'; print_r($arWordError); echo "<pre>";
		echo '<pre>'; print_r($arWordErrors); echo "<pre>";
		echo '<pre>'; print_r($arWordSinonimus); echo "<pre>";
		//echo '<pre>'; print_r($arWord); echo "<pre>";
		/*if(count($arWord)==1){
			$arParams['sort_book']="Y";
		}*/
		function mb_ucfirst($str, $encoding='windows-1251') 
			{ 
				$str = mb_ereg_replace('^[\ ]+', '', $str); 
				$str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding). 
					   mb_substr($str, 1, mb_strlen($str), $encoding); 
				return $str; 
			} 
		$Count=0;
		if(count($arWord)==0){
			$arWord='!нулевой запрос';
		}
		//$arBook=array();
		$maxPoint=0;
		$arResult=array();
		$arSelect = Array("NAME", "DETAIL_TEXT","PROPERTY_SHORT_NAME","PROPERTY_FULL_NAME","PROPERTY_Chapter","PROPERTY_Stix_Number","ID","PROPERTY_Book","PROPERTY_Number_book");
		$arFilter = Array("IBLOCK_ID"=>47, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "DETAIL_TEXT"=>$arWord);
		$res = CIBlockElement::GetList(Array("PROPERTY_Number_book" => "ASC","PROPERTY_Chapter" => "ASC","PROPERTY_Stix_Number" => "ASC"), $arFilter, false,  false, $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$point=0;
			$textBold=$arFields['DETAIL_TEXT'];
			$text=mb_strtolower(trim($textBold));
			$text=str_replace(array(',',' - ','!','.',';','--',':','?','[',']','"'),' ',$text);
			$text=' '.$text;
			/*if($arFields['PROPERTY_STIX_NUMBER_VALUE']*1==9){
				echo "<pre>"; print_r($text); echo "</pre>";
				echo "<pre>"; print_r($point); echo "</pre>";
			}*/
			foreach($arText as $word){
				if($word!=''  && strlen($word)>2){
					//Начинаем подсчет релевантности
					
					if((strpos($text,$word.' ')>=0 && strpos($text,$word.' ')!='')  ){
						if($arParams['hight_search']){
							if(!in_array($word,$arWordOrigin)){
								$point+=10;
							}else{
								if(!in_array($word,$arWordError)){
									$point+=8;
								}else{
									if(!in_array($word,$arWordErrors)){
										$point+=6;
									}else{
										if(!in_array($word,$arWordSinonimus)){
											$point+=4;
										}
									}
								}
							}
						}else{
							$point+=10;
						}
					}else{
						if(strpos($text,$word)>=0 && strpos($text,$word)!=''){
							$point+=8;
						}
					}
					if($arFields['PROPERTY_CHAPTER_VALUE']*1==13 && $arFields['PROPERTY_STIX_NUMBER_VALUE']==36 && $arFields['PROPERTY_BOOK_VALUE']=="Деяние"){
						//echo '<pre>'; print_r($text.' '.$word.' '.$point.' '.strpos($text,$word.' ')); echo "</pre>"; 
					}
					//Подсчет релевантности
					$textBold=str_replace($word,'<b>'.$word.'</b>',$textBold);
					$textBold=str_replace(mb_strtolower($word),'<b>'.mb_strtolower($word).'</b>',$textBold);
					$textBold=str_replace(mb_ucfirst(mb_strtolower($word)),'<b>'.mb_ucfirst(mb_strtolower($word)).'</b>',$textBold);
				}
				/*if($arFields['PROPERTY_STIX_NUMBER_VALUE']*1==9){
					echo "<pre>"; print_r($text); echo "</pre>";
					echo "<pre>"; print_r($point); echo "</pre>";
					echo "<pre>"; print_r($word); echo "</pre>";
				}*/
				
			}
			if(!in_array($point,$arPoint)){
				$arPoint[]=$point;
				if($maxPoint<$point)
				{
					$maxPoint=$point;
				}
			}
			$Stih=array(
				"TEXT"=>$textBold,
				"ID"=>$arFields['ID'],
				"FULL_NAME"=>$arFields['PROPERTY_FULL_NAME_VALUE'],
				"BOOK"=>$arFields['PROPERTY_BOOK_VALUE'],
				"BOOK_number"=>$arFields['PROPERTY_NUMBER_BOOK_VALUE'],
				"PATH"=>$arFields['PROPERTY_SHORT_NAME_VALUE'].' '.trim($arFields['PROPERTY_CHAPTER_VALUE']).':'.$arFields['PROPERTY_STIX_NUMBER_VALUE'],
				"POINT"=>$point,
				"STIX_NUMBER"=>$arFields['PROPERTY_STIX_NUMBER_VALUE'],
				"CHAPTER_NUMBER"=>$arFields['PROPERTY_CHAPTER_VALUE'],
			);
			if(!$arResult[$arFields['PROPERTY_NUMBER_BOOK_VALUE']]){
				$arResult[$arFields['PROPERTY_NUMBER_BOOK_VALUE']]=array();
			}
			$arResult[$arFields['PROPERTY_NUMBER_BOOK_VALUE']][]=$Stih;
				
			//$arBook[$arFields['PROPERTY_NUMBER_BOOK_VALUE']]=$arFields['PROPERTY_BOOK_VALUE'];
			$Count++;
		}
		$buf=$arResult;
		$arResult=array();
		$arResult['ITEM']=$buf;
		//echo "<pre>"; print_r($maxPoint); echo "</pre>";
		if(!$arParams['all_result']){
		/*arsort($arPoint);$Count=0;
			foreach($arPoint as $point){
				if($point>($maxPoint/2)){
					foreach($arResult as $key=>$result){
						foreach($result as $stih){
							if($point==$stih['POINT']*1){
								$Count++;
							}
						}
					}
				}
			}*/
			$Count=0;
			foreach($arResult as $key=>$result){
				foreach($result as $book){
					foreach($book as $stih){
					//echo "<pre>"; print_r($stih); echo "</pre>";
						if($stih['POINT']*1>($maxPoint/2)){
						//echo "<pre>"; print_r($stih); echo "</pre>";
							$Count+=1;
						}
					}
				}
			}
		}
		$res_text='результатов';
		$ost=$Count%10;
		if($ost>=2 && $ost<=4){
			$res_text='результата';
		}
		if($ost==1 ){
			$res_text='результат';
		}
		$arResult['POINT']=$arPoint;
		$arResult['COUNT']=$Count;
		$arResult['MAX_POINT']=$maxPoint;
		$arResult['RES_TEXT']=$res_text;
	//$arResult["ID"]=1234567; //здесь тяжелая серверная логика-которую хотим закешировать
	$this->IncludeComponentTemplate();
	//echo 'Загрузился весь шаблон и сохранился в кеш';
	if($arError)
	{
		$this->AbortResultCache();
		ShowError("ERROR");
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
	}
}else{
	//echo 'Шаблон взят и кеша!<BR>';// происходит тогда, когда загружен кеш-эффективно для проверки работы кеша и скорости без него!!
}
?>