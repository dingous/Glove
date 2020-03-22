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
 * Classe generada para a tabela "tb_social_recados"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_social_recados extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_social_recados';
    protected $_package   = 'dao';
    
    
    public $ID_RECADO;
    public $tb_glove_perfil;
    public $ID_USUARIO_DESTINATARIO;
    public $tb_social_tipo_publicacao;
    public $tb_social_albumfotos;
    public $tb_social_foto;
    public $MENSAGEM;
    public $COMPARTILHAMENTOS;
    public $CURTIDAS;
    public $FL_VISUALIZADO;
    public $DT_CADASTRO;
    public $FL_PRIVADA;
    public $tb_glove_perfil;
    public $FL_COMPARTILHAMENTO;
    public $MENSAGEM_COMPARTILHAMENTO;
    public $ID_RECADO_COMPARTILHADO;
    public $DT_COMPARTILHAMENTO;
    public $ID_RECADO_PRIMEIRA_ORIGEM;
    public $tb_social_comentarios = array();
    public $tb_socialrecado_curtir = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_RECADO', 'ID_RECADO', 'int', 50, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_glove_perfil', 'ID_USUARIO_REMETENTE', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_USUARIO', 'class' => 'Tb_glove_perfil'));
        $this->_addField('ID_USUARIO_DESTINATARIO', 'ID_USUARIO_DESTINATARIO', 'int', 11, array('notnull' => true));
        $this->_addField('tb_social_tipo_publicacao', 'ID_TIPOPUBLICACAO', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_TIPOPUBLICACAO', 'class' => 'Tb_social_tipo_publicacao'));
        $this->_addField('tb_social_albumfotos', 'ID_ALBUM', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'CASCADE', 'linkOn' => 'ID_ALBUM', 'class' => 'Tb_social_albumfotos'));
        $this->_addField('tb_social_foto', 'ID_FOTO', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'CASCADE', 'linkOn' => 'ID_FOTO', 'class' => 'Tb_social_foto'));
        $this->_addField('MENSAGEM', 'MENSAGEM', 'text', 65535, array());
        $this->_addField('COMPARTILHAMENTOS', 'COMPARTILHAMENTOS', 'int', 11, array('default' => '0'));
        $this->_addField('CURTIDAS', 'CURTIDAS', 'int', 11, array('default' => '0'));
        $this->_addField('FL_VISUALIZADO', 'FL_VISUALIZADO', 'int', 1, array('default' => '0'));
        $this->_addField('DT_CADASTRO', 'DT_CADASTRO', 'datetime', null, array());
        $this->_addField('FL_PRIVADA', 'FL_PRIVADA', 'int', 11, array('default' => '0'));
        $this->_addField('tb_glove_perfil', 'ID_USUARIO_COMPARTILHOU', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_USUARIO', 'class' => 'Tb_glove_perfil'));
        $this->_addField('FL_COMPARTILHAMENTO', 'FL_COMPARTILHAMENTO', 'int', 1, array('default' => '0'));
        $this->_addField('MENSAGEM_COMPARTILHAMENTO', 'MENSAGEM_COMPARTILHAMENTO', 'varchar', 300, array());
        $this->_addField('ID_RECADO_COMPARTILHADO', 'ID_RECADO_COMPARTILHADO', 'int', 50, array());
        $this->_addField('DT_COMPARTILHAMENTO', 'DT_COMPARTILHAMENTO', 'datetime', null, array());
        $this->_addField('ID_RECADO_PRIMEIRA_ORIGEM', 'ID_RECADO_PRIMEIRA_ORIGEM', 'int', 50, array());

        
        $this->_addForeignRelation('tb_social_comentarios', self::ONE_TO_MANY, 'Tb_social_comentarios', 'tb_social_recados', null, null, null);
        $this->_addForeignRelation('tb_socialrecado_curtir', self::ONE_TO_MANY, 'Tb_socialrecado_curtir', 'tb_social_recados', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_social_recados
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_social_recados;
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
