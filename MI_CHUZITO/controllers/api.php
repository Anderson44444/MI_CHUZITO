<?php
header('Content-Type: application/json');
require_once "../config/database.php";

$conn = Database::conectar();

$data = json_decode(file_get_contents("php://input"), true);

if ($data['accion'] == "obtener_productos") {

    $stmt = $conn->query("SELECT * FROM productos");

    $productos = array_map(function($p) {
        return [
            "id" => $p["id_producto"],
            "name" => $p["nombre"],
            "description" => $p["descripcion"],
            "price" => (int)$p["precio"],
             "image" => !empty($p["imagen"]) 
    ? $p["imagen"] 
    : "https://via.placeholder.com/400x300",   
            "category" => !empty($p["categoria"]) 
                ? $p["categoria"] 
                : "general",
            "available" => true,
            "stock" => 100
        ];
    }, $stmt->fetchAll(PDO::FETCH_ASSOC));

    echo json_encode($productos);
    exit;
}

if ($data['accion'] == "guardar_pedido") {

    $conn->exec("
        INSERT INTO pedidos 
        (id_usuario, estado, metodo_pago, direccion_entrega, total)
        VALUES (1, 'pendiente', 'efectivo', 'Sin dirección', 0)
    ");

    $pedido_id = $conn->lastInsertId();

    foreach ($data['carrito'] as $item) {
        $stmt = $conn->prepare("
            INSERT INTO detalle_pedido 
            (id_pedido, id_producto, cantidad, precio_unitario)
            VALUES (?, ?, ?, ?)
        ");

      $stmt->execute([
    $pedido_id,
    $item['id'],
    $item['quantity'], // 👈 aquí estaba el error
    $item['price']
]);
    }

    echo json_encode(["status" => "ok"]);
}
