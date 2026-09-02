copia esto:<?php
require_once 'conexion.php';

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cliente (
            idCliente INTEGER PRIMARY KEY AUTOINCREMENT,
            Nombre varchar(35) NOT NULL,
            Apellido varchar(35) NOT NULL,
            CI INTEGER NOT NULL,
            Celular INTEGER NOT NULL,
            Fecha_Nacimiento date NOT NULL,
            Email varchar(35) DEFAULT NULL,
            Direccion varchar(80) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS ejecutivo (
            idEjecutivo INTEGER PRIMARY KEY AUTOINCREMENT,
            Ejecutivo varchar(30) NOT NULL,
            Telefono INTEGER NOT NULL,
            Sucursal varchar(25) DEFAULT NULL
        );

        CREATE TABLE IF NOT EXISTS plan_tigo_hogar (
            idPlan INTEGER PRIMARY KEY AUTOINCREMENT,
            Plan varchar(25) NOT NULL,
            Velocidad_Mbps varchar(15) NOT NULL,
            MovilMB varchar(40) NOT NULL,
            Llamadas varchar(15) NOT NULL,
            Tv_HFC varchar(25) DEFAULT NULL,
            Mensualidad varchar(15) NOT NULL,
            Costo_Instalacion varchar(10) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS solicitud (
            idSolicitud INTEGER PRIMARY KEY AUTOINCREMENT,
            Fecha TEXT NOT NULL,
            Codigo INTEGER NOT NULL,
            idCliente INTEGER NOT NULL,
            idPlan INTEGER NOT NULL,
            idEjecutivo INTEGER NOT NULL,
            FOREIGN KEY (idCliente) REFERENCES cliente (idCliente),
            FOREIGN KEY (idEjecutivo) REFERENCES ejecutivo (idEjecutivo),
            FOREIGN KEY (idPlan) REFERENCES plan_tigo_hogar (idPlan)
        );

        CREATE TABLE IF NOT EXISTS instalacion (
            idInstalacion INTEGER PRIMARY KEY AUTOINCREMENT,
            Estado varchar(20) NOT NULL,
            Tecnico_Asignado varchar(30) NOT NULL,
            Fecha_Programado TEXT DEFAULT NULL,
            Fecha_Realizada TEXT DEFAULT NULL,
            idSolicitud INTEGER NOT NULL,
            FOREIGN KEY (idSolicitud) REFERENCES solicitud (idSolicitud)
        );

        CREATE TABLE IF NOT EXISTS factura (
            idFactura INTEGER PRIMARY KEY AUTOINCREMENT,
            NumeroFact INTEGER NOT NULL,
            Fecha_Emision TEXT NOT NULL,
            Monto_Total INTEGER NOT NULL,
            Estado varchar(15) NOT NULL,
            Metodo_Pago varchar(20) NOT NULL,
            idSolicitud INTEGER NOT NULL REFERENCES solicitud (idSolicitud),
            idCliente INTEGER NOT NULL REFERENCES cliente (idCliente)
        );
    ");
} catch (PDOException $e) {
    die("Error de inicialización de la base de datos: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tabla = $_POST['tabla_origen'] ?? 'cliente';

    try {
        if ($tabla === 'cliente') {
            $id = !empty($_POST['idCliente']) ? $_POST['idCliente'] : null;
            if ($id) {
                $stmt = $pdo->prepare("UPDATE cliente SET Nombre=?, Apellido=?, CI=?, Celular=?, Fecha_Nacimiento=?, Email=?, Direccion=? WHERE idCliente=?");
                $stmt->execute([$_POST['Nombre'], $_POST['Apellido'], $_POST['CI'], $_POST['Celular'], $_POST['Fecha_Nacimiento'], $_POST['Email'], $_POST['Direccion'], $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO cliente (Nombre, Apellido, CI, Celular, Fecha_Nacimiento, Email, Direccion) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['Nombre'], $_POST['Apellido'], $_POST['CI'], $_POST['Celular'], $_POST['Fecha_Nacimiento'], $_POST['Email'], $_POST['Direccion']]);
            }
        } 
        elseif ($tabla === 'ejecutivo') {
            $id = !empty($_POST['idEjecutivo']) ? $_POST['idEjecutivo'] : null;
            if ($id) {
                $stmt = $pdo->prepare("UPDATE ejecutivo SET Ejecutivo=?, Telefono=?, Sucursal=? WHERE idEjecutivo=?");
                $stmt->execute([$_POST['Ejecutivo'], $_POST['Telefono'], $_POST['Sucursal'], $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO ejecutivo (Ejecutivo, Telefono, Sucursal) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['Ejecutivo'], $_POST['Telefono'], $_POST['Sucursal']]);
            }
        } 
        elseif ($tabla === 'plan_tigo_hogar') {
            $id = !empty($_POST['idPlan']) ? $_POST['idPlan'] : null;
            if ($id) {
                $stmt = $pdo->prepare("UPDATE plan_tigo_hogar SET Plan=?, Velocidad_Mbps=?, MovilMB=?, Llamadas=?, Tv_HFC=?, Mensualidad=?, Costo_Instalacion=? WHERE idPlan=?");
                $stmt->execute([$_POST['Plan'], $_POST['Velocidad_Mbps'], $_POST['MovilMB'], $_POST['Llamadas'], $_POST['Tv_HFC'], $_POST['Mensualidad'], $_POST['Costo_Instalacion'], $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO plan_tigo_hogar (Plan, Velocidad_Mbps, MovilMB, Llamadas, Tv_HFC, Mensualidad, Costo_Instalacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['Plan'], $_POST['Velocidad_Mbps'], $_POST['MovilMB'], $_POST['Llamadas'], $_POST['Tv_HFC'], $_POST['Mensualidad'], $_POST['Costo_Instalacion']]);
            }
        } 
        elseif ($tabla === 'solicitud') {
            $id = !empty($_POST['idSolicitud']) ? $_POST['idSolicitud'] : null;
            if ($id) {
                $stmt = $pdo->prepare("UPDATE solicitud SET Fecha=?, Codigo=?, idCliente=?, idPlan=?, idEjecutivo=? WHERE idSolicitud=?");
                $stmt->execute([$_POST['Fecha'], $_POST['Codigo'], $_POST['idCliente'], $_POST['idPlan'], $_POST['idEjecutivo'], $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO solicitud (Fecha, Codigo, idCliente, idPlan, idEjecutivo) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['Fecha'], $_POST['Codigo'], $_POST['idCliente'], $_POST['idPlan'], $_POST['idEjecutivo']]);
            }
        } 
        elseif ($tabla === 'instalacion') {
            $id = !empty($_POST['idInstalacion']) ? $_POST['idInstalacion'] : null;
            if ($id) {
                $stmt = $pdo->prepare("UPDATE instalacion SET Estado=?, Tecnico_Asignado=?, Fecha_Programado=?, Fecha_Realizada=?, idSolicitud=? WHERE idInstalacion=?");
                $stmt->execute([$_POST['Estado'], $_POST['Tecnico_Asignado'], $_POST['Fecha_Programado'], $_POST['Fecha_Realizada'], $_POST['idSolicitud'], $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO instalacion (Estado, Tecnico_Asignado, Fecha_Programado, Fecha_Realizada, idSolicitud) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['Estado'], $_POST['Tecnico_Asignado'], $_POST['Fecha_Programado'], $_POST['Fecha_Realizada'], $_POST['idSolicitud']]);
            }
        } 
        elseif ($tabla === 'factura') {
            $id = !empty($_POST['idFactura']) ? $_POST['idFactura'] : null;
            if ($id) {
                $stmt = $pdo->prepare("UPDATE factura SET NumeroFact=?, Fecha_Emision=?, Monto_Total=?, Estado=?, Metodo_Pago=?, idSolicitud=?, idCliente=? WHERE idFactura=?");
                $stmt->execute([$_POST['NumeroFact'], $_POST['Fecha_Emision'], $_POST['Monto_Total'], $_POST['Estado'], $_POST['Metodo_Pago'], $_POST['idSolicitud'], $_POST['idCliente'], $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO factura (NumeroFact, Fecha_Emision, Monto_Total, Estado, Metodo_Pago, idSolicitud, idCliente) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['NumeroFact'], $_POST['Fecha_Emision'], $_POST['Monto_Total'], $_POST['Estado'], $_POST['Metodo_Pago'], $_POST['idSolicitud'], $_POST['idCliente']]);
            }
        }

        header("Location: index.php?tabla=" . $tabla);
        exit;
    } catch (PDOException $e) {
        die("Error al guardar en la base de datos: " . $e->getMessage());
    }
}
?>