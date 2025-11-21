<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once(__DIR__ . "/../modelos/Usuario.php");

$usuario = new Usuario();
$method = $_SERVER['REQUEST_METHOD'];

$raw = file_get_contents("php://input");
$body = json_decode($raw, true);
if (!is_array($body)) {
    $body = $_POST;
}

try {
    switch ($method) {

        case "POST":
            $nombre     = trim($body["nombre"] ?? "");
            $correo     = trim($body["correo"] ?? "");
            $contrasena = $body["contrasena"] ?? "";
            $telefono   = trim($body["telefono"] ?? "");

            if ($nombre === "" || $correo === "" || $contrasena === "" || $telefono === "") {
                echo json_encode(["Error" => "Todos los campos son obligatorios"]);
                break;
            }

            $res = $usuario->insertar($nombre, $correo, $contrasena, $telefono);

            if ($res === 1) {
                echo json_encode(["Correcto" => "Usuario registrado"]);
            } elseif ($res === 1062) {
                echo json_encode(["Error" => "El correo ya está registrado"]);
            } else {
                echo json_encode(["Error" => "No se pudo registrar el usuario"]);
            }
            break;

        case "PUT":
            $id         = intval($body["id"] ?? 0);
            $nombre     = trim($body["nombre"] ?? "");
            $correo     = trim($body["correo"] ?? "");
            $contrasena = $body["contrasena"] ?? "";
            $telefono   = trim($body["telefono"] ?? "");

            if ($id <= 0) {
                echo json_encode(["Error" => "Id de usuario obligatorio"]);
                break;
            }

            if ($nombre === "" || $correo === "" || $contrasena === "" || $telefono === "") {
                echo json_encode(["Error" => "Todos los campos son obligatorios"]);
                break;
            }

            $ok = $usuario->editar($id, $nombre, $correo, $contrasena, $telefono);
            echo json_encode($ok ? ["Correcto" => "Usuario actualizado"] : ["Error" => "No se pudo actualizar el usuario"]);
            break;

        case "DELETE":
            $id = intval($body["id"] ?? ($_GET["id"] ?? 0));

            if ($id <= 0) {
                echo json_encode(["Error" => "Id de usuario obligatorio"]);
                break;
            }

            $ok = $usuario->eliminar($id);
            echo json_encode($ok ? ["Correcto" => "Usuario eliminado"] : ["Error" => "No se pudo eliminar el usuario"]);
            break;

        case "GET":
            $id     = intval($_GET["id"] ?? 0);
            $correo = $_GET["correo"] ?? "";

            if ($id > 0) {
                $res = $usuario->mostrar($id);
                echo json_encode($res ?: ["Error" => "Usuario no encontrado"]);
                break;
            }

            if ($correo !== "") {
                $res = $usuario->mostrarPorCorreo($correo);
                echo json_encode($res ?: ["Error" => "Usuario no encontrado"]);
                break;
            }

            $rspta = $usuario->listar();
            $data = [];

            while ($reg = $rspta->fetch(PDO::FETCH_OBJ)) {
                $data[] = [$reg->Id, $reg->Nombre, $reg->Correo, $reg->Telefono];
            }

            echo json_encode([
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            ]);
            break;

        default:
            http_response_code(405);
            echo json_encode(["Error" => "Método no permitido"]);
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["Error" => "Error interno: " . $e->getMessage()]);
}
