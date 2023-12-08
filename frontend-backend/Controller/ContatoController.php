<?php
App::uses('AppController', 'Controller');

class ContatoController extends AppController
{
	public $uses = array();
	public $components = array('xEmail');

	private $reCaptcha = null;

	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->reCaptcha = new ReCaptcha("6LcrZPYjAAAAAM0eW8ufQMiNpbju8_BaQREVTWBJ");
	}

	public function index()
	{
		$this->set('title', 'Contato');

		if (!empty($this->request->data['Contato']))
		{
			$form = $this->request->data['Contato'];

			if (empty($form["name"]))
			{
				exit();
			}

			$resp = null;

			// Was there a reCAPTCHA response?
			if (!empty($_POST["g-recaptcha-response"]))
			{
				$resp = $this->reCaptcha->verifyResponse(
					$this->request->clientIp(), $_POST["g-recaptcha-response"]
				);
			}

			if (!($resp !== null && $resp->success))
			{
				return $this->flash('Erro submeter seus dados de cadastro. Por favor marque o captcha de verificação!');
			}

			$this->set('site', $this->conf['domain']);
			$this->set('data', date("d/m/Y"));
			$this->set('hr', date("H:i:s"));

			switch ($form["departamento"])
			{
				case 'Comercial':
					$this->xEmail->to = "Comercial 4oito <comercial@4oito.com.br>";
					break;
				default:
					$this->xEmail->to = "Redação 4oito <redacao@4oito.com.br>";
					break;
			}

			$this->xEmail->replyTo = $form["name"] . " <" . $form["email"] . ">";

			$this->xEmail->subject = 'Contato do Site: ' . $form["assunto"];

			$this->xEmail->template = 'contact';
			$this->xEmail->sendAs = 'html';

			$dados_email = array(
				"Departamento" => $form["departamento"],
				"Assunto" => $form["assunto"],
				"Nome" => $form["name"],
				"E-mail" => $form["email"],
				"Telefone" => $form["phone"],
				"Mensagem" => nl2br($form["message"]),
			);
			$this->set('dados', $dados_email);
			$this->set('subject', $this->xEmail->subject);
			$this->set('IP', $this->request->clientIp());

			if ($this->xEmail->send())
			{
				return $this->flash('Contato enviado com sucesso! Muito obrigado.', '/contato', 'success');
			}
			else
			{
				$this->flash('Erro ao Enviar sua Mensagem!<br />Favor tente novamente mais Tarde!<br /><br />' . stripslashes($this->xEmail->smtpError), null, 'error');
			}
		}
	}
}
