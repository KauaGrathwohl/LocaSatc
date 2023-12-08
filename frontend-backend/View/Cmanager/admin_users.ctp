<div class="page-header">
	<div class="pull-right btn-group">
		<a href="<?php echo $this->Html->url(array('action' => 'user_add')); ?>" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Adicionar novo Usuario</a>
	</div>
	<h2>Usuários Administrativos</h2>
</div>

<?php
$action_filters = array();
foreach ($types as $k => $t) {
  $action_filters["type_{$k}"] = "Tipo " . trim($t);
}

echo $this->element('search_bar', array(
  "action_filters" => $action_filters,
  "search_filters" => array(
    "User.name" => array("label" => "Nome", "placeholder" => "Buscar por Nome do Usuário"),
    "User.email" => array("label" => "E-mail", "placeholder" => "Buscar por E-mail do Usuário"),
  )
)); ?>

<p class="pagination-counter"><?php echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></p>

<div class="table-responsive">
	<table class="table table-bordered table-striped table-hover" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('name', "Nome");?></th>
				<th style="width: 112px;"><?php echo $this->Paginator->sort('username', "Usuário");?></th>
				<th><?php echo $this->Paginator->sort('email', "E-mail");?></th>
				<th style="width: 112px;"><?php echo $this->Paginator->sort('type', "Tipo");?></th>
				<th style="width: 72px;"><?php echo $this->Paginator->sort('active', "Ativo");?></th>
				<th class="actions">Ações</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$usuarioUsername = $this->Session->read('Auth.AdminUser.username');
		foreach ($items as $item): ?>
			<tr>
				<td><?php echo $item['AdminUser']['name']; ?></td>
				<td><?php echo $item['AdminUser']['username']; ?></td>
				<td><?php echo $item['AdminUser']['email']; ?></td>
				<td><?php echo $types[$item['AdminUser']['type']]; ?></td>
				<td><?php echo __($item['AdminUser']['active']); ?></td>
				<td class="actions">
					<?php
					$canEdit = ($item['AdminUser']['username'] !== "burnweb" || $item['AdminUser']['username'] === $usuarioUsername);
					?>
					<?php echo $this->Html->link('Editar', array('action' => 'user_edit', $item['AdminUser']['id']), array('disabled' => !$canEdit, 'class' => $canEdit ? : "disabled")); ?>
					<?php echo $this->Html->link('Deletar', array('action' => 'user_delete', $item['AdminUser']['id']), array('disabled' => !$canEdit, 'class' => $canEdit ? : "disabled")); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $this->element('pagination'); ?>
