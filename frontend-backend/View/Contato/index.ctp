<div class="container my-6 xl:my-9">
	<h2 class="content-title mb-8">Entre em Contato</h2>

	<div class="flex flex-col lg:flex-row">
		<div class="flex-grow">
			<p class="mb-5">Entre em contato com a nossa equipe através do formulário abaixo:</p>

			<?php
			$departamentos = array(
				'Redação' => 'Redação',
				'Comercial' => 'Comercial'
			);

			echo $this->xForm->create('Contato', array(
				'id' => 'formContact',
				'url' => '/contato',
				'class' => 'ui-form-1',
				'data-validate' => true
			));
			?>
				<fieldset>
					<div class="group-left">
						<?php echo $this->xForm->input('name', array('label' => false, 'placeholder' => 'Nome', 'required' => true)); ?>
						<?php echo $this->xForm->input('email', array('label' => false, 'placeholder' => 'E-mail', 'required' => true)); ?>
						<?php echo $this->xForm->input('phone', array('label' => false, 'placeholder' => 'Telefone', 'required' => true, 'data-jmask' => 'phone')); ?>
						<?php echo $this->xForm->input('departamento', array(
							'label' => false,
							'empty' => 'Departamento',
							'options' => $departamentos
						)); ?>
						<?php echo $this->xForm->input('assunto', array('label' => false, 'placeholder' => 'Assunto', 'required' => true)); ?>
						<?php echo $this->xForm->input('message', array('type' => 'textarea', 'label' => false, 'placeholder' => 'Mensagem')); ?>
					</div>
					<div class="mt-2.5 lg:mt-3.5 g-recaptcha" data-sitekey="6LcrZPYjAAAAADOAZQCEi7IYedQoAv1qWIWvKkV7" data-callback="enableSubmitBtn"></div>
					<div class="mt-6">
						<?php echo $this->xForm->submit("Enviar", array("class" => "btn btn-primary disabled", "disabled" => true)); ?>
					</div>
				</fieldset>
			<?php echo $this->xForm->end(); ?>
		</div>
	</div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
