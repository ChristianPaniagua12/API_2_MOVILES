<?php

$databaseUrl = getenv("MYSQL_URL"); 

if ($databaseUrl) {
    $parsedUrl = parse_url($databaseUrl);

    define("DB_HOST", $parsedUrl["host"]);
    define("DB_PORT", $parsedUrl["port"]);
    define("DB_NAME", ltrim($parsedUrl["path"], "/"));
    define("DB_USERNAME", $parsedUrl["user"]);
    define("DB_PASSWORD", $parsedUrl["pass"]);
} else {
    define("DB_HOST", getenv("MYSQLHOST"));
    define("DB_PORT", getenv("MYSQLPORT"));
    define("DB_NAME", getenv("MYSQLDATABASE"));
    define("DB_USERNAME", getenv("MYSQLUSER"));
    define("DB_PASSWORD", getenv("MYSQLPASSWORD"));
}

define("DB_ENCODE", "utf8mb4");
define("PRO_NOMBRE", "ITProyecto");
?>
