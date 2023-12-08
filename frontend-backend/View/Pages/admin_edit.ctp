<div class="page-header">
	<div class="pull-right btn-group">
		<a href="<?php echo $this->Html->url(array('action' => 'index')); ?>" class="btn btn-info">Listar Páginas</a>
	</div>
	<h2><?php echo !$add ? "Editando Página de Conteúdo" : "Nova Página de Conteúdo"; ?></h2>
</div>

<?php
$form_uri = array('action' => 'add');
if(!$add)
{
	$form_uri['action'] = 'edit';
	$form_uri[] = $this->request->params['pass'][0];
}

echo $this->Form->create('Page', array('url' => $form_uri));?>
	<fieldset>
		<?php if(!$add) echo $this->Form->input('id');?>
		<?php echo $this->Form->input('pin', array('label' => 'Chave Única de Acesso', 'class' => 'required col-md-2')); ?>
		<?php echo $this->Form->input('name', array('label' => 'Título da Página', 'class' => 'required col-md-3')); ?>
		<?php echo $this->Form->input('page', array('label' => 'Conteúdo da Página', 'class' => 'required editor')); ?>
		<?php echo $this->Form->input('active', array('label' => 'Página Ativa')); ?>
		<div class="form-actions col-md-12">
			<?php echo $this->Form->submit('Salvar página', array('class' => 'btn btn-primary')); ?>
		</div>
	</fieldset>
</form>
