alert(1);
	$('body').on('click', '.chat_all_text .update', function(){
		$('.text_'+$(this).attr('data-id')).addClass('hidden');
		$('.area_'+$(this).attr('data-id')).val($('.text_'+$(this).attr('data-id')).text());
		$('.area_'+$(this).attr('data-id')).removeClass('hidden');
		$(this).addClass('hidden');
		$('.delete_'+$(this).attr('data-id')).addClass('hidden');
		$('.yes_'+$(this).attr('data-id')).removeClass('hidden');
		$('.no_'+$(this).attr('data-id')).removeClass('hidden');
	});
	
	$('body').on('click', '.chat_all_text .yes', function(){
		id=$(this).attr('data-id');
		if($('.area_'+id).val()!=''){
			$.ajax({
					type: "POST", 
					url: "/ajax/chat/update.php", 
					data: 'id='+id+'&text='+$('.area_'+id).val(),
					success: function(html){ 	
						$('.text_'+id).removeClass('hidden');
						$('.text_'+id).text($('.area_'+id).val());
						$('.area_'+id).addClass('hidden');
						$('.update_'+id).removeClass('hidden');
						$('.delete_'+id).removeClass('hidden');
						$('.yes_'+id).addClass('hidden');
						$('.no_'+id).addClass('hidden');
					}
						
				});
			}else{
				alert('Введите сообщение');
			}
	});
	
	$('body').on('click', '.chat_all_text .delete', function(){
		id=$(this).attr('data-id');	
		$.ajax({
			type: "POST", 
			url: "/ajax/chat/delete.php", 
			data: 'id='+id,
			success: function(html){ 
				$.ajax({
				type: "POST", 
				url: "/ajax/chat/chat_all_message.php", 
					success: function(html){ 
						$('.chat_all_text').html(html);
						//$('.top_main_block_big_window').scrollTop($('.chat_all_text').height());
					}
				});
			}
		});
	});
	
	
	$('body').on('click', '.chat_all_text .return', function(){
		id=$(this).attr('data-id');	
		$.ajax({
			type: "POST", 
			url: "/ajax/chat/return.php", 
			data: 'id='+id,
			success: function(html){
				$.ajax({
				type: "POST", 
				url: "/ajax/chat/chat_all_message.php", 
					success: function(html){ 
						$('.chat_all_text').html(html);
						//$('.top_main_block_big_window').scrollTop($('.chat_all_text').height());
					}
				});
			}
		});
	});
	
	
	
	$('body').on('click', '.chat_all_text .no', function(){
		id=$(this).attr('data-id');
		$('.text_'+id).removeClass('hidden');
		$('.area_'+id).addClass('hidden');
		$('.update_'+id).removeClass('hidden');
		$('.delete_'+id).removeClass('hidden');
		$('.yes_'+id).addClass('hidden');
		$('.no_'+id).addClass('hidden');
	});
	
	
	
	
	$('body').on('click', '.auth_send', function(){
		if($('.chat_add_text').val()!=''){
			var template=$('.chat_component').attr('data-template');
			$.ajax({
				type: "POST", 
				data: 'text='+$('.chat_add_text').val(),
				url: template+"/add_message.php", 
					success: function(html){ 
						$('.chat_add_text').val('');
						update_chat();
					}
				});
			}
	});
