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
 * Classe generada para a tabela "tb_sys_modulo"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_sys_modulo extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_sys_modulo';
    protected $_package   = 'dao';
    
    
    public $ID_MODULO;
    public $tb_sys_aplicacao;
    public $DESCRICAO_MODULO;
    public $CONTROLADOR;
    public $DETALHES;
    public $DT_CADASTRO;
    public $tb_sys_modulo_has_tb_sys_aliasaction = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_MODULO', 'ID_MODULO', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_sys_aplicacao', 'ID_APLICACAO', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE', 'linkOn' => 'ID_APLICACAO', 'class' => 'Tb_sys_aplicacao'));
        $this->_addField('DESCRICAO_MODULO', 'DESCRICAO_MODULO', 'varchar', 300, array('notnull' => true));
        $this->_addField('CONTROLADOR', 'CONTROLADOR', 'varchar', 150, array());
        $this->_addField('DETALHES', 'DETALHES', 'text', 65535, array());
        $this->_addField('DT_CADASTRO', 'DT_CADASTRO', 'datetime', null, array());

        
        $this->_addForeignRelation('tb_sys_modulo_has_tb_sys_aliasaction', self::ONE_TO_MANY, 'Tb_sys_modulo_has_tb_sys_aliasaction', 'tb_sys_modulo', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_sys_modulo
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_sys_modulo;
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
