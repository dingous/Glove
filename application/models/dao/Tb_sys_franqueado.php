<?php
#### START AUTOCODE
################################################################################
#  Lumine - Database Mapping for PHP
#  Copyright (C) 2005  Hugo Ferreira da Silva
#  
#  This program is free software: you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
#  
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#  
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>
################################################################################
/**
 * Classe generada para a tabela "tb_sys_franqueado"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_sys_franqueado extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_sys_franqueado';
    protected $_package   = 'dao';
    
    
    public $ID_FRANQUEADO;
    public $tb_sys_cidade;
    public $TIPOPESSOA;
    public $NOME_FRANQUEADO;
    public $NOME_FANTASIA;
    public $RAZAO_SOCIAL;
    public $CPF_CNPJ;
    public $ATIVO;
    public $DT_CADASTRO;
    public $DT_MODIFICACAO;
    public $URL_DOMINIO;
    public $tb_sys_plano_franqueados = array();
    public $tb_sys_usuarios = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_FRANQUEADO', 'ID_FRANQUEADO', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_sys_cidade', 'ID_CIDADE', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_CIDADE', 'class' => 'Tb_sys_cidade'));
        $this->_addField('TIPOPESSOA', 'TIPOPESSOA', 'char', 1, array());
        $this->_addField('NOME_FRANQUEADO', 'NOME_FRANQUEADO', 'varchar', 300, array());
        $this->_addField('NOME_FANTASIA', 'NOME_FANTASIA', 'varchar', 300, array());
        $this->_addField('RAZAO_SOCIAL', 'RAZAO_SOCIAL', 'varchar', 45, array());
        $this->_addField('CPF_CNPJ', 'CPF_CNPJ', 'varchar', 12, array());
        $this->_addField('ATIVO', 'ATIVO', 'int', 1, array());
        $this->_addField('DT_CADASTRO', 'DT_CADASTRO', 'datetime', null, array());
        $this->_addField('DT_MODIFICACAO', 'DT_MODIFICACAO', 'datetime', null, array());
        $this->_addField('URL_DOMINIO', 'URL_DOMINIO', 'varchar', 150, array());

        
        $this->_addForeignRelation('tb_sys_plano_franqueados', self::ONE_TO_MANY, 'Tb_sys_plano_franqueado', 'tb_sys_franqueado', null, null, null);
        $this->_addForeignRelation('tb_sys_usuarios', self::ONE_TO_MANY, 'Tb_sys_usuario', 'tb_sys_franqueado', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_sys_franqueado
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_sys_franqueado;
        $obj->get($pk, $pkValue);
        return $obj;
    }

	/**
	 * chama o destrutor pai
	 *
	 */
	function __destruct()
	{
		parent::__destruct();
	}
	
    #------------------------------------------------------#
    # Coloque todos os metodos personalizados abaixo de    #
    # END AUTOCODE                                         #
    #------------------------------------------------------#
    #### END AUTOCODE


}
