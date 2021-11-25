<link rel="stylesheet" type="text/css" href="<?=$this->GetFolder()?>/style.css">
<?if(!in_array($this->GetFolder().'/script_my.js',$_SESSION['script'])){
	$_SESSION['script'][]=$this->GetFolder().'/script_my.js';
	?>
	<script type='text/javascript' src='<?=$this->GetFolder()?>/script_my.js'></script>
<?}?>
<div class='chat_component' data-template='<?=$this->GetFolder()?>'>
	<div class='chat_all_text' >
		<?require_once($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/chat_all_message.php');?>
	</div>
	<?if(!CUser::IsAuthorized()){?><div class='error'>Внимание ! для отправки сообщений необходимо авторизоваться!!!</div><?}?>
	<div style='height: 200px;'>
		<textarea id='anchor_chat' class='chat_add_text' ></textarea>
		<div class='button chat_add <?if(CUser::IsAuthorized()){ echo 'auth_send';}else{ echo 'auth_no_send';}?>'>
			<div class='text'><img class='img_send_email' src='/images/email2.png'  /></div>
		</div>
	</div>
</div>