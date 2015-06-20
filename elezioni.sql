-- Creo la tabella elections che conterrà le elezioni

CREATE TABLE IF NOT EXISTS `elections` (
`id_ele` int(11) NOT NULL auto_increment,
  `nome_ele` varchar(60) COLLATE utf8_swedish_ci NOT NULL,
  `scadenza_ele` date NOT NULL
  PRIMARY KEY (`id_ele`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Creo la tabella candidates che conterrà i candidati

CREATE TABLE IF NOT EXISTS `candidates` (
`id_cand` int(11) NOT NULL auto_increment,
  `elezione_cand` int(11) NOT NULL,
  `nome_cand` varchar(20) COLLATE utf8_swedish_ci NOT NULL,
  `partito_cand` varchar(65) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id_cand`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- Creo la tabella voters che conterrà i votanti

CREATE TABLE IF NOT EXISTS `voters` (
`id_vot` int(11) NOT NULL auto_increment,
  `nome_vot` varchar(40) COLLATE utf8_swedish_ci NOT NULL,
  `elezione_vot` int(11) NOT NULL,
  `candidato_vot` int(11) NOT NULL,
  PRIMARY KEY (`id_vot`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
