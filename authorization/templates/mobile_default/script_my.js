$(document).ready(function(){
	$('.email_reg').focus(); 
});
$('body').on('click', ' .input_reg', function(){
	$(this).removeClass('error_order');
	$('.error_notice_'+$(this).attr('data-value')).css({"display":"none"});
});


$('body').on('keypress', '.email_reg', function(event){
	var keyCode = event.keyCode ? event.keyCode :
		event.charCode ? event.charCode :
		event.which ? event.which : void 0;
		if(keyCode == 13)
		{
			if($('.email_reg').val()!=''){
				$('.password_reg').focus(); 
			}
		}
});

$('body').on('keypress', '.password_reg', function(event){
	var keyCode = event.keyCode ? event.keyCode :
		event.charCode ? event.charCode :
		event.which ? event.which : void 0;
		if(keyCode == 13)
		{
			if($('.password_reg').val()!=''){
				$('.reg_button').click();  
			}
		}
});
	
$('body').on('click', ' .reg_button', function(){
	$('.error_notice').css({"display":"none"});
	$('.input_reg').removeClass('error_order');
	var error=0;
		
		
		
	var login=$('.email_reg').val();
	if(login=='undefined' || login=='' || $.trim(login)==''){	
		error++;
		$('.error_notice_email').css({"display":"block"});
		$('.error_notice_email').text('поле не может быть пустым');
		$('.email_reg').addClass('error_order');
	}
	
	
	
	var password=$('.password_reg').val();
	if(password=='undefined' || password=='' || $.trim(password)==''){	
		error++;
		$('.error_notice_password').css({"display":"block"});
		$('.error_notice_password').text('поле не может быть пустым');
		$('.password_reg').addClass('error_order');
	}else{
		if(password.length<6){
			error++;
			$('.error_notice_password').css({"display":"block"});
			$('.error_notice_password').text('пароль должен быть не короче 6 символов');
			$('.password_reg').addClass('error_order');
		}
	}
	
	// ВНИМАНИЕ!! ДОМЕН СМЕНИТЬ
	if(error==0){
		$(".main_regs").html('');
		$(".main_regs").addClass('load'); 		
		var url=$('.authorization_component').attr('data-template');
		$.ajax({
		type: "POST", 
		data:'password='+password+'&login='+login,
		url: url+"/auth_server.php", 
			success: function(html){ 
				$(".main_regs").html(html); 
				
			}
		});
	}
	
});