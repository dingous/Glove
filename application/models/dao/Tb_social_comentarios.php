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
 * Classe generada para a tabela "tb_social_comentarios"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_social_comentarios extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_social_comentarios';
    protected $_package   = 'dao';
    
    
    public $ID_COMENTARIO;
    public $tb_social_recados;
    public $tb_glove_perfil;
    public $CURTIDAS;
    public $COMENTARIO;
    public $DT_CADASTRO;
    public $DT_ULTIMAMODIFICACAO;
    public $tb_socialcomentario_curtir = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_COMENTARIO', 'ID_COMENTARIO', 'int', 50, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_social_recados', 'ID_RECADO', 'int', 50, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'CASCADE', 'linkOn' => 'ID_RECADO', 'class' => 'Tb_social_recados'));
        $this->_addField('tb_glove_perfil', 'ID_USUARIO', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_USUARIO', 'class' => 'Tb_glove_perfil'));
        $this->_addField('CURTIDAS', 'CURTIDAS', 'int', 11, array('default' => '0'));
        $this->_addField('COMENTARIO', 'COMENTARIO', 'text', 65535, array('notnull' => true));
        $this->_addField('DT_CADASTRO', 'DT_CADASTRO', 'datetime', null, array('notnull' => true));
        $this->_addField('DT_ULTIMAMODIFICACAO', 'DT_ULTIMAMODIFICACAO', 'datetime', null, array('notnull' => true));

        
        $this->_addForeignRelation('tb_socialcomentario_curtir', self::ONE_TO_MANY, 'Tb_socialcomentario_curtir', 'tb_social_comentarios', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_social_comentarios
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_social_comentarios;
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
