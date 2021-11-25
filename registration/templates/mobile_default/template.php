<?//$APPLICATION->SetAdditionalCSS($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/style.css',true);?>
<link rel="stylesheet" type="text/css" href="<?=$this->GetFolder()?>/style.css">
<?if(!in_array($this->GetFolder().'/script_my.js',$_SESSION['script'])){
	$_SESSION['script'][]=$this->GetFolder().'/script_my.js';
	?>
	<script type='text/javascript' src='<?=$this->GetFolder()?>/script_my.js'></script>
<?}?>
<div class='registration_component' data-template='<?=$this->GetFolder()?>'>
	<div class='top_popup'>
		<span class='title' >Регистрация</span>
		<span class='close_popup' style='display: none;'> <img  class='close_img'  src ='/images/cerrar3.png'/></span>
	</div>
	<div class='popup_content'>
			<div class='main_reg'>
				<table>
					<tr>
						<td class='reg_left'><span class='text_reg' style='width: 300px;'>Введите ваше имя</span></td><td class='reg_right'><input data-value='name' class='input_reg name_reg' type='text'  value='' /></td>
						<td><div class='error_notice error_notice_name'> поле не может быть пустым</div></td>
					</tr>
					<tr>
						<td class='reg_left'><span class='text_reg' style='width: 300px;'>Введите фамилию</span></td><td class='reg_right'><input data-value='family' class='input_reg family_reg' type='text'  value='' /></td>
						<td><div class='error_notice error_notice_family'> поле не может быть пустым</div></td>
					</tr>
					<tr>
						<td class='reg_left'><span class='text_reg' style='width: 300px;'>Введите логин</span></td><td class='reg_right'><input data-value='login' class='input_reg login_reg' type='text'  value='' /></td>
						<td><div class='error_notice error_notice_login'> поле не может быть пустым</div></td>
					</tr>
					<tr>
						<td class='reg_left'><span class='text_reg' style='width: 300px;'>Введите email</span></td><td class='reg_right'><input data-value='email' class='input_reg email_reg' type='text'  value='' /></td>
						<td><div class='error_notice error_notice_email'> поле не может быть пустым</div></td>
					</tr>
					<tr>
						<td class='reg_left'><span class='text_reg' style='width: 300px;'>Введите пароль</span></td><td class='reg_right'><input data-value='password' class='input_reg password_reg' type='text'  value='' /></td>
						<td><div class='error_notice error_notice_password'> поле не может быть пустым</div></td>
					</tr>
					<tr>
						<td class='reg_left'><span class='text_reg' style='width: 300px;'>Подтвердите пароль</span></td><td class='reg_right'><input data-value='password2' class='input_reg password2_reg' type='text'  value='' /></td>
						<td><div class='error_notice error_notice_password2'> поле не может быть пустым</div></td>
					</tr>
				</table>
				<div class='button reg_button'>Зарегестрироваться</div>
			</div>
		</div>
</div>
<style>
.registration_component .title{
    text-align: center;
    color: black;
    margin: 0 auto;
}
.registration_component .top_popup {
    text-align: center;
}
</style>