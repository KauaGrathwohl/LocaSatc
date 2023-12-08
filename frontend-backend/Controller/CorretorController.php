<?php 
App::uses('AppController', 'Controller');

class CorretorController extends AppController {
    public $name = 'Corretor';
    public $uses = array('Corretor');

    public function admin_index() {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Corretor.nome' => 'asc')
        );

        $corretores = $this->Paginator->paginate('Corretor');
        $this->set('corretores', $corretores);
    }

    public function admin_add() {
        if(!empty($this->request->data))
        {
            $this->Corretor->create();
            if ($this->Corretor->save($this->request->data['Corretor'])) {
                $this->flash('Corretor cadastrado com sucesso!', '/admin/corretor', 'success');
            } else 
            {
                $this->flash('Não foi possível cadastrar o Corretor!', 'admin/corretor', 'error');
            }
        }

        $this->set('add', true);
        $this->render('admin_edit');
    }

    public function admin_edit($id)
    {
        if(!empty($this->request->data))
        {
            if ($this->Corretor->save($this->request->data['Corretor'])) {
                $this->flash('Corretor atualizado com sucesso!', '/admin/corretor', 'success');
            } else 
            {
                $this->flash('Não foi possível atualizar o Corretor!', 'admin/corretor', 'error');
            }
        }
        else
        {
            $this->request->data = $this->Corretor->findById($id);
        }

        $this->set('add', false);
    }

    public function admin_delete($id)
    {
        if ($this->Corretor->delete($id)) {
            $this->flash('Corretor excluído com sucesso!', '/admin/corretor', 'success');
        } else 
        {
            $this->flash('Não foi possível excluir o Corretor!', 'admin/corretor', 'error');
        }
    }
}
?>