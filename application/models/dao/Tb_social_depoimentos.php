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
 * Classe generada para a tabela "tb_social_depoimentos"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_social_depoimentos extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_social_depoimentos';
    protected $_package   = 'dao';
    
    
    public $ID_DEPOIMENTO;
    public $tb_glove_perfil;
    public $tb_glove_perfil;
    public $MENSAGEM;
    public $FL_VISUALIZADO;
    public $DT_CADASTRO;
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_DEPOIMENTO', 'ID_DEPOIMENTO', 'int', 50, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_glove_perfil', 'ID_USUARIO_REMETENTE', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_USUARIO', 'class' => 'Tb_glove_perfil'));
        $this->_addField('tb_glove_perfil', 'ID_USUARIO_DESTINATARIO', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_USUARIO', 'class' => 'Tb_glove_perfil'));
        $this->_addField('MENSAGEM', 'MENSAGEM', 'text', 65535, array());
        $this->_addField('FL_VISUALIZADO', 'FL_VISUALIZADO', 'int', 1, array('default' => '0'));
        $this->_addField('DT_CADASTRO', 'DT_CADASTRO', 'datetime', null, array());

        
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_social_depoimentos
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_social_depoimentos;
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
