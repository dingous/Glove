<?php
/**
 * **********************************************************************
 * Classe de contexto para arquiteturas MVC
 * 
 * Para utilizar com frameworks, como por exemplo, CodeIgniter.
 * Inicializa todas as configuracoes necessarias para utilizacao de
 * Lumine.
 * 
 * Esta classe deve ser incluida
 * 
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br
 * @package Lumine
 * 
 * **********************************************************************
 */ 
// inclui os arquivos necessarios
require_once 'lumine/Lumine.php';

class Lumine_ApplicationContext extends Lumine_EventListener {
	
	/**
	 * Construtor
	 * 
	 * @author Hugo Ferreira da Silva
	 * @link http://www.hufersil.com.br
	 * @return Lumine_ApplicationContext
	 */
	public function __construct(){
		
				
		//include 'lumine-conf.php';
		if(strpos($_SERVER['HTTP_HOST'], "com") == true )
		{
			
				
		$lumineConfig = array(
		    'dialect' => 'MySQL', 
		    'database' => 'glove', 
		    'user' => 'glovebra_glove2', 
		    'password' => 'inventoadmin', 
		    'port' => '3306', 
		    'host' => 'localhost', 
		    'class_path' => $_SERVER['DOCUMENT_ROOT'].'/application/models', 
		    'package' => 'dao', 
		    'addons_path' => '', 
		    'acao' => 'gerar', 
		
			
		    'options' => array(
		        'configID' => '', 
		        'tipo_geracao' => '1', 
		        'remove_prefix' => '', 
		        'remove_count_chars_start' => '0', 
		        'remove_count_chars_end' => '0', 
		        'format_classname' => '', 
		        'schema_name' => '', 
		        'many_to_many_style' => '%s_has_%s', 
		        'plural' => '', 
		        'create_controls' => 'White', 
		        'class_sufix' => '', 
		        'keep_foreign_column_name' => '0', 
		        'camel_case' => '0', 
		        'usar_dicionario' => '1', 
		        'create_paths' => '1', 
		        'dto_format' => '%sDTO', 
		        'dto_package' => 'entidades', 
		        'create_models' => '1', 
		        'model_path' => $_SERVER['DOCUMENT_ROOT'].'system/application/models', 
		        'model_format' => '%sModel', 
		        'model_context' => '1', 
		        'model_context_path' => $_SERVER['DOCUMENT_ROOT'].'system/application/libraries', 
		        'overwrite' => '1', 
		        'create_dtos' => '', 
		        'generateAccessors' => '0', 
		        'create_entities_for_many_to_many' => '1', 
		        'generate_files' => '1', 
		        'generate_zip' => '0',
				'escape' => '1',
				'xml_validation_path' => $_SERVER['DOCUMENT_ROOT'].'/application/models/validators'
		    )
		);
		
		
		}else
		{
			
			$lumineConfig = array(
		    'dialect' => 'MySQL', 
		    'database' => 'glove', 
		    'user' => 'root', 
		    'password' => '', 
		    'port' => '3306', 
		    'host' => 'localhost', 
		    'class_path' => 'D:\wamp\www\glove\application\models', 
		    'package' => 'dao', 
		    'addons_path' => '', 
		    'acao' => 'gerar', 
		
			
		    'options' => array(
		        'configID' => '', 
		        'tipo_geracao' => '1', 
		        'remove_prefix' => '', 
		        'remove_count_chars_start' => '0', 
		        'remove_count_chars_end' => '0', 
		        'format_classname' => '', 
		        'schema_name' => '', 
		        'many_to_many_style' => '%s_has_%s', 
		        'plural' => '', 
		        'create_controls' => 'White', 
		        'class_sufix' => '', 
		        'keep_foreign_column_name' => '0', 
		        'camel_case' => '0', 
		        'usar_dicionario' => '1', 
		        'create_paths' => '1', 
		        'dto_format' => '%sDTO', 
		        'dto_package' => 'entidades', 
		        'create_models' => '1', 
		        'model_path' => 'system/application/models', 
		        'model_format' => '%sModel', 
		        'model_context' => '1', 
		        'model_context_path' => 'system/application/libraries', 
		        'overwrite' => '1', 
		        'create_dtos' => '', 
		        'generateAccessors' => '0', 
		        'create_entities_for_many_to_many' => '1', 
		        'generate_files' => '1', 
		        'generate_zip' => '0',
				'escape' => '1',
				'xml_validation_path' => 'D:\wamp\www\glove\application\models\validators'
		    )
		);
			
		}
		
		$cfg = new Lumine_Configuration($lumineConfig);
		register_shutdown_function(array($cfg->getConnection(),'close'));
		spl_autoload_register(array('Lumine','import'));
		spl_autoload_register(array('Lumine','loadModel'));
		
	}
	
}