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
 * Classe generada para a tabela "tb_sys_cidade"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_sys_cidade extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_sys_cidade';
    protected $_package   = 'dao';
    
    
    public $ID_CIDADE;
    public $tb_sys_estado;
    public $NOME_CIDADE;
    public $SLUG_CIDADE;
    public $tb_sys_bairros = array();
    public $tb_sys_franqueados = array();
    public $tb_sys_regioes = array();
    public $tb_sys_usuarios = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_CIDADE', 'ID_CIDADE', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_sys_estado', 'ID_ESTADO', 'smallint', 6, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'CASCADE', 'linkOn' => 'ID_ESTADO', 'class' => 'Tb_sys_estado'));
        $this->_addField('NOME_CIDADE', 'NOME_CIDADE', 'varchar', 50, array('notnull' => true));
        $this->_addField('SLUG_CIDADE', 'SLUG_CIDADE', 'varchar', 60, array());

        
        $this->_addForeignRelation('tb_sys_bairros', self::ONE_TO_MANY, 'Tb_sys_bairro', 'tb_sys_cidade', null, null, null);
        $this->_addForeignRelation('tb_sys_franqueados', self::ONE_TO_MANY, 'Tb_sys_franqueado', 'tb_sys_cidade', null, null, null);
        $this->_addForeignRelation('tb_sys_regioes', self::ONE_TO_MANY, 'Tb_sys_regiao', 'tb_sys_cidade', null, null, null);
        $this->_addForeignRelation('tb_sys_usuarios', self::ONE_TO_MANY, 'Tb_sys_usuario', 'tb_sys_cidade', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_sys_cidade
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_sys_cidade;
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
