<div class="content-header">
	<h2>Executar Query SQL</h2>
</div>

<?php if(!empty($retorno) && is_array($retorno)): ?>
<div class="retorno" style="background: #F7F7F7; padding: 3px; margin-bottom: 16px;">
	<?php foreach ($retorno as $i => $r): ?>
	<pre><?php print_r($r); ?></pre>
	<?php endforeach ?>
</div>
<?php endif; ?>

<p><strong>Atenção:</strong> Este recurso poderá danificar o banco de dados de seu site, favor usar com cuidado.</p>

<?php echo $this->Form->create("DB", array("url" => "/admin/cmanager/query")); ?>
	<fieldset>
		<?php echo $this->Form->input("query", array('label' => 'SQL Query', 'cols' => 90, 'rows' => 8)); ?>
		<div class="form-actions col-md-12">
			<?php echo $this->Form->submit("Executar / Run", array('class' => 'btn btn-primary')); ?>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>
