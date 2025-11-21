<?php
require __DIR__ . "/../config/Conexion.php";

class Usuario
{
    public function __construct()
    {
    }

    public function insertar($nombre, $correo, $contrasena, $telefono)
    {
        try {
            $sql_check = "SELECT 1 FROM usuario WHERE Correo = '$correo' LIMIT 1";
            $res_check = ejecutarConsulta($sql_check);

            if ($res_check && $res_check->fetch(PDO::FETCH_NUM)) {
                return 1062;
            }

            $sql = "INSERT INTO usuario (Nombre, Correo, Contraseña, Telefono)
                    VALUES ('$nombre', '$correo', '$contrasena', '$telefono')";
            $ok = ejecutarConsulta($sql);
            return $ok ? 1 : 0;

        } catch (Exception $e) {
            return $e->getCode();
        }
    }

    public function editar($id, $nombre, $correo, $telefono)
    {
        $sql = "UPDATE usuario 
                SET Nombre='$nombre', Correo='$correo', Telefono='$telefono'
                WHERE Id='$id'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM usuario WHERE Id='$id'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id)
    {
        $sql = "SELECT * FROM usuario WHERE Id='$id'";
        $res = ejecutarConsulta($sql);
        return $res ? $res->fetch(PDO::FETCH_ASSOC) : null;
    }

    public function mostrarPorCorreo($correo)
    {
        $sql = "SELECT * FROM usuario WHERE Correo='$correo'";
        $res = ejecutarConsulta($sql);
        return $res ? $res->fetch(PDO::FETCH_ASSOC) : null;
    }

    public function autenticar($correo, $contrasena)
    {
        $sql = "SELECT * FROM usuario
                WHERE Correo='$correo' AND Contraseña='$contrasena' LIMIT 1";

        $res = ejecutarConsulta($sql);
        return $res ? $res->fetch(PDO::FETCH_ASSOC) : null;
    }

    public function listar()
    {
        $sql = "SELECT * FROM usuario";
        return ejecutarConsulta($sql);
    }
}
