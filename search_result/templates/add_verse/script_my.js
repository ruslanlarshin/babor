$(document).ready(function(){

});
$('body').on('click', '.serach_stih_path', function(){
	var sData='book='+$(this).attr('data-book')+'&chapter='+$(this).attr('data-chapter')+'&stix='+$(this).attr('data-stix');
	$.ajax({
		type: "POST", 
		data: sData,
		url: "/ajax/popup/chapter_add.php", 
			success: function(html){ 
				$(".add_verses_from_list").html(html); 
				$('.button').css({"display":'none'});
				$('.button.preview').css({"display":'inline-block'});
				//open_popup_2();
			}
		});
});	
$('body').on('click', '.option', function(){	
	$(".search_result_add_verse_compomemt").html(''); 
});