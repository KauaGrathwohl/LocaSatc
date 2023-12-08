<?php
App::uses('AppController', 'Controller');

class PagesController extends AppController {

	public $uses = array('Page');

	public function index()
	{
		$this->set('fancybox', true);
		if(empty($this->request->params['pass']))
		{
			throw new NotFoundException('Conteúdo não disponível');
		}
		else
		{
			$key = implode("/", $this->request->params['pass']);

			$conditions = array(
				"Page.store_id" => $this->storeId,
				"Page.pin" => $key,
				"Page.active" => "Y"
			);

			$page = $this->Page->find('first', compact('conditions'));
			if(empty($page))
			{
				throw new NotFoundException('Conteúdo não disponível');
			}
			else
			{
				$title = $page['Page']['name'];

				$this->set(compact('page', 'title', 'key'));
			}
		}
	}

	public function get($key = null)
	{
		$conditions = array(
			"Page.pin" => $key,
			"Page.active" => "Y"
		);

		$page = $this->Page->find('first', compact('conditions'));

		return $page;
	}

	public function admin_add()
	{
		if(!empty($this->request->data))
		{
			$this->request->data['Page']['store_id'] = $this->storeId;

			if($this->Page->saveAll($this->request->data))
			{
				return $this->flash("Dados atualizados com sucesso", array('action' => 'index'), "success");
			}
		}

		$this->set('add', true);

		$this->render('admin_edit');
	}

	public function admin_edit($id)
	{
		if(!empty($this->request->data))
		{
			if($this->Page->saveAll($this->request->data))
			{
				return $this->flash("Dados atualizados com sucesso", array('action' => 'index'), "success");
			}
		}

		$this->set('add', false);

		$this->request->data = $this->Page->find('first', array(
			'conditions' => array(
				'Page.id' => $id
			)
		));
		if(empty($this->request->data))
		{
			return $this->flash('Página não encontrada', $this->referer(), 'alert');
		}
	}

}
?>
