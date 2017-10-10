<?php
define("PG_USER", "admin");
define("PG_PASS", "D4ufA&.%WJ-^#erL]q,$F5ZmXG{~2\bG");
define("PG_HOST", "localhost");
define("PG_DBSE", "mission_next_prod");
define("PG_PORT", "5555");
global $db_link; // for use inside a function 
$db_link = pg_connect("host=".PG_HOST." port=".PG_PORT." dbname=".PG_DBSE." user=".PG_USER." password=".PG_PASS);
?>