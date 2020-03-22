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
 * Classe generada para a tabela "tb_sys_usuario"
 * in 2013-02-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package dao
 *
 */

class Tb_sys_usuario extends Lumine_Base {

    // sobrecarga
    protected $_tablename = 'tb_sys_usuario';
    protected $_package   = 'dao';
    
    
    public $ID_USUARIO;
    public $tb_sys_empresa;
    public $tb_sys_planos;
    public $tb_sys_cidade;
    public $tb_sys_franqueado;
    public $tb_sys_grupos;
    public $tb_sys_tipousuario;
    public $tb_sys_usuario_estado_civil;
    public $tb_sys_endereco;
    public $tb_sys_contato;
    public $NOME;
    public $SOBRENOME;
    public $SLUG;
    public $EMAIL;
    public $SENHA;
    public $DT_CADASTRO;
    public $DT_MODIFICACAO;
    public $GENERO;
    public $DT_NASCIMENTO;
    public $DT_ULTIMOACESSO;
    public $SOBRE_VOCE;
    public $ACEITOUTERMOS;
    public $FL_RECEBERNOVIDADES;
    public $FL_ATIVO;
    public $FL_PRIMEIROACESSO;
    public $QTD_ACESSOS;
    public $tb_glove_perfis = array();
    
    
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->_addField('ID_USUARIO', 'ID_USUARIO', 'int', 30, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->_addField('tb_sys_empresa', 'ID_EMPRESA', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_EMPRESA', 'class' => 'Tb_sys_empresa'));
        $this->_addField('tb_sys_planos', 'ID_PLANO', 'int', 11, array('notnull' => true, 'default' => '1', 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_PLANO', 'class' => 'Tb_sys_planos'));
        $this->_addField('tb_sys_cidade', 'ID_CIDADE', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_CIDADE', 'class' => 'Tb_sys_cidade'));
        $this->_addField('tb_sys_franqueado', 'ID_FRANQUEADO', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_FRANQUEADO', 'class' => 'Tb_sys_franqueado'));
        $this->_addField('tb_sys_grupos', 'ID_GRUPO', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_GRUPO', 'class' => 'Tb_sys_grupos'));
        $this->_addField('tb_sys_tipousuario', 'ID_TIPOUSUARIO', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_TIPOUSUARIO', 'class' => 'Tb_sys_tipousuario'));
        $this->_addField('tb_sys_usuario_estado_civil', 'ID_ESTADOCIVIL', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_ESTADOCIVIL', 'class' => 'Tb_sys_usuario_estado_civil'));
        $this->_addField('tb_sys_endereco', 'ID_ENDERECO', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_ENDERECO', 'class' => 'Tb_sys_endereco'));
        $this->_addField('tb_sys_contato', 'ID_CONTATO', 'int', 11, array('foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'ID_CONTATO', 'class' => 'Tb_sys_contato'));
        $this->_addField('NOME', 'NOME', 'varchar', 300, array('notnull' => true));
        $this->_addField('SOBRENOME', 'SOBRENOME', 'varchar', 100, array());
        $this->_addField('SLUG', 'SLUG', 'varchar', 100, array('notnull' => true));
        $this->_addField('EMAIL', 'EMAIL', 'varchar', 45, array('notnull' => true));
        $this->_addField('SENHA', 'SENHA', 'varchar', 45, array('notnull' => true));
        $this->_addField('DT_CADASTRO', 'DT_CADASTRO', 'datetime', null, array());
        $this->_addField('DT_MODIFICACAO', 'DT_MODIFICACAO', 'datetime', null, array());
        $this->_addField('GENERO', 'GENERO', 'varchar', 1, array());
        $this->_addField('DT_NASCIMENTO', 'DT_NASCIMENTO', 'date', null, array());
        $this->_addField('DT_ULTIMOACESSO', 'DT_ULTIMOACESSO', 'datetime', null, array());
        $this->_addField('SOBRE_VOCE', 'SOBRE_VOCE', 'text', 65535, array());
        $this->_addField('ACEITOUTERMOS', 'ACEITOUTERMOS', 'int', 1, array('notnull' => true, 'default' => '0'));
        $this->_addField('FL_RECEBERNOVIDADES', 'FL_RECEBERNOVIDADES', 'int', 1, array('default' => '0'));
        $this->_addField('FL_ATIVO', 'FL_ATIVO', 'int', 1, array('notnull' => true, 'default' => '0'));
        $this->_addField('FL_PRIMEIROACESSO', 'FL_PRIMEIROACESSO', 'int', 1, array('notnull' => true, 'default' => '1'));
        $this->_addField('QTD_ACESSOS', 'QTD_ACESSOS', 'int', 11, array('default' => '0'));

        
        $this->_addForeignRelation('tb_glove_perfis', self::ONE_TO_MANY, 'Tb_glove_perfil', 'tb_sys_usuario', null, null, null);
    }

    /**
     * Recupera um objeto estaticamente
     * @author Hugo Ferreira da Silva
     * @return Tb_sys_usuario
     */
    public static function staticGet($pk, $pkValue = null)
    {
        $obj = new Tb_sys_usuario;
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
