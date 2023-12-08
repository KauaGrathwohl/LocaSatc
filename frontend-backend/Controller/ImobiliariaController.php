<?php 
App::uses('AppController', 'Controller');

class ImobiliariaController extends AppController
{
    public $name = 'Imobiliaria';
    public $uses = array('Imobiliaria');


    public function admin_index()
    {
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('Imobiliaria.endereco' => 'asc')
        );

        $imobiliarias = $this->Paginator->paginate('Imobiliaria');
        $this->set('imobiliarias', $imobiliarias);
    }

    public function admin_add()
    {
        if(!empty($this->request->data['Imobiliaria']))
        {
            $this->Imobiliaria->create();
            if ($this->Imobiliaria->save($this->request->data['Imobiliaria']))
            {
                $this->flash('Imobiliaria adicionada com sucesso.', '/admin/imobiliaria', 'success');
                return;
            }
            else
            {
                $this->flash('Erro ao adicionar Imobiliaria.', '/admin/imobiliaria', 'error');
                return;
            }
        }

        $this->set('add', true);
        $this->render('admin_edit');
    }

    public function admin_edit($id)
    {
        if(!empty($this->request->data['Imobiliaria']))
        {   
            if ($this->Imobiliaria->save($this->request->data['Imobiliaria']))
            {
                $this->flash('Imobiliaria adicionada com sucesso.', '/admin/imobiliaria', 'success');
                return;
            }
            else
            {
                $this->flash('Erro ao adicionar Imobiliaria.', '/admin/imobiliaria', 'error');
                return;
            }
        }
        $this->set('add', false);
        $this->request->data = $this->Imobiliaria->findById($id);
    }

    public function admin_delete($id)
    {
        $this->Imobiliaria->delete($id);
        $this->flash('Imobiliaria removida com sucesso.', '/admin/imobiliaria', 'success');
    }
}
?>