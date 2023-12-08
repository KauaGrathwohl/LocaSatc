<div class="container my-6 xl:my-9" data-error="missing_controller">
	<h2 class="content-title mb-8">Oooooppssss....</h2>
	<?php if(!empty($message)): ?>
	<p class="my-8 text-lg xl:text-xl"><?php echo $message; ?></p>
	<?php endif; ?>
	<?php if(!empty($url)): ?>
	<p class="my-8">
		<strong>Mais detalhes:</strong> Não foi possível encontrar ou exibir o endereço solicitado <em><?php echo $url; ?></em>
	</p>
	<?php endif; ?>
	<?php
	if (Configure::read('debug') > 0):
		echo $this->element('exception_stack_trace');
	endif;
	?>
</div>
