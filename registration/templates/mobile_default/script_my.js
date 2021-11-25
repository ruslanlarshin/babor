$(document).ready(function(){
	$('.error_notice').css({"display":"none"});
	
		$('body').on('click', ' .input_reg', function(){
			$(this).removeClass('error_order');
			$('.error_notice_'+$(this).attr('data-value')).css({"display":"none"});
		});
		
		$('body').on('click', ' .reg_button', function(){
			$('.error_notice').css({"display":"none"});
			$('.input_reg').removeClass('error_order');
			var error=0;
				
			var name=$('.name_reg').val();
			if(name=='undefined' || name=='' || $.trim(name)==''){	
				error++;
				$('.error_notice_name').css({"display":"block"});
				$('.error_notice_name').text('���� �� ����� ���� ������');
				$('.name_reg').addClass('error_order');
			}
			var pattern=/[�-��-�]/;
				if(pattern.test(name)==false){
					error++;
					$('.error_notice_name').text('��� ������ ���� �� �������');
					$('.error_notice_name').css({"display":"block"});
					$('.name_reg').addClass('error_order');
				}
				
			var family=$('.family_reg').val();
			if(family=='undefined' || family=='' || $.trim(family)==''){	
				error++;
				$('.error_notice_family').css({"display":"block"});
				$('.error_notice_family').text('���� �� ����� ���� ������');
				$('.family_reg').addClass('error_order');
			}
			var pattern=/[�-��-�]/;
				if(pattern.test(family)==false){
					error++;
					$('.error_notice_family').text('������� ������ ���� �� �������');
					$('.error_notice_family').css({"display":"block"});
					$('.family_reg').addClass('error_order');
				}
				
				
			var login=$('.login_reg').val();
			if(login=='undefined' || login=='' || $.trim(login)==''){	
				error++;
				$('.error_notice_login').css({"display":"block"});
				$('.error_notice_login').text('���� �� ����� ���� ������');
				$('.login_reg').addClass('error_order');
			}
			
			
			
			var email=$('.email_reg').val();
			if(email=='undefined' || email=='' || $.trim(email)==''){	
				error++;
				$('.error_notice_email').css({"display":"block"});
				$('.error_notice_email').text('���� �� ����� ���� ������');
				$('.email_reg').addClass('error_order');
			}
			var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
				if(pattern.test(email)==false){
					error++;
					$('.error_notice_email').text('������ ������������� email');
					$('.error_notice_email').css({"display":"block"});
					$('.email_reg').addClass('error_order');
				}
			
			var password=$('.password_reg').val();
			if(password=='undefined' || password=='' || $.trim(password)==''){	
				error++;
				$('.error_notice_password').css({"display":"block"});
				$('.error_notice_password').text('���� �� ����� ���� ������');
				$('.password_reg').addClass('error_order');
			}else{
				if(password.length<6){
					error++;
					$('.error_notice_password').css({"display":"block"});
					$('.error_notice_password').text('������ ������ ���� �� ������ 6 ��������');
					$('.password_reg').addClass('error_order');
				}
			}
			var password2=$('.password2_reg').val();
			
			if(password2=='undefined' || password2=='' || $.trim(password2)==''){	
				error++;
				$('.error_notice_password2').css({"display":"block"});
				$('.error_notice_password2').text('���� �� ����� ���� ������');
				$('.password2_reg').addClass('error_order');
			}else{
				if(password!=password2){	
					error++;
					$('.error_notice_password2').css({"display":"block"});
					$('.error_notice_password2').text('������ ������ ���������');
					$('.password2_reg').addClass('error_order');
				}
			}
			//�������� ����������� ������
			var url=$('.registration_component').attr('data-template');
			$.ajax({
				type: "POST", 
				data:'password='+password+'&login='+login+'&password2='+password2+'&email='+email+'&family='+family+'&name='+name,
				url: url+"/reg_double_login.php", 
					success: function(html){ 
						if(html*1==1){
							$('.error_notice_login').css({"display":"block"});
							$('.error_notice_login').text('������ ����� ��� �����');
							error++;
						}
							if(error==0){
								$(".main_reg").html(''); 
								$(".main_reg").addClass('load'); 
								var url=$('.registration_component').attr('data-template');
								$.ajax({
								type: "POST", 
								data:'password='+password+'&login='+login+'&password2='+password2+'&email='+email+'&family='+family+'&name='+name,
								url: url+"/reg_server.php", 
									success: function(html){ 
										$(".main_reg").html(html); 
									}
								});
							}
					}
				});		
		});
});