<?php

class ThumbsController extends Zend_Controller_Action
{

    public function init()
    {
       	$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
    }

    public function indexAction()
    {
    	      
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
    	
      $pars = $this->_request->getParams(); 
      Zend_Loader_Autoloader::getInstance()->pushAutoloader(new My_Plugins_AutoloaderThumbs());

      $width = $pars["w"];
      $height = $pars["h"];
      $autocrop = (!isset($pars["autocrop"]) ? true : $pars["autocrop"] == "true" ? true : false);
      $file = isset($pars["file"]) ? $pars["file"] : null;
      $type = $pars["type"];
      $dir = null;
      $root = $_SERVER['DOCUMENT_ROOT'].'/www/';
      
      switch ($type)
      {
      	case 1:
      		$dir = "uploads/admin/icones/"; 
      		break;
		case 2:
      		$dir = "uploads/perfis/"; 
      		break;			
		case 3:
      		$dir = "uploads/cms/foto_chamadas/"; 
      		break;
      	case 4:
      		$dir = "uploads/banners/"; 
      		break;
      	case 5:
      		$dir = "uploads/cms/coberturas/"; 
      		break;
		case 6:
      		$dir = "uploads/locais/"; 
      		break;	
		case "url":
			 
						
			 $file = str_replace("___", "%", $file);
			 $file = urldecode($file);
			
			 $options = array('resizeUp' => true, 'jpegQuality' => 80);
			 $thumb = PhpThumbFactory::create($file, $options);
			 $tamanhoReal = $thumb->getCurrentDimensions();
			 $widthReal = $tamanhoReal['width'];
			 $heightReal = $tamanhoReal['height'];
			 
			 if($widthReal > $width || $heightReal > $height)
			 {
			 	$thumb->resize(0, $height);
			 }
			 
   			 $thumb->show();
   			 exit;
			
			break;
      }
        
      

      $options = array('resizeUp' => true, 'jpegQuality' => 100);
      if($file != null && file_exists($root.$dir.$file))
      {
      	 $thumb = PhpThumbFactory::create($dir.$file, $options);
      }else
      {
      	$thumb = PhpThumbFactory::create("uploads/no_photo.png", $options);
      }
      if($autocrop == true)
      {
      	$thumb->adaptiveResize($width, $height);
      }else
      {
      	$thumb->resize($width, $height);
      }
      $thumb->show();
      exit;
    }
    
    
    
	public function thumbslocaisAction()
    {
      $pars = $this->_request->getParams(); 
      Zend_Loader_Autoloader::getInstance()->pushAutoloader(new My_Plugins_AutoloaderThumbs());

      $width = $pars["w"];
      $height = $pars["h"];
      $file = isset($pars["file"]) ? $pars["file"] : null;
      $dir = null;
      $root = $_SERVER['DOCUMENT_ROOT'].'/www/';
      
  	
     $dir = "uploads/locais/"; 
        
      
      
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
      $options = array('resizeUp' => true, 'jpegQuality' => 100);
      if($file != null && file_exists($root.$dir.$file))
      {
      	 $thumb = PhpThumbFactory::create($dir.$file, $options);
      }else
      {
      	$thumb = PhpThumbFactory::create("uploads/no_photo.jpg", $options);
      }
      $thumb->adaptiveResize($width, $height);
      $thumb->show();
      exit;
    }


}

