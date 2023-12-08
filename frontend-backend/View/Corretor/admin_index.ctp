<div class="page-header">
  <div class="pull-right btn-group">
    <a style="margin-left: 5px;" href="<?php echo $this->xHtml->url('add'); ?>" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Novo Corretor</a>
  </div>
  <h2>Listando Corretores</h2>
</div>
<div>
  <p class="pagination-counter"><?php echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></p>
  <table class="table table-condensed table-bordered table-striped table-hover" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th><?php echo $this->Paginator->sort('nome', 'Nome') ?></th>
        <th><?php echo $this->Paginator->sort('telefone', 'Telefone') ?></th>
        <th><?php echo $this->Paginator->sort('email', 'Email') ?></th>
        <th><?php echo $this->Paginator->sort('cargo', 'Cargo') ?></th>
        <th><?php echo $this->Paginator->sort('unidade', 'Unidade') ?></th>
        <th class="actions" style="width: 250px;"><?php echo __('Actions');?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($corretores as $corretor) { ?>
        <tr>
          <td><?php echo $corretor['Corretor']['nome'] ?></td>
          <td><?php echo $corretor['Corretor']['telefone'] ?></td>
          <td><?php echo $corretor['Corretor']['email'] ?></td>
          <td><?php echo $corretor['Corretor']['cargo'] ?></td>
          <td><?php echo $corretor['Corretor']['unidade'] ?></td>
          <td class="actions">
            <?php echo $this->xHtml->link(__('Edit'), array('action' => 'edit', $corretor['Corretor']['id'])); ?>
            <?php echo $this->xHtml->link(__('delete'), array('action' => 'delete', $corretor['Corretor']['id'])); ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php echo $this->element('pagination') ?>