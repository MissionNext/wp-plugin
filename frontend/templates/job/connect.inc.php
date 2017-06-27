<?php
define("PG_USER", "admin");
define("PG_PASS", "kdfFGTss");
define("PG_HOST", "72.10.48.27");
define("PG_DBSE", "mission_next_prod");
define("PG_PORT", "5432");
global $db_link; // for use inside a function 
$db_link = pg_connect("host=".PG_HOST." port=".PG_PORT." dbname=".PG_DBSE." user=".PG_USER." password=".PG_PASS);
?>