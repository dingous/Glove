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
 * Classe generada para a tabela "tb_sys_estado"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_sys_estado extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_sys_estado';
    protected $_package   = 'dao';
    
    
    public $ID_ESTADO;
    public $tb_sys_pais;
    public $SGL_ESTADO;
    public $NOME_ESTADO;
    public $tb_sys_cidades = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_ESTADO', 'ID_ESTADO', 'smallint', 6, array('primary' => true, 'notnull' => true));
        $this->_addField('tb_sys_pais', 'ID_PAIS', 'smallint', 6, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'CASCADE', 'linkOn' => 'ID_PAIS', 'class' => 'Tb_sys_pais'));
        $this->_addField('SGL_ESTADO', 'SGL_ESTADO', 'char', 2, array('notnull' => true));
        $this->_addField('NOME_ESTADO', 'NOME_ESTADO', 'varchar', 50, array('notnull' => true));

        
        $this->_addForeignRelation('tb_sys_cidades', self::ONE_TO_MANY, 'Tb_sys_cidade', 'tb_sys_estado', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_sys_estado
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_sys_estado;
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
