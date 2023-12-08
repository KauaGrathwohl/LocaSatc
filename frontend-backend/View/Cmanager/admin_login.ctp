<h1 id="logo">
  <img style="max-width: 270px; margin: 0 auto; display: block;" src="" alt="<?php echo $conf['name']; ?>" />
</h1>

<?php echo $this->xForm->create('AdminUser', array("url" => "/admin/cmanager/login", "id" => "formLogin")); ?>
	<fieldset class="well">
		<h3><i class="pull-right glyphicon glyphicon-lock"></i> Área de acesso restrito</h3>
		<?php echo $this->xForm->input('username', array('label' => 'Usuário', 'class' => 'input-xlarge')); ?>
		<?php echo $this->xForm->input('password', array('label' => 'Senha', 'type' => 'password', 'class' => 'input-xlarge')); ?>
		<div class="row-button text-center">
			<button class="btn btn-primary btn-lg">Entrar / Login</button>
		</div>
	</fieldset>
<?php echo $this->xForm->end(); ?>

<script type="text/javascript">
<!--
$(window).load(function(){
	$("#UserUsername").focus();
});
//-->
</script>
