<?php
session_start( );
//login var yoxsa yox yoxdursxa login
//daxil olan userin rolu teyin olunur
//rolde admindirse admin/index sehifesine yonlendir
//rolde user ise  client/index sehifesine yonlendir

include_once 'check-url.php';
require_once 'config/database.php';
require_once 'helper/help.php';
include_once 'head.php';
include_once 'navbar.php';
include_once 'main.php';

?>
