<?php

class Admin_BaixaController extends Zend_Controller_Action
{

    
   public function init()
    {
   	
  		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
  	
    }

    
    public function startAction()
    {
    	 $this->_helper->viewRenderer->setNoRender(true);
    	 $url = $this->_request->getParam("url");
    	 
    }
    
	public function geraurlAction()
	{
		$pars = $this->_request->getParams();
 	
	 	
	 	//$this->view->id_franqueado = $id_franqueado = $exp[0];
	 	$this->view->id_cidade = $pars["tb_sys_cidade"];
	 	$apontador_estado = $pars["apontador_estado"];
	 	$apontador_cidade = $pars["apontador_cidade"];
	 	$pagina_atual = $pars["pagina_atual"];
	 	$qtdPagina = $pars['txtQtdPagina'];
	 	
		$retorno = array(
						"url" => "http://www.apontador.com.br/local/ajax_search.html?q=&city=$apontador_cidade&state=$apontador_estado&loc_b=&lat=&lng=&limit=$qtdPagina&page=$pagina_atual&&",
						"id_cidade" => $pars["tb_sys_cidade"],
						"pagina_atual" => $pagina_atual,
						"qtdPagina" => $qtdPagina
					);
		
		
	    	
       	$jsonData = Zend_Json::encode($retorno);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;
	
	}
    
    public function analisaAction()
    {
    	 $this->_helper->viewRenderer->setNoRender(true);
    	 $url = $this->_request->getParam("url");
    	 $pagina_atual = $this->_request->getParam("pagina_atual");
    	 $qtd_pagina = $this->_request->getParam("qtdPagina");
    	 
	     $clientini = new Zend_Http_Client($url);
		 $responseini = $clientini->request();
		 $jsonDataini = Zend_Json::decode($responseini->getBody());
		 
		 $total_registros = str_replace(".", "", $jsonDataini["information"]["total"]);
	 	 $total_paginas =  ceil($total_registros / $qtd_pagina);
		
		 $retorno = array("apont" => $jsonDataini, "total_paginas" => $total_paginas, "pagina_atual" => $pagina_atual,"qtd_pagina" => $qtd_pagina);
	 	 
	 	 
	 	 $jsonData = Zend_Json::encode($retorno);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;	
    	 
    	 
    }
    
	public function getcidadesAction()
	{
		$id = $this->_request->getParam("idCidade");
			
		$obj = new Tb_sys_cidade();
		$obj->where("ID_ESTADO = '".$id."'");
		$obj->find(true);
		$json = $obj->allToJSON(true);
		
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($json)
		->sendResponse();
		exit;	
	}
    
