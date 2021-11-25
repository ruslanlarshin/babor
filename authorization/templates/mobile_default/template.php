<?//$APPLICATION->SetAdditionalCSS($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/style.css',true);?>
<link rel="stylesheet" type="text/css" href="<?=$this->GetFolder()?>/style.css">
<?if(!in_array($this->GetFolder().'/script_my.js',$_SESSION['script'])){
	$_SESSION['script'][]=$this->GetFolder().'/script_my.js';
	?>
	<script type='text/javascript' src='<?=$this->GetFolder()?>/script_my.js'></script>
<?}?>
<div class='authorization_component_mobile authorization_component' data-template='<?=$this->GetFolder()?>'>
	<div class='top_popup'>
		<span class='title' >Авторизация</span>
		<span class='close_popup' style='display:none;'> <img  class='close_img'  src ='/images/cerrar3.png'/></span>
	</div>
	<div class='popup_content'>
		<div class='main_regs'></div>
		<div class='main_reg'>
			<table> 
				<tr>
					<td class='reg_left'><span class='text_reg' style='width: 200px;'>Введите логин</span></td><td class='reg_right'><input data-value='email' class='input_reg email_reg' type='text'  value='' /></td>
				</tr>
				<tr>
					<td><div class='error_notice error_notice_email'> поле не может быть пустым</div></td>
				</tr>
				<tr>
					<td class='reg_left'><span class='text_reg' style='width: 200px;'>Введите пароль</span></td><td class='reg_right'><input data-value='password' class='input_reg password_reg' type='text'  value='' /></td>
				</tr>
				<tr>
					<td><div class='error_notice error_notice_password'> поле не может быть пустым</div></td>
				</tr>
			</table>
			<div class='button reg_button'>Войти</div>
		</div>

		<div class='error_order_bottom auth_error' STYLE='position:relative; top :-80px; left: 10px; color:#cc5392;'>Введены неправильно логин или пароль</div>
	</div>
</div>
<style>
.authorization_component_mobile .title{
	text-align: center;
	color: black;
	margin: 0 auto;
}
.authorization_component_mobile .top_popup{
	text-align: center;
}
.authorization_component_mobile .reg_left {
	width: 100px;
}
.authorization_component_mobile .input_reg {
	width: 200px;
}
.authorization_component_mobile .reg_button{
	left: 120px;
}
</style>