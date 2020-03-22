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
 * Classe generada para a tabela "tb_sys_endereco"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_sys_endereco extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_sys_endereco';
    protected $_package   = 'dao';
    
    
    public $ID_ENDERECO;
    public $tb_sys_bairro;
    public $LOGRADOURO;
    public $NUMERO;
    public $CEP;
    public $COMPLEMENTO;
    public $tb_sys_usuarios = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_ENDERECO', 'ID_ENDERECO', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_sys_bairro', 'ID_BAIRRO', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_BAIRRO', 'class' => 'Tb_sys_bairro'));
        $this->_addField('LOGRADOURO', 'LOGRADOURO', 'varchar', 200, array());
        $this->_addField('NUMERO', 'NUMERO', 'varchar', 45, array());
        $this->_addField('CEP', 'CEP', 'varchar', 13, array());
        $this->_addField('COMPLEMENTO', 'COMPLEMENTO', 'varchar', 120, array());

        
        $this->_addForeignRelation('tb_sys_usuarios', self::ONE_TO_MANY, 'Tb_sys_usuario', 'tb_sys_endereco', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_sys_endereco
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_sys_endereco;
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