	public function geraslugcidadesAction()
	{
		

		//$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    
	    $sql = "select *, count(*) as total, sgl_estado from tb_sys_cidade inner join tb_sys_estado on tb_sys_estado.ID_ESTADO =  tb_sys_cidade.ID_ESTADO  group by nome_cidade order by  count(*) DESC";
	    
	    $obj = new Tb_sys_cidade();
	    $obj->query($sql);
	    $obj->fetch();
	    
	    $cidades = $obj->allToObject();
	    
		
		
		
	    
	    foreach($cidades as $chave => $valor)
	    {
	    	 			
	    		    		
	    		$novastring = str_replace("'", "", $valor->NOME_CIDADE);	    		
	    		$novastring = ereg_replace("[^a-zA-Z0-9_]", "", strtr($novastring, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC"));
	    		$novastring = strtolower($novastring);
	    		$novastring = trim($novastring.strtolower($valor->sgl_estado));
	    		//echo $novastring."<br>";
	    		$updsql = "update tb_sys_cidade set slug_cidade = '$novastring' where id_cidade = $valor->ID_CIDADE";
	    		$objupdate = new Tb_sys_cidade();
	   			$objupdate->query($updsql);	
	   			
	    	
	   		
	    }
	    
	}
	
	public function getEstados()
    {
    	$estados = new Tb_sys_estado(); $estados->orderby("SGL_ESTADO")->find();
    	return $estados;
    }
	
	public function indexAction()
	{
		$obj = new Tb_sys_franqueado();
		/*$sql = "select franquia.ID_FRANQUEADO, cidade.ID_CIDADE, count(*) as total, CONCAT(franquia.NOME_FRANQUEADO ,' (' , franquia.SLUG_FRANQUIA, ')') as franquia, cidade.NOME_CIDADE as cidade  from tb_gcool_anuncio as locais	
left join tb_sys_franqueado as franquia on franquia.ID_FRANQUEADO = locais.ID_FRANQUEADO
left join tb_sys_cidade as cidade on cidade.ID_CIDADE = locais.ID_CIDADE
group by franquia.ID_FRANQUEADO
order by cidade";*/
		$sql = "select * from tb_sys_franqueado";
		$obj->query($sql);	
		$this->view->franquias = $obj->allToObject();
		$this->view->estados = $this->getEstados();
		
	}
	
	
	
	
	
	public function getInnerHTML($Node)
	{
	     $Body = $Node->ownerDocument->documentElement->firstChild->firstChild;
	     $Document = new DOMDocument();     
	     $Document->appendChild($Document->importNode($Body,true));
	     return $Document->saveHTML();
	}
	
    public function baixarAction()
    {
	
      
      //ob_start();
      
    	try {
    		
    	
      
    	
     $this->_helper->layout()->disableLayout();
     
     $this->_helper->viewRenderer->setNoRender(true);
     
     $pars = $this->_request->getParams();
     $url = $pars["apontador_url"];
     $clientini = new Zend_Http_Client($url);
	 $responseini = $clientini->request();
	 $jsonDataini = Zend_Json::decode($responseini->getBody());
      
     	 
	 $total_registros = str_replace(".", "", $jsonDataini["information"]["total"]);
	 $qtd_pagina = 50;
	 $total_paginas =  ceil($total_registros / $qtd_pagina);
	 //$id_franquia = $pars["franquia"];
	 $id_franquia = NULL;
	 $id_cidade = $pars["cidade"];
	 $id_usuario = 1;
	 	 
	$contador = 0;
	//for($i = 0; $i <= $total_paginas; $i++)
	for($i = 0; $i <= 0; $i++)
	{
	 
	 if($i == 0)
	 {
	 	$url = $url;
	 	
	 }else
	 {
	 	$back = ($i-1);
	 	$url = str_replace("&page=$back", "&page=$i", $url);
	 }

	 echo "<br><font color=red>".$url."</font><br>";
	 
	 $client = new Zend_Http_Client($url);
	 
	 $response = $client->request();
	 $jsonData = Zend_Json::decode($response->getBody());
	 $html = utf8_decode($jsonData["organic"]);
	 
	

	 $dom = new Zend_Dom_Query($html);
	 $dom->setEncoding("utf-8");
	 $results = $dom->query('.result .hreview-aggregate .vcard');
	 
	
	
	 
	 if($results->count() > 0)
	 {	 
		 foreach($results as $valor)
		 {
		 
					 	
			if($valor->getAttribute("class") == "result hreview-aggregate vcard")
			{

			$titulo = ltrim(rtrim($valor->childNodes->item(1)->getElementsByTagName("h2")->item(0)->nodeValue));
		 	$categoria = rtrim($valor->childNodes->item(1)->getElementsByTagName("p")->item(0)->nodeValue);
		 	$node_endereco = $valor->childNodes->item(1)->getElementsByTagName("p")->item(1)->getElementsByTagName("span")->item(0);
		 	$node_endereco->removeChild($node_endereco->getElementsByTagName("a")->item(0));
		 	$logradouro = rtrim($node_endereco->nodeValue);
		 	echo $logradouro;
		 	$exp_ender = explode(",", $logradouro);
		 	$bairro = rtrim(str_replace(",", "", $valor->childNodes->item(1)->getElementsByTagName("p")->item(1)->getElementsByTagName("span")->item(1)->nodeValue));
		 	
		
	
			
			$telNode = $valor->childNodes->item(1);
		 	$telefone = "";
		 	if($telNode->hasChildNodes())
		 	{
		 		if($telNode->getElementsByTagName("p")->length > 2)
		 		{
		 				$telefone = rtrim($telNode->getElementsByTagName("p")->item(2)->getElementsByTagName("span")->item(2)->nodeValue);
		 		}
		 	
		 	}
			
		 	
		 	
		 	echo "<br />Título: " . $titulo. "<br />";
		 	echo "Categoria: " . $categoria. "<br />";
		 	echo "Logradouro: " . $logradouro. "<br />";
		 	echo "Rua: " . $exp_ender[0]. "<br />";
		 	echo "Número: " . $exp_ender[1]. "<br />";
		 	echo "Bairro: " . $bairro. "<br />";
		 	echo "Telefone: " . $telefone. "<br />";
		 	echo "<br /><br /><br />";
		 	
		 	
		 	$criterio = addslashes($titulo);
		 	$criterio = htmlspecialchars($criterio, ENT_QUOTES);
			try {

				
		    
			$objverif = new Tb_gcool_anuncio();	
		  	$sql = "SELECT * FROM tb_gcool_local WHERE ID_CIDADE  = $id_cidade AND TITULO LIKE '%".($criterio)."%'";;

		  	
		  	$objverif->query($sql);
		  	$objverif->fetch();
		  	$achou = $objverif->toObject();
		  			  			    
			} catch (Exception $e) {
				echo $objverif->_getSQL();
				echo $e->getMessage();
			}
		
	    	
					
		    if($achou->ID_ANUNCIO == "")
		    {
		    	$id_categoria = $this->addCategoriaLocal($categoria);	
		 		$id_bairro = $this->addBairro($bairro, $id_cidade);
		 		$id_endereco = $this->persisteEndereco($exp_ender[0], trim($exp_ender[1]), $id_bairro);
		 		$id_contato = $this->persisteContato($telefone);
		    	
		    	
		    	
			 	$anuncio = new Tb_gcool_local();
			 	$anuncio->tb_sys_usuarios = $id_usuario;
			 	$anuncio->tb_sys_franqueado = $id_franquia;
			 	$anuncio->tb_gcool_categoria = $id_categoria;
			 	$anuncio->tb_sys_cidade = $id_cidade;
			 	$anuncio->tb_sys_endereco = $id_endereco;
			 	$anuncio->tb_sys_contato = $id_contato;
			 	$anuncio->TITULO = $titulo;
			 	$anuncio->insert();
			 	$anuncio->destroy();
			 	
		    }else
		    {
		    	
		    }

		 	$contador++;
		 	
		 
		 
		 }
		 }
		 
	
	 }else
	 {
	 	echo "Nenhum resultado encontrado.<br />";
	 }

	  
	 
	}
	
	
	
    	} catch (Exception $e) {
    		echo $e->getMessage();
    	}
	
    }
    
    
    
    
    
    
    
    
    
	public function baixarnovoAction()
    {
	      
    	try {
    		

			     $this->_helper->layout()->disableLayout();
			     $this->_helper->viewRenderer->setNoRender(true);
			     $pars = $this->_request->getParams();
    
				 $titulo = $pars['titulo']; $titulo = ltrim($titulo); $titulo = rtrim($titulo);
				 $categoria = $pars['categoria']; $categoria = rtrim($categoria); $categoria = ltrim($categoria);
				 $ender = $pars['ender']; $ender = ltrim($ender); $ender = rtrim($ender);
				 $bairro = $pars['bairro'];
				 $telefone = $pars['tel']; $telefone = rtrim($telefone); $telefone = ltrim($telefone);
				 $id_usuario = 1;
				 $id_cidade = $pars['cidade'];
				 $exp_ender = explode(",", $ender);
				 $indice_linha = $pars['indice_linha'];
				 
					
			    $criterio = addslashes($titulo);
		 		$criterio = htmlspecialchars($criterio, ENT_QUOTES);
				$objverif = new Tb_gcool_anuncio();	
			  	$sql = "SELECT * FROM tb_gcool_local WHERE ID_CIDADE  = $id_cidade AND TITULO LIKE '%".($criterio)."%'";
	
			  
			  	
			  	$objverif->query($sql);
			  	$objverif->fetch();
			  	$achou = $objverif->toObject();
			  			  			    
			
			
		    	
						
			    if($achou->ID_ANUNCIO == "")
			    {
			    	$id_categoria = $this->addCategoriaLocal($categoria);	
			 		$id_bairro = $this->addBairro($bairro, $id_cidade);
			 		$id_endereco = $this->persisteEndereco($exp_ender[0], trim($exp_ender[1]), $id_bairro);
			 		$id_contato = $this->persisteContato($telefone);
			    	
			    	
			    	
				 	$anuncio = new Tb_gcool_local();
				 	$anuncio->tb_sys_usuarios = $id_usuario;
				 	$anuncio->tb_gcool_categoria = $id_categoria;
				 	$anuncio->tb_sys_cidade = $id_cidade;
				 	$anuncio->tb_sys_endereco = $id_endereco;
				 	$anuncio->tb_sys_contato = $id_contato;
				 	$anuncio->TITULO = $titulo;
				 	$anuncio->insert();
				 	$anuncio->destroy();
				 	
				 	$retorno = array("msg" => 'Inserido com sucesso', 'indice_linha' => $indice_linha);
				 	
			    }else
			    {
			    	$retorno = array("msg" => 'ja existia na base de dados', 'indice_linha' => $indice_linha);
			    }


	
    	} catch (Exception $e) {
    		 $retorno = array("msg" =>  $e->getMessage(), 'indice_linha' => $indice_linha);
    	}
    	
    	
    	
				
				$jsonData = Zend_Json::encode($retorno);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;	
	
    }
    
    
    
    
    
    
    
    

    function localporcategoriaAction()
    {
    	$this->_helper->viewRenderer->setNoRender();
    	//http://api.apontador.com.br/v1/categories/67/subcategories
    }
    
 	function baixacategoriasAction()
    {
    	$this->_helper->viewRenderer->setNoRender();
  	 
    	$key = "qI_bPB7NW1ty7i2tZwi_1qwR9LP2IPwYIwlW_223_nk~";
		$secret = "sDpWJBAwpF3CLhzRtkMGS0xpWRE~";
		$callbackurl = "http://localhost/apontador-api-libs/php/exemplo/callback.php";
		
		
		$objdel = new Tb_gcool_categoria();
		$objdel->query("delete FROM `tb_gcool_categoria` WHERE id not in (1,2);");
		$objdel->query("ALTER TABLE  `tb_gcool_categoria` AUTO_INCREMENT =3;");
		
		
    	$apontadorApi = new My_Plugins_ApontadorApi($key, $secret, $callbackurl);
    	$categories = $apontadorApi->getCategories("");
    	
    	foreach($categories as $chave => $valor)
    	{
    		//$id = $this->addCategoria($valor['name'],$valor['id'], 2);
    		//$subcategories = $apontadorApi->getSubCategories($valor['id'], '');
    		
    		foreach($subcategories as $chave2 => $valor2)
	    	{
	    		//$this->addCategoria($valor2['name'], $valor2['id'], $id, 2);
	    		
	    	}
    	}
    
    }
    

    public  function addCategoriaLocal($categoria)
    {
    	$categoria = ltrim($categoria);
    	$categoria = rtrim($categoria);
    	$categoria = strtolower($categoria);
    	$categoria = utf8_decode($categoria);
    	$obj = new Tb_gcool_categoria();
    	$obj->where("title = '$categoria'");
    	if($obj->find() > 0)
    	{
    		$obj->fetch();
    		return $obj->id;
    	}else{
    		
    		
    		throw new Exception("Categoria nao econtrada", 1, null); 
    	}
    	
    	
    
    }
    
public function addCategoria($categoria, $id , $parent_id = 2, $level = 2)
{
	$max = new Tb_gcool_categoria();
	$max->orderby("id DESC");
	$max->limit(1);
	$max->find();
	$max->fetch();
	
	
	if($parent_id == 2)
	{
		$nextlevel = 2;	
		$nextleft = $max->left + 2;
		$nextright = $nextleft + 1;
		
		
	}else
	{
		$nextlevel = 3;
		$nextleft = $max->left + 2;
		$nextright = $nextleft + 1;
		
		
	}
	
	
	
	$nextposition = $max->position + 1;
	
	
		
		$sql = "INSERT INTO `tb_gcool_categoria` (`id_apont`, `SLUG`, `parent_id`, `position`, `left`, `right`, `level`, `title`, `type`, `icone_pequeno`, `icone_grande`) VALUES
($id, '".My_Sites_GuiaCool::urldinamica(ltrim($categoria))."', $parent_id, $nextposition, $nextleft, $nextright, $nextlevel, '".utf8_decode(mysql_real_escape_string(ltrim($categoria)))."', 'folder', NULL, NULL);";
		
		$obj = new Tb_gcool_categoria();
		$obj->query($sql);
		
		$max2 = new Tb_gcool_categoria();
		$max2->orderby("id DESC");
		$max2->limit(1);
		$max2->find();
		$max2->fetch();
		
		echo $max2->id;
		
		
		$increment = $max2->right + 1;		
		$sqlUpdate = "update `tb_gcool_categoria` as x set x.right = '$increment' where id = $parent_id";
		$objUpdate = new Tb_gcool_categoria();
		$objUpdate->query($sqlUpdate);
		
		
		
		return $max2->id;
		
}


public function addBairro($bairro, $cidade)
{
	$bairro = rtrim($bairro); $bairro = ltrim($bairro);
	$bairro = str_replace("'", "", $bairro);
    //$bairro = htmlspecialchars($bairro, ENT_QUOTES);
		
	$verif = new Tb_sys_bairro();
	$verif->where("NOME_BAIRRO = '".$bairro."'");
	$verif->find();
	$verif->fetch();
	if($verif->count() == 0)
	{
		$ins = new Tb_sys_bairro();
		$ins->NOME_BAIRRO = $bairro;
		$ins->tb_sys_cidade = $cidade;
		$ins->insert();
		
		return $ins->ID_BAIRRO;
		
	}else
	{
		return $verif->ID_BAIRRO;
	}
	
}   


public function persisteEndereco($logradouro, $nr, $bairro)
{
	$obj = new Tb_sys_endereco();
	$obj->LOGRADOURO = $logradouro;
	$obj->NUMERO = $nr;
	$obj->tb_sys_bairro = $bairro;
	$obj->insert();
	return $obj->ID_ENDERECO;
	
}


public function persisteContato($telefone)
{
	$obj = new Tb_sys_contato();
	$obj->TELEFONE1 = $telefone;
	$obj->insert();
	return $obj->ID_CONTATO;
	
}

}




