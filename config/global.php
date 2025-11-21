<?php

$databaseUrl = getenv("MYSQL_URL"); 

if ($databaseUrl) {
    $parsedUrl = parse_url($databaseUrl);

    define("DB_HOST", $parsedUrl["host"] ?? "localhost");
    define("DB_PORT", $parsedUrl["port"] ?? 3306);
    define("DB_NAME", ltrim($parsedUrl["path"] ?? "", "/"));
    define("DB_USERNAME", $parsedUrl["user"] ?? "");
    define("DB_PASSWORD", $parsedUrl["pass"] ?? "");
} else {
    define("DB_HOST", getenv("MYSQLHOST") ?: "localhost");
    define("DB_PORT", getenv("MYSQLPORT") ?: "3306");
    define("DB_NAME", getenv("MYSQLDATABASE") ?: "");
    define("DB_USERNAME", getenv("MYSQLUSER") ?: "");
    define("DB_PASSWORD", getenv("MYSQLPASSWORD") ?: "");
}

define("DB_ENCODE", "utf8mb4");
define("PRO_NOMBRE", "ITProyecto");

// Debug: Comentar después de verificar
error_log("DB_HOST: " . DB_HOST);
error_log("DB_NAME: " . DB_NAME);
error_log("DB_USERNAME: " . DB_USERNAME);
?>