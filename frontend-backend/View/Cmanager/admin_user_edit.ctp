<div class="page-header">
	<div class="pull-right btn-group">
		<a href="<?php echo $this->Html->url(array('action' => 'users')); ?>" class="btn btn-info">Listar Usuários</a>
	</div>
	<h2><?php echo !$add ? "Editando Usuário" : "Novo Usuário"; ?></h2>
</div>

<?php
$form_uri = array('controller' => 'cmanager', 'action' => 'user_add');
if(!$add)
{
	$form_uri['action'] = 'user_edit';
}

echo $this->xForm->create('AdminUser', array('url' => $form_uri, 'type' => 'file'));?>
	<fieldset>
		<?php if(!$add) echo $this->xForm->input('id');?>
		<?php echo $this->xForm->input('type', array('label' => 'Tipo de Acesso', 'class' => 'required')); ?>
		<?php echo $this->xForm->input('name', array('label' => 'Nome do Usuário', 'class' => 'required col-md-4')); ?>
		<?php echo $this->xForm->input('username', array('label' => 'Login/Usuário', 'class' => 'required col-md-3')); ?>
		<?php echo $this->xForm->input('password', array('label' => 'Senha Administrativa', 'class' => 'col-md-2')); ?>
		<?php echo $this->xForm->input('email', array('label' => 'E-mail', 'class' => 'required col-md-6')); ?>
		<?php echo $this->xForm->input('active', array('label' => 'Ativo')); ?>

		<div class="form-actions col-md-12">
			<?php echo $this->xForm->submit('Salvar usuário', array('class' => 'btn btn-primary btn-lg')); ?>
		</div>
	</fieldset>
</form>
