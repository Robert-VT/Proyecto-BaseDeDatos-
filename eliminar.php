<?php
require_once 'conexion.php';

$tabla = $_GET['tabla'] ?? 'cliente';
$id = $_GET['id'] ?? null;

if ($id) {
    $pk = [
        'cliente' => 'idCliente',
        'ejecutivo' => 'idEjecutivo',
        'plan_tigo_hogar' => 'idPlan',
        'solicitud' => 'idSolicitud',
        'instalacion' => 'idInstalacion',
        'factura' => 'idFactura'
    ][$tabla] ?? 'id';

    $stmt = $pdo->prepare("DELETE FROM {$tabla} WHERE {$pk} = ?");
    $stmt->execute([$id]);
}

header("Location: index.php?tabla=" . $tabla);
exit;
?>