<?php 

class Admin_ControladoresnaocadastradosController extends Zend_Controller_Action{ 

	public function indexAction() 
	{
		$dir = $_SERVER['DOCUMENT_ROOT']."/application/modules/admin/controllers/";
		$scan = scandir($dir);
		
		$controladores = array();
		$modulos = array();
		
		foreach($scan as $chave => $valor)
		{
			if($valor != "." && $valor != "..")
			{
				$controladores[] = str_replace("Controller.php","", $valor);
			}
		}
		
		$obj = new Tb_sys_modulo();
		$obj->select("CONTROLADOR");
		$obj->find();
		$obj->fetch();
		foreach($obj->allToArray() as $chave => $valor)
		{
			$modulos[] = $valor['CONTROLADOR'];
		}
		
		$dif = array_diff($controladores, $modulos);
		$this->view->dif = $dif; 
		
		
	}

}

?>