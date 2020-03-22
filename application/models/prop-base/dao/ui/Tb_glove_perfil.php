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
 * Classe generada para a tabela "tb_glove_perfil"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_glove_perfil extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_glove_perfil';
    protected $_package   = 'dao';
    
    
    public $tb_sys_usuario;
    public $tb_glove_peso;
    public $tb_glove_altura;
    public $tb_glove_idade;
    public $tb_glove_fumante;
    public $tb_glove_situacao;
    public $tb_glove_tipo;
    public $tb_glove_bebidas;
    public $tb_glove_disponibilidade;
    public $tb_glove_insteresse;
    public $ID_PRIVACIDADEBUSCA;
    public $ID_PRIVACIDADEMENSAGENS;
    public $SOBREMIM;
    public $tb_social_foto;
    public $CSSCLASSPATTERN;
    public $CSSCLASSCOLOR;
    public $FL_HABILITASOMCHAT;
    public $tb_social_albumfotos = array();
    public $tb_social_amigos = array();
    public $tb_social_amigos_1 = array();
    public $tb_social_comentarios = array();
    public $tb_social_depoimentos = array();
    public $tb_social_depoimentos_1 = array();
    public $tb_social_recados = array();
    public $tb_social_recados_1 = array();
    public $tb_social_videos = array();
    public $tb_socialauditorias = array();
    public $tb_socialcomentario_curtir = array();
    public $tb_socialrecado_curtir = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('tb_sys_usuario', 'ID_USUARIO', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'CASCADE', 'linkOn' => 'ID_USUARIO', 'class' => 'Tb_sys_usuario'));
        $this->_addField('tb_glove_peso', 'ID_PESO', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_PESO', 'class' => 'Tb_glove_peso'));
        $this->_addField('tb_glove_altura', 'ID_ALTURA', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_ALTURA', 'class' => 'Tb_glove_altura'));
        $this->_addField('tb_glove_idade', 'ID_IDADE', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_IDADE', 'class' => 'Tb_glove_idade'));
        $this->_addField('tb_glove_fumante', 'ID_FUMANTE', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_FUMANTE', 'class' => 'Tb_glove_fumante'));
        $this->_addField('tb_glove_situacao', 'ID_SITUACAO', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_SITUACAO', 'class' => 'Tb_glove_situacao'));
        $this->_addField('tb_glove_tipo', 'ID_TIPO', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_TIPO', 'class' => 'Tb_glove_tipo'));
        $this->_addField('tb_glove_bebidas', 'ID_BEBIDA', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_BEBIDA', 'class' => 'Tb_glove_bebidas'));
        $this->_addField('tb_glove_disponibilidade', 'ID_DISPONIBILIDADE', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_DISPONIBILIDADE', 'class' => 'Tb_glove_disponibilidade'));
        $this->_addField('tb_glove_insteresse', 'ID_INSTERESSE', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_INSTERESSE', 'class' => 'Tb_glove_insteresse'));
        $this->_addField('ID_PRIVACIDADEBUSCA', 'ID_PRIVACIDADEBUSCA', 'int', 1, array('default' => '1'));
        $this->_addField('ID_PRIVACIDADEMENSAGENS', 'ID_PRIVACIDADEMENSAGENS', 'int', 1, array('default' => '1'));
        $this->_addField('SOBREMIM', 'SOBREMIM', 'text', 65535, array());
        $this->_addField('tb_social_foto', 'ID_FOTOPERFIL', 'int', 50, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_FOTO', 'class' => 'Tb_social_foto'));
        $this->_addField('CSSCLASSPATTERN', 'CSSCLASSPATTERN', 'varchar', 50, array());
        $this->_addField('CSSCLASSCOLOR', 'CSSCLASSCOLOR', 'varchar', 50, array());
        $this->_addField('FL_HABILITASOMCHAT', 'FL_HABILITASOMCHAT', 'int', 1, array('notnull' => true, 'default' => '1'));

        
        $this->_addForeignRelation('tb_social_albumfotos', self::ONE_TO_MANY, 'Tb_social_albumfotos', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_amigos', self::ONE_TO_MANY, 'Tb_social_amigos', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_amigos_1', self::ONE_TO_MANY, 'Tb_social_amigos', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_comentarios', self::ONE_TO_MANY, 'Tb_social_comentarios', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_depoimentos', self::ONE_TO_MANY, 'Tb_social_depoimentos', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_depoimentos_1', self::ONE_TO_MANY, 'Tb_social_depoimentos', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_recados', self::ONE_TO_MANY, 'Tb_social_recados', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_recados_1', self::ONE_TO_MANY, 'Tb_social_recados', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_social_videos', self::ONE_TO_MANY, 'Tb_social_videos', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_socialauditorias', self::ONE_TO_MANY, 'Tb_socialauditoria', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_socialcomentario_curtir', self::ONE_TO_MANY, 'Tb_socialcomentario_curtir', 'tb_glove_perfil', null, null, null);
        $this->_addForeignRelation('tb_socialrecado_curtir', self::ONE_TO_MANY, 'Tb_socialrecado_curtir', 'tb_glove_perfil', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_glove_perfil
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_glove_perfil;
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
