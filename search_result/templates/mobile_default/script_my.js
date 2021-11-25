$(document).ready(function(){

});
$('body').on('click', '.serach_stih_path', function(){
	var sData='book='+$(this).attr('data-book')+'&chapter='+$(this).attr('data-chapter')+'&stix='+$(this).attr('data-stix');
	$.ajax({
		type: "POST", 
		data: sData,
		url: "/ajax/popup/chapter.php", 
			success: function(html){ 
				$(".popup_main_2").html(html); 
				open_popup_2();
			}
		});
});