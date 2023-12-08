<div class="page-header">
	<h2><?php echo __('Alterando sua senha do administrador');?></h2>
</div>

<?php echo $this->Form->create('AdminUser', array("id" => "formSenha", "url" => "/admin/cmanager/password"));?>
	<fieldset>

		<?php echo $this->Form->input("password", array("label" => "Digite a Nova Senha:", "class" => "required col-md-4")); ?>
		<?php echo $this->Form->input("repass", array("label" => "Repita a Nova Senha:", "type" => "password", "class" => "required col-md-4")); ?>

		<div class="form-actions col-md-12" style="text-align: left;">
			<?php echo $this->Form->submit('Salvar nova Senha', array('class' => 'btn btn-primary'));?>
		</div>

	</fieldset>
<?php echo $this->Form->end(); ?>

<script type="text/javascript">
<!--
$(function(){
	$("#formSenha").on("submit", function(event) {
		var p = $("#AdminUserPassword").val(), rp = $("#AdminUserRepass").val();
		if(!(p.length > 0 && rp.length > 0 && p == rp && confirm("Você confirma a alteração?")))
		{
			alert("Verifique os campos e a confirmação de senha!");

			e.preventDefault();
			return false;
		}
		return true;
	});
});
//-->
</script>
