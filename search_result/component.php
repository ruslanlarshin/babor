<?
global $DB;
global $USER;
global $APPLICATION;
global $INTRANET_TOOLBAR;

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
//����� �������� ��������� ��� ��������-��� �������-������� ��������� �������� �� �����!!
$arOption=array();
$arError=array(); 
if($_REQUEST['clear']!='yes'){
	$time=3600000;// ����� ����� ���� � �������� -��� ���������� � ������������-0
}else{
	$time=0;// ����� ����� ���� � �������� -��� ���������� � ������������-0
}
$APPLICATION->SetPageProperty("title", '�����  : '.$arParams['text']);
$APPLICATION->SetPageProperty("description", '����� �� ������  '.$arParams['text']);
$APPLICATION->SetPageProperty("keywords", ' ��������, ����� �� ������, ����� �� ������ ������ ,����� �� ������  '.$arParams['text']);


if($this->StartResultCache($time, array($arOption))){ //��� ������� �� �������� $arParams � $arOption-���� ������� ����� �� �����������-�������� �������� ����������
	if($arError){ //���� ������ ��������-�� ��� �� ���������
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$arResult=array();
	$arResult['PARAM']=$arParams;
	
		$arTranscription=array('`'=> '�','q'=> '�','w'=> '�','e'=> '�','r'=> '�','t'=> '�','y'=> '�','u'=> '�','i'=> '�','o'=> '�','p'=> '�','['=> '�',']'=> '�','a'=> '�','s'=> '�','d'=> '�','f'=> '�','g'=> '�','h'=> '�','j'=> '�','k'=> '�','l'=> '�',';'=> '�',"'"=> '�','z'=> '�','x'=> '�','c'=> '�','v'=> '�','b'=> '�','n'=> '�','m'=> '�',','=> '�','.'=> '�','/'=> '.');
		$arParams['text']=mb_strtolower($arParams['text']);
		if(preg_match('#^[a-z\d]+$#i', $arParams['text'])){
			foreach($arTranscription as $eng=>$ru){
				$arParams['text']=str_replace($eng,$ru,$arParams['text']);
			}
		}
		$firestAlfavitId=12;
		$arAlphavit=array('�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�');
		$arPoint=array();
		$arWord=array();
		$text=trim($arParams['text']);
		$text=str_replace(array(',',' - ','!','.',';','--',':','?','[',']','"'),' ',$text);
		$text=str_replace('�','�',$text);
		$arText=explode(' ',mb_strtolower($text));

		$arWordOrigin=array(); //������ ��������� ����-����=10
		$arWordError=array(); //����� �� �������.. �� ��������� ����� ����� ���� 1 ���������(�����==1)-���� =8
		$arWordErrors=array(); //����� �� �������.. �� ��������� ����� ����� ���� >1 ���������(�����>1)-���� =6
		$arWordSinonimus=array(); //����� �� ������� ��� �������..�� ���� ����� ��������� ������� ��������� ��� ������-�������� � ��, ����� �����... ����->�����,������.... ���� =4
		$alfavitAllId=array();
		for($i=0;$i<33;$i++){
			$alfavitAllId[]=12+$i;
		}
		foreach($arText as $word){
			if($word!='' && strlen($word)>2){
				//����� ���������, ������ ����, ���� � ������� � ��... �������� �� �������������
				if($arParams['hight_search']){
					//�������� ������������� �����, ������� ����� ������ ������ �� ���� ������ �����
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
						//����� ��������� �������������� � 2� �������: ������������ ����� � ������������ ��������� �� ������- ����� ���
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
						}//������� ����� ����� ����� ����� ������� � �� ���������
						$arSelect = Array("NAME", "ID");
						$arFilter = Array("IBLOCK_ID"=>$alfavitAllId,"NAME"=>$synonimus);
						$res = CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false,  false, $arSelect);
						while($ob = $res->GetNext()){
							$arWordSinonimus[]=$ob['NAME'];
							//������������-�������� ��������� �������-���-�� ��� �����!!
						}
					}else{
						$wordWithError=array();
						if(strlen($word<=4)){ //��� �������� ���� ������ �� 1�� ����� ����� ����� ������� ����� ����� �����������
							for($i=1;$i<strlen($word);$i++){
								$wordWithError[]=substr($word,0,$i).'%'.substr($word,$i+1,strlen($word));
							}
						}else{//������� �� �����-����� ������ ����� 1
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
							}//������� ����� ����� ����� ����� ������� � �� ���������
							$arSelect = Array("NAME", "ID");
							$arFilter = Array("IBLOCK_ID"=>$alfavitAllId,"NAME"=>$synonimus);
							$res = CIBlockElement::GetList(Array("ID" => "ASC"), $arFilter, false,  false, $arSelect);
							while($ob = $res->GetNext()){
								$arWordSinonimus[]=$ob['NAME'];
								//������������-�������� ��������� �������-���-�� ��� �����!!
							}
						}else{
							while($ob = $res->GetNext())
							{
								if(strlen($ob["NAME"])<=strlen($word)+1){
									$arWordErrors[]=$ob["NAME"];
								}
							}
						}
						//  � ������ ���� ����� � ��������-�������� ���� ����� � ������� � 2 ���������
					}
					//echo $word.' '.$res->SelectedRowsCount();
					//�������� ����� ������ ��� �������-����������� ��������
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
					$arWord[]='%'.$word.'%';// ����� ������ ��� �������������� ������-����� ����� ����
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
			$arWord='!������� ������';
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
					//�������� ������� �������������
					
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
					if($arFields['PROPERTY_CHAPTER_VALUE']*1==13 && $arFields['PROPERTY_STIX_NUMBER_VALUE']==36 && $arFields['PROPERTY_BOOK_VALUE']=="������"){
						//echo '<pre>'; print_r($text.' '.$word.' '.$point.' '.strpos($text,$word.' ')); echo "</pre>"; 
					}
					//������� �������������
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
		$res_text='�����������';
		$ost=$Count%10;
		if($ost>=2 && $ost<=4){
			$res_text='����������';
		}
		if($ost==1 ){
			$res_text='���������';
		}
		$arResult['POINT']=$arPoint;
		$arResult['COUNT']=$Count;
		$arResult['MAX_POINT']=$maxPoint;
		$arResult['RES_TEXT']=$res_text;
	//$arResult["ID"]=1234567; //����� ������� ��������� ������-������� ����� ������������
	$this->IncludeComponentTemplate();
	//echo '���������� ���� ������ � ���������� � ���';
	if($arError)
	{
		$this->AbortResultCache();
		ShowError("ERROR");
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
	}
}else{
	//echo '������ ���� � ����!<BR>';// ���������� �����, ����� �������� ���-���������� ��� �������� ������ ���� � �������� ��� ����!!
}
?>