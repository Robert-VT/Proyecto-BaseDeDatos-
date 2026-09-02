<?php
require_once 'conexion.php';

try {
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS cliente (
        idCliente INTEGER PRIMARY KEY AUTOINCREMENT,
        Nombre varchar(35) NOT NULL,
        Apellido varchar(35) NOT NULL,
        CI INTEGER NOT NULL,
        Celular INTEGER NOT NULL,
        Fecha_Nacimiento date NOT NULL,
        Email varchar(35) DEFAULT NULL,
        Direccion varchar(80) NOT NULL
    );");

    
    $pdo->exec("CREATE TABLE IF NOT EXISTS ejecutivo (
        idEjecutivo INTEGER PRIMARY KEY AUTOINCREMENT,
        Ejecutivo varchar(30) NOT NULL,
        Telefono INTEGER NOT NULL,
        Sucursal varchar(25) DEFAULT NULL
    );");

   
    $pdo->exec("CREATE TABLE IF NOT EXISTS plan_tigo_hogar (
        idPlan INTEGER PRIMARY KEY AUTOINCREMENT,
        Plan varchar(25) NOT NULL,
        Velocidad_Mbps varchar(15) NOT NULL,
        MovilMB varchar(40) NOT NULL,
        Llamadas varchar(15) NOT NULL,
        Tv_HFC varchar(25) DEFAULT NULL,
        Mensualidad varchar(15) NOT NULL,
        Costo_Instalacion varchar(10) NOT NULL
    );");

    
    $pdo->exec("CREATE TABLE IF NOT EXISTS solicitud (
        idSolicitud INTEGER PRIMARY KEY AUTOINCREMENT,
        Fecha TEXT NOT NULL,
        Codigo INTEGER NOT NULL,
        idCliente INTEGER NOT NULL,
        idPlan INTEGER NOT NULL,
        idEjecutivo INTEGER NOT NULL,
        FOREIGN KEY (idCliente) REFERENCES cliente (idCliente),
        FOREIGN KEY (idEjecutivo) REFERENCES ejecutivo (idEjecutivo),
        FOREIGN KEY (idPlan) REFERENCES plan_tigo_hogar (idPlan)
    );");

    
    $pdo->exec("CREATE TABLE IF NOT EXISTS instalacion (
        idInstalacion INTEGER PRIMARY KEY AUTOINCREMENT,
        Estado varchar(20) NOT NULL,
        Tecnico_Asignado varchar(30) NOT NULL,
        Fecha_Programado TEXT DEFAULT NULL,
        Fecha_Realizada TEXT DEFAULT NULL,
        idSolicitud INTEGER NOT NULL,
        FOREIGN KEY (idSolicitud) REFERENCES solicitud (idSolicitud)
    );");

    
    $pdo->exec("CREATE TABLE IF NOT EXISTS factura (
        idFactura INTEGER PRIMARY KEY AUTOINCREMENT,
        NumeroFact INTEGER NOT NULL,
        Fecha_Emision TEXT NOT NULL,
        Monto_Total INTEGER NOT NULL,
        Estado varchar(15) NOT NULL,
        Metodo_Pago varchar(20) NOT NULL,
        idSolicitud INTEGER NOT NULL REFERENCES solicitud (idSolicitud),
        idCliente INTEGER NOT NULL REFERENCES cliente (idCliente)
    );");

    echo "✅ ¡Todas las tablas fueron creadas/verificadas con éxito en SQLite!";
} catch (PDOException $e) {
    echo "❌ Error al crear tablas: " . $e->getMessage();
}
?>