create database IF NOT EXISTS p2wiki;

use p2wiki;

CREATE TABLE IF NOT EXISTS `clientes` (
  `cod_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'F - fisica J - juridica',
  `cep` char(9) COLLATE utf8_unicode_ci NOT NULL,
  `endereco` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `complemento` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `bairro` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `cidade` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `estado` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `data_cad` date NOT NULL,
  `data_exp` date NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `last_mod` datetime NOT NULL,
  `last_mod_by` int(11) NOT NULL,
  PRIMARY KEY (`cod_cliente`)
);

CREATE TABLE IF NOT EXISTS `grupo` (
  `user_group` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `Descricao` varchar(200) NOT NULL,
  `limite` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - ativo 0 - inativo',
  PRIMARY KEY (`user_group`),
  constraint FK_grupo1 foreign key (`cod_cliente`) references clientes(`cod_cliente`)
);

CREATE TABLE IF NOT EXISTS `categorias` (
  `cod_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(21) NOT NULL,
  `categoria_pai` int(11) DEFAULT NULL,
  `glyphicon` varchar(50) NOT NULL DEFAULT 'glyphicon-cog',
  `user_group` int(11) NOT NULL,
  `publico` tinyint(1) NOT NULL COMMENT '0 ou 1',
  PRIMARY KEY (`cod_categoria`),
  constraint FK_cat1 foreign key (`user_group`) references grupo(`user_group`)
);

CREATE TABLE IF NOT EXISTS `pessoa_fisica` (
  `cod_cliente` int(11) NOT NULL,
  `rg` varchar(12)  NOT NULL,
  `estado_rg` char(2)  NOT NULL,
  `cpf` char(11) NOT NULL,
  `sexo` char(1) NOT NULL COMMENT 'F ou M',
  `data_nasc` date NOT NULL,
  UNIQUE KEY `cod_cliente` (`cpf`),
  primary key (cod_cliente),
  constraint FK_pf1 foreign key (`cod_cliente`) references clientes(`cod_cliente`)
);

CREATE TABLE IF NOT EXISTS `pessoa_juridica` (
  `cod_cliente` int(11) NOT NULL,
  `cnpj` char(18) NOT NULL,
  `ra_social` varchar(100) NOT NULL,
    primary key (cod_cliente),
  constraint FK_pj1 foreign key (`cod_cliente`) references clientes(`cod_cliente`)
);

CREATE TABLE IF NOT EXISTS `item_galeria` (
  `cod_item` int(11) NOT NULL primary key,
  `nome_item` text  NOT NULL,
  `tipo` text  NOT NULL,
  `tamanho_item` double NOT NULL,
  `inserido_em` datetime NOT NULL
);

CREATE TABLE IF NOT EXISTS `usuarios` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `last_mod` datetime DEFAULT NULL,
  `last_mod_by` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `nivel_acesso` int(11) NOT NULL DEFAULT '1',
  `cargo_acesso` int(11) NOT NULL COMMENT '1-funcionario/2-socio/3-master',
  `ativo` enum('S','N','B') NOT NULL DEFAULT 'S',
  `tentativas` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
);

