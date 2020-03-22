<?php

class Admin_CategoriasController extends Zend_Controller_Action
{
    /**
     * The default action - show the home page
     */
	

	public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	$this->id = $this->getRequest()->getParam("id_categoria");
    	
    	
		
    	
    	if($this->id != 0)
    	{
    		$this->tipo_acao = "edit";
    	}
    	
    	$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();

    }
	
	
    public function insertAction()
    {	
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$post = $this->_request->getParams();
    	
    	
    	$form = $this->getForm();
    	if ($form->isValid($_POST) == false) 
        {
        	$this->view->form = $form;
        	$this->render("form");
        	
        }
        
        $obj = new Tb_gcool_categoria();
        $obj->get($post["id"]);
        $obj->_setFrom($post);
        $obj->save();
        
        if($post["ID_MODULO"] != "")
        {
        	//die('aaa');
        	
        	$sql = "SELECT * FROM  tb_gcool_categoria AS categoria "; 
			$sql .= "LEFT JOIN tb_sys_modulo_tb_cool_categoria AS cat_modulos ON categoria.id = cat_modulos.ID_CATEGORIA "; 
			$sql .= "WHERE categoria.id = '".$obj->id."'";
			
			//echo $sql;
			
			$categoria_mod = new  Tb_sys_modulo_tb_cool_categoria();
			$categoria_mod->query($sql);
			$dados = $categoria_mod->allToArray();
        	$dados = $dados[0];
			
			//print_r($dados);
			
        	$obj2 = new Tb_sys_modulo_tb_cool_categoria();
        	$obj2->where("ID_MODULO = '".$dados["ID_MODULO"]."' AND ID_CATEGORIA = '".$dados["ID_CATEGORIA"]."'");
			
        	$qtd = $obj2->find();
        	
        	$obj2->ID_CATEGORIA = $post["id"];
        	$obj2->ID_MODULO = $post["ID_MODULO"];
        	
        	if($qtd == 0)
        	{
        		$obj2->insert();
        	
        	}else
        	{
        		$obj2->update(true);
        		
        	}
        	
        	
        }else
        {
        	$obj_del = new Tb_sys_modulo_tb_cool_categoria();
        	$obj_del->where("ID_CATEGORIA = '".$post["id"]."'");
        	$obj_del->delete(true);
        }
        
        $this->_forward("index");
        
        
        
    }
    
	public function formAction()
	{
		$this->view->form = $this->getForm();
	}
	
	
    public function indexAction ()
    {

        $jstree = new My_Plugins_jstree_classtreeCategorias();
        
        
        if(isset($_GET["reconstruct"])) {
			$jstree->_reconstruct();
			die();
		}
		if(isset($_GET["analyze"])) {
			echo $jstree->_analyze();
			die();
		}
		
		if(isset($_REQUEST["operation"]) && strpos($_REQUEST["operation"], "_") !== 0 && method_exists($jstree, $_REQUEST["operation"])) {
			header("HTTP/1.0 200 OK");
			header('Content-type: application/json; charset=utf-8');
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Pragma: no-cache");
			echo $jstree->{$_REQUEST["operation"]}($_REQUEST);
			die();
		}
		header("HTTP/1.0 404 Not Found"); 

    }
    
 	public function uploadiconeAction()
    {
    	$pars = $this->_request->getParams();
    	
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$obj = new Tb_gcool_categoria();
    	$obj->get($pars["id_categoria"]);
    	$obj->fetch();
    	
    	$file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, 0, 17, false, $pasta_destino.$obj->icone_pequeno);
               
   	
    	
    	$obj->icone_grande = $upload->getFileName();
    	$obj->update(); 
			
        $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000));
			
        
                
    
    	$obj->icone_pequeno = $upload->getFileName();
    	$obj->update(); 
			
         $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000));
				
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;	
           
    }
    
    
	public function uploadiconegrAction()
    {
    	$pars = $this->_request->getParams();
    	
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
                
    	$obj = new Tb_gcool_categoria();
    	$obj->get($pars["id_categoria"]);
    	$obj->fetch();
    	
    	$file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, 156, 76, true, $pasta_destino.$obj->icone_grande);
               
   	
    	
    	$obj->icone_grande = $upload->getFileName();
    	$obj->update(); 
			
        $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000));
				
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;	
           
           
    }
    
    
 	public function editarAction()
    {
    	 $pars = $this->_request->getParams();
    	 $obj = new Tb_gcool_categoria();
    	 $obj->get($pars["id"]);
    	 $obj->fetch();
    	 
     	      	 
    	 $this->view->modelo = $obj->toObject(); 
     	 $this->view->id_categoria = $pars["id"];
       	 $this->render("form");
    	
    }
    
    
    
	public function getForm()
	{
			
		
			$form = new Zend_Form();
			$form->setAction('/admin/categorias/insert')
				 ->setMethod('post')
				 ->setAttrib("id", "form1");
					
		
		
			$sql = "SELECT * FROM  tb_gcool_categoria AS categoria "; 
			$sql .= "LEFT JOIN tb_sys_modulo_tb_cool_categoria AS cat_modulos ON categoria.id = cat_modulos.ID_CATEGORIA "; 
			$sql .= "WHERE categoria.id = '".$this->id."'";
			
			//echo $sql;
			
			$categoria_mod = new  Tb_sys_modulo_tb_cool_categoria();
			$categoria_mod->query($sql);
			
			$popula_dados = array();
			
			if($this->id != 0)
			{	
				
				
				
				$popula_dados = $categoria_mod->allToArray();
				
				//print_r($popula_dados);

			}
			
			
			
			$modulo = new Tb_sys_modulo();
			$dados = array();
			$modulo->order("DESCRICAO");
			$modulo->find();
			$object = $modulo->allToObject();	 
									 
			$options[""] = "Selecione...";
			foreach($object as $chave => $valor)
			{
				$options[$valor->ID_MODULO] = $valor->DESCRICAO;
			}
			
			
			
			$frm_nome_categoria = new Zend_Form_Element_Text('title');
			$frm_nome_categoria->setLabel("Nome da categoria: ");
			$frm_nome_categoria->setRequired(true);
			
			
			
			$frm_id_modulo = new Zend_Form_Element_Select('ID_MODULO');
			$frm_id_modulo->addMultiOptions($options)
						 ->setRequired(false)
						 ->setLabel("Esta categoria esta associada a algum modulo especial? ");

						 
			$frm_id = new Zend_Form_Element_Text("id");
			$frm_id->setLabel("Cod. categoria");			 
					
			$form->addElement($frm_id);
			$form->addElement($frm_nome_categoria);		 
			$form->addElement($frm_id_modulo);
			
				
			 //print_r($popula_dados);		 
				 	
			 $form->populate($popula_dados[0]);
				 
			 return $form;
				 
	}
	
	public function gridordenaajaxAction()
	{
		
	
		$obj = new Tb_gcool_categoria();
		$obj->where("FL_DESTACAR = 1");
		$obj->order("ORDEM");
		$obj->find(true);
		
		$this->view->dados = $obj->allToObject();
	}	
	
	public function ordenaAction()
    {
    	
    	try 
    	{
    	
	    	$sort = $this->_request->getParam("sort");
	    	
	    	    	
	    	$lista = explode(",", $sort);
	    	
	    	foreach($lista as $chave => $valor)
	    	{
	    		$obj = new Tb_gcool_categoria();
	    		$obj->get($valor);
	    		$obj->fetch();
	    		$obj->ORDEM = $chave + 1;
	    		$obj->update();
	    	}
	    	
	    	$this->_helper->layout()->disablelayout();
	    	$this->_helper->viewRenderer->setNoRender();  	
	    	
			$content = array('erro' => 'false', 'html' => '', 'msg' => utf8_encode('Ordernação efetuada com sucesso!'));
	    	
    	} catch (Exception $e) 
    	{
				
			 $content = array('erro' => 'true', 'html' => '', 'msg' => $e->getMessage());
				
		}
			$jsonData = Zend_Json::encode($content);
			$this->getResponse()
			->setHeader('Content-Type', 'text/html')
			->setBody($jsonData)
			->sendResponse();
			exit;	
    }
	
	public function gridordenaAction()
	{
		
	}
	
	public function salvarAction()
    {	
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$obj = new Tb_gcool_categoria();
    	
    	$post = $this->_request->getParams();
    	$id = $post["id_categoria"];
    	
    	if($this->tipo_acao == "insert")
    	{
    		
    	}
    	elseif($this->tipo_acao == "edit")
    	{
    		$obj->get($id);
			    		    		
    	}
    	
    	
    		if($post['FL_DESTACAR'] == "1")
			{
				
				
				if($obj->FL_DESTACAR == "0")
				{
									
					$maxOrdem = new Tb_gcool_categoria();
    				$maxOrdem->select("MAX(ORDEM) as ordem");
    				$maxOrdem->find();
    				$maxOrdem->fetch();
    				
    				$obj->ORDEM = $maxOrdem->ordem + 1;
				}
			}
    	
    	$obj->_setFrom($post);
 	
    	
    	
		$result = $obj->validate();
		
	
		
		if(count($result) == 0){
			
			
			
			
			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 }else
        	 {          	 
        	 	$msg = "Registro inserido com sucesso!";
        	 	
        	 }
			
        
        	 
        	 $obj->save();	
        	 
        
     
        	 $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj);
			
		}else{
			
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = utf8_encode($erro);
				}
			}
			
			$validacaoToHtml = My_Plugins_Util::validacaoToHtml($array_erros);
			
			$this->render("form");
			$html = $this->getResponse()->getBody();
	    	$content = array('erro' => 'valida', 'html' =>  $html, 'campos_erros' => $array_erros, 'error_html' => $validacaoToHtml, 'msg' => 'Preencha os campos obrigatorios');
		}
		
        
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
				
				
    }
    
}
