<div class="page-header">
  <div class="pull-right btn-group">
    <a style="margin-left: 5px;" href="<?php echo $this->xHtml->url('add'); ?>" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Nova Imobiliaria</a>
  </div>
  <h2>Listando Imobiliaria</h2>
</div>
<div>
  <p class="pagination-counter"><?php echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></p>
  <table class="table table-condensed table-bordered table-striped table-hover" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th><?php echo $this->Paginator->sort('franqueado', 'Franqueado') ?></th>
        <th><?php echo $this->Paginator->sort('status', 'Status') ?></th>
        <th><?php echo $this->Paginator->sort('uf', 'UF') ?></th>
        <th><?php echo $this->Paginator->sort('municipio', 'Município') ?></th>
        <th><?php echo $this->Paginator->sort('bairro', 'Bairro') ?></th>
        <th><?php echo $this->Paginator->sort('endereco', 'Endereço') ?></th>
        <th class="actions" style="width: 250px;"><?php echo __('Actions');?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($imobiliarias as $imobiliaria) { ?>
        <tr>
          <td><?php echo $imobiliaria['Imobiliaria']['franqueado'] ?></td>
          <td><?php echo $imobiliaria['Imobiliaria']['status'] ?></td>
          <td><?php echo $imobiliaria['Imobiliaria']['uf'] ?></td>
          <td><?php echo $imobiliaria['Imobiliaria']['municipio'] ?></td>
          <td><?php echo $imobiliaria['Imobiliaria']['bairro'] ?></td>
          <td><?php echo $imobiliaria['Imobiliaria']['endereco'] ?></td>
          <td class="actions">
            <?php echo $this->xHtml->link(__('Edit'), array('action' => 'edit', $imobiliaria['Imobiliaria']['id'])); ?>
            <?php echo $this->xHtml->link(__('delete'), array('action' => 'delete', $imobiliaria['Imobiliaria']['id'])); ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php echo $this->element('pagination') ?>