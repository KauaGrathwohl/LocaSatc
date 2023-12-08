<div class="page-header">
	<div class="pull-right btn-group">
		<a href="<?php echo $this->xHtml->url(array('action' => 'add')); ?>" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Nova Notícia</a>
	</div>
	<h2><?php echo __('Notícias');?></h2>
</div>

<?php
$action_filters = array();

$search_filters = array(
  "Noticia.name" => array("label" => "Título", "placeholder" => "Buscar por Título da Notícia"),
  "Noticia.data" => array("label" => "Data", "placeholder" => "Buscar por Data de Postagem"),
);

echo $this->element('search_bar', compact('action_filters', 'search_filters')); ?>

<p class="pagination-counter"><?php echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></p>

<div class="table-responsive">
	<table class="table table-condensed table-bordered table-striped table-hover" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th style="width: 118px;"><?php echo $this->Paginator->sort('data', 'Publicação');?></th>
				<th><?php echo $this->Paginator->sort('name', 'Título da Notícia');?></th>
				<th class="actions" style="width: 221px;"><?php echo __('Actions');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($items as &$noticia): ?>
			<tr class="<?php
				echo classnames(array(
					"success" => (strtotime($noticia['Noticia']['data']) > time()),
					"danger" => ($noticia['Noticia']['ativo'] === 'N'),
				)); ?>">
				<td class="text-center" style="font-size: 12px;"><?php echo $this->Util->date($noticia['Noticia']['data'], "d/m/Y H:i"); ?></td>
				<td class="text-left" style="font-size: 13px;"><?php echo $noticia['Noticia']['name']; ?></td>
				<td class="actions">
					<?php
					$preview_url = array('/noticia', $noticia['Noticia']['slug']);
					$preview_url[] = "preview:" . md5($noticia['Noticia']['id']);

					echo $this->xHtml->link(__('View'), implode("/", $preview_url), array('target' => '_blank')); ?>
					<?php echo $this->xHtml->link(__('Edit'), array('action' => 'edit', $noticia['Noticia']['id'])); ?>
					<?php echo $this->xHtml->link(__('Delete'), array('action' => 'delete', $noticia['Noticia']['id'])); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php echo $this->element('pagination'); ?>
