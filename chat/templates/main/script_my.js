$(document).ready(function(){

});
$('body').on('click', '.book_list .book_ajax_chapter', function(){
	var data="BOOK="+$(this).attr('data-name');
	var url='/bible/'+$(this).attr('data-name')+"/";
	$.ajax({
		type: "POST", 
		url: $('.book_list_component').attr('data-template')+"/ajax.php",
		data: data,		
			success: function(html){ 
				history.pushState(null, null, url);
				$(".main_block").html(html); 
				//left_height();
			}
	});
});