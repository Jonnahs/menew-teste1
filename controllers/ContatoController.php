<?php

//incluindo classes da camada Model
require_once 'models/ContatoModel.php'; 
  
class ContatoController
{
    /**
    * Efetua a manipulação dos modelos necessários
    * para a aprensentação da lista de contatos
    */
    public function listarContatoAction()
    {
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        } 

        $o_Contato = new ContatoModel();
        
        //Listando os contatos cadastrados
        $v_contatos = $o_Contato->_list();
        
        //definindo qual o arquivo HTML que será usado para
        //mostrar a lista de contatos
        $o_view = new View('views/listarContatos.html');
          
        //Passando os dados do contato para a View
        $o_view->setParams(array('v_contatos' => $v_contatos));
        
        //Imprimindo código HTML
        $o_view->showContents();
        
    }
      
      
    /**
    * Gerencia a requisiçães de criação
    * e edição dos contatos 
    */
    public function manterContatoAction()
    {
        $o_contato = new ContatoModel();
          
        //verificando se o id do contato foi passado
        if( isset($_REQUEST['in_con']) )
            //verificando se o id passado é valido
            if( DataValidator::isNumeric($_REQUEST['in_con']) )
                //buscando dados do contato
                $o_contato->loadById($_REQUEST['in_con']);
              
        if(count($_POST) > 0)
        {
            $o_contato->setNome(DataFilter::cleanString($_POST['st_nome']));
            $o_contato->setEmail(DataFilter::cleanString($_POST['st_email']));
            $o_contato->setCidade(DataFilter::cleanString($_POST['st_cidade']));
            $o_contato->setEstado(DataFilter::cleanString($_POST['st_estado']));
            $o_contato->setCategoria(DataFilter::cleanString($_POST['st_categoria']));

            // Cria sessão para envio de mensagem
            session_start();
            $_SESSION['message'] = 'Registro cadastrado com sucesso! '.'<b>'.$o_contato->getNome().'</b>'.' foi incluído na agenda.';
            $_SESSION['type'] = 'success';
            $_SESSION['css'] = 'alert-success';
              
            //salvando dados e redirecionando para a lista de contatos
            if($o_contato->save() > 0)
                Application::redirect('?controle=Contato&acao=listarContato');
        }
              
        $o_view = new View('views/manterContato.html');
        $o_view->setParams(array('o_contato' => $o_contato));
        $o_view->showContents();
    }
      
    /**
    * Gerencia a requisições de exclusão dos contatos
    */
    public function apagarContatoAction()
    {
        if( DataValidator::isNumeric($_GET['in_con']) )
        {
            //apagando o contato
            $o_contato = new ContatoModel();
            $o_contato->loadById($_GET['in_con']);
            $o_contato->delete();

            // Cria sessão para envio de mensagem
            session_start();
            $_SESSION['message'] = 'Registro excluído com sucesso! '.'<b>'.$o_contato->getNome().'</b>'.' foi apagado da agenda.';
            $_SESSION['type'] = 'success';
            $_SESSION['css'] = 'alert-danger';
              
            //Apagando os telefones do contato
            $o_telefone = new TelefoneModel();
            $v_telefone = $o_telefone->_list($_GET['in_con']);
            foreach($v_telefone AS $o_telefone)
                $o_telefone->delete();
            Application::redirect('?controle=Contato&acao=listarContato');
        }   
    }
}
?>