CREATE TABLE IF NOT EXISTS `armazenamento` (
  `cod_armazenamento` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cod_item` int(11) NOT NULL,
  `data_arma` datetime NOT NULL,
  PRIMARY KEY (`cod_armazenamento`),
  constraint FK_armazenamento1 foreign key (`cod_cliente`) references clientes(`cod_cliente`),
  constraint FK_armazenamento2 foreign key (`user_id`) references usuarios(`user_id`),
  constraint FK_armazenamento3 foreign key (`cod_item`) references item_galeria(`cod_item`)
);

CREATE TABLE IF NOT EXISTS `contato` (
  `cod_contato` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `tipo` char(2)  NOT NULL COMMENT 'TF,TM ou EM',
  `contato` varchar(80)  NOT NULL,
  `falar_com` varchar(60)  NOT NULL,
  PRIMARY KEY (`cod_contato`),
   constraint FK_contato1 foreign key (`cod_cliente`) references clientes(`cod_cliente`)
);

CREATE TABLE IF NOT EXISTS `cliente_adm` (
  `cod_cliente` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`cod_cliente`,`user_id`),
  constraint FK_clienteadm1 foreign key (`cod_cliente`) references clientes(`cod_cliente`),
  constraint FK_clienteadm2 foreign key (`user_id`) references usuarios(`user_id`)
);


CREATE TABLE IF NOT EXISTS `topicos` (
  `cod_topico` int(11) NOT NULL AUTO_INCREMENT,
  `nome_topico` varchar(21) NOT NULL,
  `link_topico` varchar(200) NOT NULL DEFAULT '#',
  `cod_categoria` int(11) NOT NULL,
  `ordem` int(11) DEFAULT NULL,
  `descricao` varchar(297) NOT NULL,
  `acessos` bigint(20) NOT NULL DEFAULT '0',
  `publico` tinyint(1) NOT NULL COMMENT '0 ou 1',
  `cod_criador` int(11) NOT NULL,
  `last_mod_by` int(11) ,
  PRIMARY KEY (`cod_topico`),
  constraint FK_topicos1 foreign key (`cod_categoria`) references categorias(`cod_categoria`),
  constraint FK_topicos2 foreign key (`cod_criador`) references usuarios(`user_id`)
);

CREATE TABLE IF NOT EXISTS `conteudos` (
  `cod_conteudo` int(11) NOT NULL AUTO_INCREMENT,
  `autor` int(11) NOT NULL,
  `cod_topico` int(11) NOT NULL,
  `nome_conteudo` text NOT NULL,
  `conteudo` longtext NOT NULL,
  PRIMARY KEY (`cod_conteudo`),
  constraint FK_conteudos1 foreign key (`cod_topico`) references topicos(`cod_topico`),
  constraint FK_conteudos2 foreign key (`autor`) references usuarios(`user_id`)
  );

CREATE TABLE IF NOT EXISTS `galeria` (
  `cod_galeria` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` int(11) NOT NULL,
  `tamanho` double NOT NULL DEFAULT '0' COMMENT 'medido em kb',
  `limite` double NOT NULL DEFAULT '10000',
  PRIMARY KEY (`cod_galeria`),
   constraint FK_galeria1 foreign key (`cod_cliente`) references clientes(`cod_cliente`)
);

CREATE TABLE IF NOT EXISTS `niveis_acessos_internos` (
  `cod_acesso` int(11) NOT NULL,
  `descricao_acesso` text  NOT NULL,
  PRIMARY KEY (`cod_acesso`)
) ;

CREATE TABLE IF NOT EXISTS `palavra_chave` (
  `palavra_chave` varchar(40) NOT NULL,
  primary KEY (`palavra_chave`)
);

CREATE TABLE IF NOT EXISTS `palavra_chave_categoria` (
  `cod_categoria` int(11) NOT NULL,
  `palavra_chave_name` varchar(40)  NOT NULL,
  primary key(cod_categoria,palavra_chave_name),
   constraint FK_palavra_chave_categoria1 foreign key (`cod_categoria`) references categorias(`cod_categoria`),
   constraint FK_palavra_chave_categoria2 foreign key (`palavra_chave_name`) references palavra_chave(`palavra_chave_name`)
) ;

CREATE TABLE IF NOT EXISTS `palavra_chave_topico` (
  `cod_topico` int(11) NOT NULL,
  `palavra_chave_name` varchar(40) NOT NULL,
  primary key(cod_topico,palavra_chave_name),
  constraint FK_palavra_chave_topico1 foreign key (`cod_topico`) references topicos(`cod_topico`),
   constraint FK_palavra_chave_topico2 foreign key (`palavra_chave_name`) references palavra_chave(`palavra_chave_name`)
);

CREATE TABLE IF NOT EXISTS `usuario_grupo` (
  /*`user_group` int(11) NOT NULL AUTO_INCREMENT,*/
  `user_id` int(11) NOT NULL,
  `user_group` int(11) NOT NULL,
  `acesso_interno` int(11) NOT NULL COMMENT '1 - lê, 2- escreve, 3 - adm grupo',
  PRIMARY KEY (`user_id`,`user_group`,`acesso_interno`),
   constraint FK_usuario_grupo1 foreign key (`user_id`) references usuarios(`user_id`),
    constraint FK_usuario_grupo2 foreign key (`user_group`) references grupo(`user_group`),
     constraint FK_usuario_grupo3 foreign key (`acesso_interno`) references niveis_acessos_internos(`cod_acesso`)
);

CREATE TABLE IF NOT EXISTS `referencia` (
  `cod_topico` int(11) NOT NULL,
  `cod_conteudo` int(11) NOT NULL,
  PRIMARY KEY (`cod_topico`,`cod_conteudo`),
  constraint FK_referencia1 foreign key (`cod_conteudo`) references conteudos(`cod_conteudo`),
  constraint FK_referencia2 foreign key (`cod_topico`) references topicos(`cod_topico`)
) ;

