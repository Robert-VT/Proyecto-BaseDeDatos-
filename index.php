<?php
require_once 'conexion.php';


$tabla = $_GET['tabla'] ?? 'cliente';
$tablasValidas = ['cliente', 'ejecutivo', 'plan_tigo_hogar', 'solicitud', 'instalacion', 'factura'];
if (!in_array($tabla, $tablasValidas)) { $tabla = 'cliente'; }


$pks = [
    'cliente' => 'idCliente',
    'ejecutivo' => 'idEjecutivo',
    'plan_tigo_hogar' => 'idPlan',
    'solicitud' => 'idSolicitud',
    'instalacion' => 'idInstalacion',
    'factura' => 'idFactura'
];
$pkActual = $pks[$tabla];


$editarRegistro = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM {$tabla} WHERE {$pkActual} = ?");
    $stmt->execute([$_GET['editar']]);
    $editarRegistro = $stmt->fetch();
}


try {
    $registros = $pdo->query("SELECT * FROM {$tabla}")->fetchAll();
} catch (Exception $e) {
    $registros = [];
}


try { $listaClientes = $pdo->query("SELECT idCliente, Nombre, Apellido FROM cliente")->fetchAll(); } catch (Exception $e) { $listaClientes = []; }
try { $listaEjecutivos = $pdo->query("SELECT idEjecutivo, Ejecutivo FROM ejecutivo")->fetchAll(); } catch (Exception $e) { $listaEjecutivos = []; }
try { $listaPlanes = $pdo->query("SELECT idPlan, Plan FROM plan_tigo_hogar")->fetchAll(); } catch (Exception $e) { $listaPlanes = []; }
try { $listaSolicitudes = $pdo->query("SELECT idSolicitud, Codigo FROM solicitud")->fetchAll(); } catch (Exception $e) { $listaSolicitudes = []; }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Tigo - CRUD Completo</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { display: flex; height: 100vh; background: #f4f6f9; }
        .sidebar { width: 230px; background: #001a35; color: white; padding: 20px 10px; flex-shrink: 0; }
        .sidebar h2 { color: #00c3ff; font-size: 18px; text-align: center; margin-bottom: 20px; }
        .sidebar a { display: block; padding: 10px 15px; color: #b8c7ce; text-decoration: none; border-radius: 4px; margin-bottom: 5px; font-size: 14px; }
        .sidebar a:hover, .sidebar a.active { background: #0056b3; color: white; }
        .main { flex: 1; padding: 25px; overflow-y: auto; }
        .crud-box { display: grid; grid-template-columns: 320px 1fr; gap: 20px; margin-top: 15px; }
        .card { background: white; padding: 20px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h3 { margin-bottom: 15px; font-size: 16px; color: #001a35; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 3px; }
        .form-group input, .form-group select { width: 100%; padding: 7px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px; }
        .btn { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 12px; display: inline-block; }
        .btn-submit { background: #28a745; color: white; width: 100%; margin-top: 10px; font-weight: bold; }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-del { background: #dc3545; color: white; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 8px 10px; border: 1px solid #dee2e6; text-align: left; font-size: 13px; }
        th { background: #e9ecef; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TIGO ADMIN</h2>
        <a href="index.php?tabla=cliente" class="<?= $tabla === 'cliente' ? 'active' : '' ?>">👤 Clientes</a>
        <a href="index.php?tabla=ejecutivo" class="<?= $tabla === 'ejecutivo' ? 'active' : '' ?>">💼 Ejecutivos</a>
        <a href="index.php?tabla=plan_tigo_hogar" class="<?= $tabla === 'plan_tigo_hogar' ? 'active' : '' ?>">🏠 Planes Hogar</a>
        <a href="index.php?tabla=solicitud" class="<?= $tabla === 'solicitud' ? 'active' : '' ?>">📄 Solicitudes</a>
        <a href="index.php?tabla=instalacion" class="<?= $tabla === 'instalacion' ? 'active' : '' ?>">🛠️ Instalaciones</a>
        <a href="index.php?tabla=factura" class="<?= $tabla === 'factura' ? 'active' : '' ?>">🧾 Facturas</a>
    </div>

    <div class="main">
        <h1>Gestión de <?= strtoupper(str_replace('_', ' ', $tabla)) ?></h1>

        <div class="crud-box">
        
            <div class="card">
                <h3><?= $editarRegistro ? 'Editar Registro' : 'Nuevo Registro' ?></h3>
                <form action="guardar.php" method="POST">
                    <input type="hidden" name="tabla_origen" value="<?= $tabla ?>">
                    <?php if ($editarRegistro): ?>
                        <input type="hidden" name="<?= $pkActual ?>" value="<?= $editarRegistro[$pkActual] ?>">
                    <?php endif; ?>

                    <?php if ($tabla === 'cliente'): ?>
                        <div class="form-group"><label>Nombre</label><input type="text" name="Nombre" value="<?= $editarRegistro['Nombre'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Apellido</label><input type="text" name="Apellido" value="<?= $editarRegistro['Apellido'] ?? '' ?>" required></div>
                        <div class="form-group"><label>CI</label><input type="number" name="CI" value="<?= $editarRegistro['CI'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Celular</label><input type="number" name="Celular" value="<?= $editarRegistro['Celular'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Fecha Nacimiento</label><input type="date" name="Fecha_Nacimiento" value="<?= $editarRegistro['Fecha_Nacimiento'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Email</label><input type="email" name="Email" value="<?= $editarRegistro['Email'] ?? '' ?>"></div>
                        <div class="form-group"><label>Dirección</label><input type="text" name="Direccion" value="<?= $editarRegistro['Direccion'] ?? '' ?>" required></div>

                    <?php elseif ($tabla === 'ejecutivo'): ?>
                        <div class="form-group"><label>Ejecutivo</label><input type="text" name="Ejecutivo" value="<?= $editarRegistro['Ejecutivo'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Teléfono</label><input type="number" name="Telefono" value="<?= $editarRegistro['Telefono'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Sucursal</label><input type="text" name="Sucursal" value="<?= $editarRegistro['Sucursal'] ?? '' ?>"></div>

                    <?php elseif ($tabla === 'plan_tigo_hogar'): ?>
                        <div class="form-group"><label>Plan</label><input type="text" name="Plan" value="<?= $editarRegistro['Plan'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Velocidad Mbps</label><input type="text" name="Velocidad_Mbps" value="<?= $editarRegistro['Velocidad_Mbps'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Móvil MB</label><input type="text" name="MovilMB" value="<?= $editarRegistro['MovilMB'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Llamadas</label><input type="text" name="Llamadas" value="<?= $editarRegistro['Llamadas'] ?? '' ?>" required></div>
                        <div class="form-group"><label>TV HFC</label><input type="text" name="Tv_HFC" value="<?= $editarRegistro['Tv_HFC'] ?? '' ?>"></div>
                        <div class="form-group"><label>Mensualidad</label><input type="text" name="Mensualidad" value="<?= $editarRegistro['Mensualidad'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Costo Instalación</label><input type="text" name="Costo_Instalacion" value="<?= $editarRegistro['Costo_Instalacion'] ?? '' ?>" required></div>

                    <?php elseif ($tabla === 'solicitud'): ?>
                        <div class="form-group"><label>Fecha</label><input type="date" name="Fecha" value="<?= $editarRegistro['Fecha'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Código</label><input type="number" name="Codigo" value="<?= $editarRegistro['Codigo'] ?? '' ?>" required></div>
                        <div class="form-group">
                            <label>Cliente</label>
                            <select name="idCliente" required>
                                <?php foreach ($listaClientes as $c): ?>
                                    <option value="<?= $c['idCliente'] ?>" <?= ($editarRegistro['idCliente'] ?? '') == $c['idCliente'] ? 'selected' : '' ?>><?= $c['Nombre'] . ' ' . $c['Apellido'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Plan</label>
                            <select name="idPlan" required>
                                <?php foreach ($listaPlanes as $p): ?>
                                    <option value="<?= $p['idPlan'] ?>" <?= ($editarRegistro['idPlan'] ?? '') == $p['idPlan'] ? 'selected' : '' ?>><?= $p['Plan'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ejecutivo</label>
                            <select name="idEjecutivo" required>
                                <?php foreach ($listaEjecutivos as $e): ?>
                                    <option value="<?= $e['idEjecutivo'] ?>" <?= ($editarRegistro['idEjecutivo'] ?? '') == $e['idEjecutivo'] ? 'selected' : '' ?>><?= $e['Ejecutivo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <?php elseif ($tabla === 'instalacion'): ?>
                        <div class="form-group"><label>Estado</label><input type="text" name="Estado" value="<?= $editarRegistro['Estado'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Técnico Asignado</label><input type="text" name="Tecnico_Asignado" value="<?= $editarRegistro['Tecnico_Asignado'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Fecha Programado</label><input type="date" name="Fecha_Programado" value="<?= $editarRegistro['Fecha_Programado'] ?? '' ?>"></div>
                        <div class="form-group"><label>Fecha Realizada</label><input type="date" name="Fecha_Realizada" value="<?= $editarRegistro['Fecha_Realizada'] ?? '' ?>"></div>
                        <div class="form-group">
                            <label>Solicitud (Código)</label>
                            <select name="idSolicitud" required>
                                <?php foreach ($listaSolicitudes as $s): ?>
                                    <option value="<?= $s['idSolicitud'] ?>" <?= ($editarRegistro['idSolicitud'] ?? '') == $s['idSolicitud'] ? 'selected' : '' ?>><?= $s['Codigo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <?php elseif ($tabla === 'factura'): ?>
                        <div class="form-group"><label>Número Factura</label><input type="number" name="NumeroFact" value="<?= $editarRegistro['NumeroFact'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Fecha Emisión</label><input type="date" name="Fecha_Emision" value="<?= $editarRegistro['Fecha_Emision'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Monto Total</label><input type="number" name="Monto_Total" value="<?= $editarRegistro['Monto_Total'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Estado</label><input type="text" name="Estado" value="<?= $editarRegistro['Estado'] ?? '' ?>" required></div>
                        <div class="form-group"><label>Método de Pago</label><input type="text" name="Metodo_Pago" value="<?= $editarRegistro['Metodo_Pago'] ?? '' ?>" required></div>
                        <div class="form-group">
                            <label>Solicitud</label>
                            <select name="idSolicitud" required>
                                <?php foreach ($listaSolicitudes as $s): ?>
                                    <option value="<?= $s['idSolicitud'] ?>" <?= ($editarRegistro['idSolicitud'] ?? '') == $s['idSolicitud'] ? 'selected' : '' ?>><?= $s['Codigo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Cliente</label>
                            <select name="idCliente" required>
                                <?php foreach ($listaClientes as $c): ?>
                                    <option value="<?= $c['idCliente'] ?>" <?= ($editarRegistro['idCliente'] ?? '') == $c['idCliente'] ? 'selected' : '' ?>><?= $c['Nombre'] . ' ' . $c['Apellido'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-submit"><?= $editarRegistro ? 'Guardar Cambios' : 'Registrar' ?></button>
                    <?php if ($editarRegistro): ?>
                        <a href="index.php?tabla=<?= $tabla ?>" style="display:block; text-align:center; margin-top:8px; font-size:12px; color:#666;">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Tabla de Registros -->
            <div class="card" style="overflow-x: auto;">
                <h3>Registros Existentes</h3>
                <table>
                    <thead>
                        <tr>
                            <?php if (!empty($registros)): ?>
                                <?php foreach (array_keys($registros[0]) as $col): ?>
                                    <th><?= htmlspecialchars($col) ?></th>
                                <?php endforeach; ?>
                                <th>Acciones</th>
                            <?php else: ?>
                                <th>Sin Registros</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $row): ?>
                            <tr>
                                <?php foreach ($row as $val): ?>
                                    <td><?= htmlspecialchars($val ?? '') ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <a href="index.php?tabla=<?= $tabla ?>&editar=<?= $row[$pkActual] ?>" class="btn btn-edit">Editar</a>
                                    <a href="eliminar.php?tabla=<?= $tabla ?>&id=<?= $row[$pkActual] ?>" class="btn btn-del" onclick="return confirm('¿Eliminar este registro?')">Borrar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>