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
 * Classe generada para a tabela "tb_socialauditoria"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_socialauditoria extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_socialauditoria';
    protected $_package   = 'dao';
    
    
    public $ID_AUDITORIA;
    public $tb_socialauditoria_tipo;
    public $tb_glove_perfil;
    public $MENSAGEM;
    public $ID_POST;
    public $DT_CADASTRO;
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_AUDITORIA', 'ID_AUDITORIA', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_socialauditoria_tipo', 'ID_TIPOAUDITORIA', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_TIPOAUDITORIA', 'class' => 'Tb_socialauditoria_tipo'));
        $this->_addField('tb_glove_perfil', 'ID_USUARIO', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_USUARIO', 'class' => 'Tb_glove_perfil'));
        $this->_addField('MENSAGEM', 'MENSAGEM', 'text', 65535, array());
        $this->_addField('ID_POST', 'ID_POST', 'int', 11, array());
        $this->_addField('DT_CADASTRO', 'DT_CADASTRO', 'datetime', null, array());

        
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_socialauditoria
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_socialauditoria;
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
