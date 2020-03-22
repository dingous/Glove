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
 * Classe generada para a tabela "tb_sys_admin_menu"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_sys_admin_menu extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_sys_admin_menu';
    protected $_package   = 'dao';
    
    
    public $id;
    public $URLACAO;
    public $parent_id;
    public $position;
    public $left;
    public $right;
    public $level;
    public $title;
    public $type;
    public $url;
    public $ID_MODULO;
    public $ACAO;
    public $CONTROLADOR;
    public $ICONE;
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('id', 'id', 'int', 10, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('URLACAO', 'URLACAO', 'varchar', 255, array());
        $this->_addField('parent_id', 'parent_id', 'int', 11, array());
        $this->_addField('position', 'position', 'int', 10, array('notnull' => true));
        $this->_addField('left', 'left', 'int', 10, array('notnull' => true));
        $this->_addField('right', 'right', 'int', 10, array('notnull' => true));
        $this->_addField('level', 'level', 'int', 10, array('notnull' => true));
        $this->_addField('title', 'title', 'text', 65535, array());
        $this->_addField('type', 'type', 'varchar', 255, array());
        $this->_addField('url', 'url', 'varchar', 200, array('default' => 'index'));
        $this->_addField('ID_MODULO', 'ID_MODULO', 'int', 11, array());
        $this->_addField('ACAO', 'ACAO', 'varchar', 60, array());
        $this->_addField('CONTROLADOR', 'CONTROLADOR', 'varchar', 60, array());
        $this->_addField('ICONE', 'ICONE', 'varchar', 100, array());

        
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_sys_admin_menu
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_sys_admin_menu;
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
