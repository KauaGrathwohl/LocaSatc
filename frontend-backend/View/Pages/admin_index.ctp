<div class="page-header">
	<div class="pull-right btn-group">
		<a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Adicionar nova Página</a>
	</div>
	<h2>Páginas de Conteúdo</h2>
</div>
<table class="table table-bordered table-striped table-hover" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th style="width: 150px;"><?php echo $this->Paginator->sort('pin', "Chave");?></th>
			<th><?php echo $this->Paginator->sort('name', "Título da Página");?></th>
			<th style="width: 60px;"><?php echo $this->Paginator->sort('active', "Ativo");?></th>
			<th class="actions">Ações</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($items as $item): ?>
		<tr>
			<td><a rel="external" href="<?php echo $this->Html->url("/page/" . $item['Page']['pin']); ?>"><?php echo $item['Page']['pin']; ?></a></td>
			<td><?php echo $item['Page']['name']; ?></td>
			<td><?php echo __($item['Page']['active']); ?></td>
			<td class="actions">
				<?php echo $this->Html->link('Editar', array('action' => 'edit', $item['Page']['id'])); ?>
				<?php
					if($item['Page']['removable'] == 'Y')
					{
						echo $this->Html->link('Deletar', array('action' => 'delete', $item['Page']['id']));
					}
					else
					{
						echo $this->Html->link('Deletar', '#delete', array('class' => 'disabled'));
					}
				?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
