<?php

// coloque a barra / no final
define('URL', 'https://appserver.unimar.br/intranet/');
// **** OBS:> alterar também a url do preloader no main.css
define('ABS_PATH', "/www/web/intranet/");

define('LIBS', 'libs/');
//conf. da conexao com o BD
define('DB_TNS', '(DESCRIPTION =
					   (ADDRESS = (PROTOCOL = TCP)(HOST = 172.16.6.12)(PORT = 1521))
					   (CONNECT_DATA =
						  (SERVER = DEDICATED)
						  (SID = proj)
						)
					  )');
define('DB_USER', 'AUTOATENDIMENTO');
define('DB_PASS', 'AutoAten');