$('body').on('mouseenter', '.tr_info', function(){
		var now_class='.'+$(this).attr('data-class');
		$(now_class+'.pencil').css({"opacity":"1"});
	});
	$('body').on('mouseleave', '.tr_info', function(){
		var now_class='.'+$(this).attr('data-class');
		$(now_class+'.pencil').css({"opacity":"0"});
	});

	$('body').on('click', '.profile_table .pencil', function(){
		var now_class='.'+$(this).attr('data-class');
		$(now_class+' .info_result').css({"display":"none"});
		$(now_class+' .info_edit').css({"display":"block"});
		$(this).css({"display":"none"});
		$('.ok'+now_class).css({"display":"inline-block"});
		$('.cancel'+now_class).css({"display":"inline-block"});
	});
	
	$('body').on('click', '.profile_table .cancel', function(){
		var now_class='.'+$(this).attr('data-class');
		$(now_class+' .info_result').css({"display":"block"});
		$(now_class+' .info_edit').css({"display":"none"});
		$(this).css({"display":"none"});
		$('.ok'+now_class).css({"display":"none"});
		$('.pencil'+now_class).css({"display":"inline-block"});
	});
	//Разделю на будущее-проверки могут быть разными на клиенте и сервере
	$('body').on('click', '.profile_table .ok.work', function(){
		var id=$('.main_info').attr('data-id');
		var work=$('.tr_info.work .info_edit').val();
		var now_class='.'+$(this).attr('data-class');
		if(work=='undefined' || work=='' || $.trim(work)==''){
		}else{
			$.ajax({
				type: "POST", 
				url: "/ajax/sochial/update/work.php", 
				data: 'id='+id+'&work='+work,
					success: function(html){ 
						$(now_class+' .info_result').text(work);
						$('.work.cancel').click();
					}
			});
		}
	});
	
	$('body').on('click', '.profile_table .ok.family', function(){
		var id=$('.main_info').attr('data-id');
		var family=$('.tr_info.family .info_edit').val();
		var now_class='.'+$(this).attr('data-class');
		if(family=='undefined' || family=='' || $.trim(family)==''){
		}else{
			$.ajax({
				type: "POST", 
				url: "/ajax/sochial/update/family.php", 
				data: 'id='+id+'&family='+family,
					success: function(html){ 
						$(now_class+' .info_result').text(family);
						$('.family.cancel').click();
					}
			});
		}
	});
	
	$('body').on('click', '.profile_table .ok.city', function(){
		var id=$('.main_info').attr('data-id');
		var city=$('.tr_info.city .info_edit').val();
		var now_class='.'+$(this).attr('data-class');
		if(city=='undefined' || city=='' || $.trim(city)==''){
		}else{
			$.ajax({
				type: "POST", 
				url: "/ajax/sochial/update/city.php", 
				data: 'id='+id+'&city='+city,
					success: function(html){ 
						$(now_class+' .info_result').text(city);
						$('.city.cancel').click();
					}
			});
		}
	});
	
	$('body').on('click', '.profile_table .ok.birthday', function(){
		var id=$('.main_info').attr('data-id');
		var buf=$('.tr_info.birthday .info_edit').val();
		buf=buf.split('-');
		buf=buf[2]+'.'+buf[1]+'.'+buf[0];
		var birthday=buf;
		var now_class='.'+$(this).attr('data-class');
		if(birthday=='undefined' || birthday=='' || $.trim(birthday)==''){
		}else{
			$.ajax({
				type: "POST", 
				url: "/ajax/sochial/update/birthday.php", 
				data: 'id='+id+'&birthday='+birthday,
					success: function(html){ 
						$(now_class+' .info_result').text(birthday);
						$('.birthday.cancel').click();
					}
			});
		}
	});
	
	$('body').on('click', '.upload_photo_form .button.upload', function(){
		alert(1);
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/update/photo_server.php", 
			data: $('.upload_photo_form').serialize(),
				success: function(html){ 
					$(".result_ajax").html(html); 
				}
		});
	});
	
	$('body').on('click', '.reg_btn', function(){
			$('.popup_content').css({"display":"none"});
			$.ajax({
				type: "POST", 
				url: "/ajax/registration.php", 
					success: function(html){ 
						$(".popup_content").html(html); 
						$('.popup_content').css({"display":"block"});
					}
				});
		});
		
		$('body').on('click', '.upload_photo', function(){
			window.open('/ajax/upload_photo/index.php?lang=ru&LID=s1&addDefault=N&func_name=FillProductFields', '', 'scrollbars=yes,resizable=yes,width=980,height=550,top='+parseInt((screen.height - 500)/2-14)+',left='+parseInt((screen.width - 840)/2-5));
		});
		
		$('body').on('click', '.auth_small_title', function(){
			$('.popup_content').css({"display":"none"});
			$.ajax({
				type: "POST", 
				url: "/ajax/auth_form.php", 
					success: function(html){ 
						$(".popup_content").html(html); 
						$('.popup_content').css({"display":"block"});
					}
				});
		});
		
		
		$('body').on('click', '.exit_user', function(){
			$.ajax({
				type: "POST", 
				url: "/ajax/exit_user.php", 
					success: function(html){ 
						$.ajax({
							type: "POST", 
							url: "/ajax/auth_small.php", 
								success: function(html){ 
									$(".auth_small").html(html); 
								}
							});
					}
				});
		});
		
		$('body').on('click', '.reg_btn', function(){
			$.ajax({
				type: "POST", 
				url: "/popap/reg_popap.php", 
				//data: "chapter="+$(this).attr('data-chapter')+"&bookname="+$(this).attr('data-book'),
					success: function(html){ 
						$(".top_main_block_big_window").html(html); 
					}
				});
		});
		
	//Функции для личной тсранички раздел Социальные сети
	$('body').on('click', '.friend_name, .friend_img', function(){
		var id=$(this).attr('data-id');
		$.ajax({
				type: "POST", 
				data: 'id='+id,
				url: "/ajax/sochial/profile/index.php?id="+$(this).attr('data-id'), 
					success: function(html){ 
						$('.top_main_block_big_window').html(html);
						var sNewUrl='/?main=sochial&id='+id;
						history.pushState(null, null, sNewUrl);
					}
				});
			
	});
	
	
	$('body').on('click', '.friend_message', function(){
		var id=$(this).attr('data-id');
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/sms.php",
			data: 'id='+id,			
			success: function(html){ 
				$(".top_main_block_big_window").html(html); 
				var sNewUrl='/?main=sms&option='+id;
				history.pushState(null, null, sNewUrl);
				$.ajax({
					type: "POST", 
					url: "/ajax/sochial/sms_update.php",
					data: 'id='+id,			
					success: function(html){ 
						$(".for_sms").html(html); 
						$('.sms_list').scrollTop($('.sms_height').height());
						//$('.sms_list').height(($('.top_main_block_big_window').height()*1-200)+'px');
					}
				});
			}
		});
	});
	//--Функции для личной тсранички раздел Социальные сети
	
	
	//Диалоги
	$('body').on('click', '.list_parent', function(){
		var id=$(this).attr('data-user-id');
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/sms.php",
			data: 'id='+id,			
			success: function(html){ 
				$(".top_main_block_big_window").html(html); 
				var sNewUrl='/?main=sms&option='+id;
				history.pushState(null, null, sNewUrl);
				$.ajax({
					type: "POST", 
					url: "/ajax/sochial/sms_update.php",
					data: 'id='+id,			
					success: function(html){ 
						$(".for_sms").html(html); 
						$('.sms_list').scrollTop($('.sms_height').height());
						//$('.sms_list').height(($('.top_main_block_big_window').height()*1-200)+'px');
					}
				});
			}
		});
	});