CREATE TABLE IF NOT EXISTS `comentarios` (
  `cod_comentario` int(11) NOT NULL,
  `cod_topico` int(11) NOT NULL,
  `cod_usuario` int(11) NOT NULL COMMENT 'codigo do usuario criador',
  `comentario` varchar(500)  NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` int(11) NOT NULL,
  `deslikes` int(11) NOT NULL,
  PRIMARY KEY (`cod_comentario`),
  constraint FK_comentarios1 foreign key (cod_topico) references topicos(cod_topico),
  constraint FK_comentarios2 foreign key (cod_usuario) references usuarios(user_id)
);

CREATE TABLE IF NOT EXISTS `momento` (
  `cod_momento` int(11) NOT NULL,
  `momento`timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
)
CREATE TABLE IF NOT EXISTS `compatilha` (
  `cod_usuario` int(11) NOT NULL,
  `cod_topico` int(11) NOT NULL,
  `momento` int(11) NOT NULL,
  `publico` enum('0','1')  NOT NULL,
  PRIMARY KEY (`cod_usuario`,`cod_topico`,`data`),
  constraint FK_compatilha1 foreign key (cod_topico) references topicos(cod_topico),
  constraint FK_compatilha2 foreign key (cod_usuario) references usuarios(user_id),
  constraint FK_compatilha3 foreign key (momento) references momento(cod_momento)
) ;

CREATE TABLE IF NOT EXISTS `compatilha_cat` (
  `user_id` int(11) NOT NULL,
  `cod_categoria` int(11) NOT NULL,
  `momento` int(11) NOT NULL,
  `publico` enum('0','1')  NOT NULL,
  PRIMARY KEY (`user_id`,`cod_categoria`),
  constraint FK_compcat1 foreign key (`cod_categoria`) references categorias(`cod_categoria`),
  constraint FK_compcat2 foreign key (`user_id`) references usuarios(`user_id`),
  constraint FK_compcat3 foreign key (`momento`) references momento(`cod_momento`)
) ;
CREATE TABLE IF NOT EXISTS `referencia` (
  `cod_topico` int(11) NOT NULL,
  `cod_conteudo` int(11) NOT NULL,
  PRIMARY KEY (`cod_conteudo`,`cod_topico`),
  constraint FK_referencia1 foreign key (cod_topico) references topicos(cod_topico),
  constraint FK_referencia2 foreign key (cod_conteudo) references conteudos(cod_conteudo),
) ;
-- Procedures
--
CREATE DEFINER=`p2wik940`@`localhost` PROCEDURE `despublica_categorias_filhas_procedure`(IN `pai` INT(11))
    NO SQL
BEGIN

update categorias set publico = 0 where categoria_pai = pai;


END$$

--
-- Functions
--
CREATE DEFINER=`p2wik940`@`localhost` FUNCTION `get_produtividade`(`x` INT(11)) RETURNS int(11)
BEGIN
     DECLARE p1,p2,p3,r INT(11);
     SELECT count(cod_criador) into p1 FROM topicos WHERE user_id = x;
     SELECT count(user_id) * 2    into p2 FROM comentarios WHERE user_id = x;
     SELECT count(user_id) * 3    into p3 FROM usuario_conteudo WHERE user_id = x;
     set r = p1 + p2 + p3;
     RETURN p2;
     END$$

CREATE DEFINER=`p2wik940`@`localhost` FUNCTION `qtd_comentarios`(`x` INT(11)) RETURNS int(11)
BEGIN
     DECLARE qtdade INT(11);
     SELECT count(user_id) into qtdade FROM comentarios WHERE user_id = x;
     RETURN qtdade;
     END$$

CREATE DEFINER=`p2wik940`@`localhost` FUNCTION `qtd_conteudos`(x int(11)) RETURNS int(11)
BEGIN
     DECLARE qtdade INT(11);
     SELECT count(user_id) into qtdade FROM usuario_conteudo WHERE user_id = x;
     RETURN qtdade;
     END$$

CREATE DEFINER=`p2wik940`@`localhost` FUNCTION `qtd_topicos`(x int(11)) RETURNS int(11)
BEGIN
     DECLARE qtdade INT(11);
     SELECT count(cod_criador) into qtdade FROM topicos WHERE user_id = x;
     RETURN qtdade;
     END$$

DELIMITER ;