//--Диалоги
		
				// Отправка лички
	$('body').on('click', '.auth_send_sms', function(){
		var id=$(this).attr('data-id');
		var val=$('.sms_add_text').val();
		if($('.sms_add_text').val()!=''){
		$.ajax({
				type: "POST", 
				url: "/ajax/sochial/add_message.php", 
				data: 'text='+val+'&user_id='+id,
					success: function(html){ 
						$('.sms_add_text').val('');
						
						update_sms();
						
					}
				});
			}
	});
			
			
	$('body').on('click', '.sms_author_panel .sms2_update', function(){
		//$('.sms_no').click();
		$('.sms_height').attr({'data-update':'1'});
		$('.sms_div_'+$(this).attr('data-id')).addClass('hidden');
		$('.sms_textarea_'+$(this).attr('data-id')).removeClass('hidden');
		$('.sms_textarea_'+$(this).attr('data-id')).height($('.sms_div_'+$(this).attr('data-id')).height());
		$('.update_'+$(this).attr('data-id')).addClass('hidden');
		$('.yes_'+$(this).attr('data-id')).removeClass('hidden');
		$('.no_'+$(this).attr('data-id')).removeClass('hidden');
		$('.delete_'+$(this).attr('data-id')).addClass('hidden');
	});	
	
	$('body').on('click', '.sms_author_panel .sms2_no', function(){
		$('.sms_div_'+$(this).attr('data-id')).removeClass('hidden');
		$('.sms_height').attr({'data-update':'0'});
		$('.sms_textarea_'+$(this).attr('data-id')).addClass('hidden');
		$('.sms_textarea_'+$(this).attr('data-id')).height($('.sms_div_'+$(this).attr('data-id')).height());
		$('.no_'+$(this).attr('data-id')).addClass('hidden');
		$('.yes_'+$(this).attr('data-id')).addClass('hidden');
		$('.update_'+$(this).attr('data-id')).removeClass('hidden');
		$('.delete_'+$(this).attr('data-id')).removeClass('hidden');
	});
	
	$('body').on('click', '.sms_author_panel .sms2_yes', function(){
		var id=$(this).attr('data-id');
		var text=$('.sms_textarea_'+$(this).attr('data-id')).val();
		//$('.sms_div_'+$(this).attr('data-id')).text(text);
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/update.php", 
			data: 'text='+text+'&id='+id,
				success: function(html){ 
					$('.no_'+id).click();
					$('.sms_height').attr({'data-update':'0'});
					update_sms();
				}
			});
	});
	$('body').on('click', '.ajax_new', function(){
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/sms_list.php", 
			success: function(html){ 
				$(".top_main_block_big_window").html(html); 
				var sNewUrl='/?main=sms_list';
				history.pushState(null, null, sNewUrl);
			}
		});
	});
	
	$('body').on('click', '.sms_author_panel .sms2_delete', function(){
		var id=$(this).attr('data-id');
		//$('.sms_div_'+$(this).attr('data-id')).text(text);
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/delete.php", 
			data: 'id='+id,
				success: function(html){ 
					//$('.no_'+id).click();
					update_sms();
				}
			});
	});
	
	$('body').on('click', '.sms_author_panel .sms2_return', function(){
		var id=$(this).attr('data-id');
		//$('.sms_div_'+$(this).attr('data-id')).text(text);
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/return.php", 
			data: 'id='+id,
				success: function(html){ 
					//$('.no_'+id).click();
					update_sms();
				}
			});
	});
	
	
	function update_small_new(){
		$.ajax({
		type: "POST", 
		url: "/ajax/sochial/new_small_sms.php", 
			success: function(html){ 
				$('.ajax_new').html(html);
			}
		});
	}
	
	function update_sms_list(){
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/sms_list.php", 
			success: function(html){ 
				$(".top_main_block_big_window").html(html); 
				var sNewUrl='/?main=sms_list';
				history.pushState(null, null, sNewUrl);
			}
		});
	}
	function  update_chat(){
		var template=$('.chat_component').attr('data-template');
		$.ajax({
		type: "POST", 
		url: template+"/chat_all_message.php", 
			success: function(html){ 
				$('.chat_all_text').html(html);
				$('.main_block').scrollTop($('.chat_all_text').height());
			}
		});
	}
	
	function  update_sms(){
		var id=$('.sms_user_id').attr('data-user-id');
		var scroll=$('.sms_list').scrollTop();
		var height=$('.for_sms').attr('data-height');
		$.ajax({
			type: "POST", 
			url: "/ajax/sochial/sms_update.php",
			data: 'id='+id+'&scroll='+scroll+'&height='+height,			
			success: function(html){ 
				$(".for_sms").html(html); 
				//$('.sms_list').height(($('.top_main_block_big_window').height()*1-200)+'px');
			}
		});
	}
$(document).ready(function(){
	
	setInterval(function(){if($('.sms_user_id').length>0){
		var update=$('.sms_height').attr('data-update');
		if(update==0){
			update_sms();
		}
	}},5000);
	setInterval(function(){if($('.ajax_new').length>0){
		update_small_new();
	}},5000);
	setInterval(function(){if($('.list_parent').length>0){
		update_sms_list();
	}},5000);
	update_small_new();
	
});