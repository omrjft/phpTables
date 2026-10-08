<?php
$host = "localhost";
$usuario = "omar";
$password = "root";
$baseDatos = "escuela";

$conexion = new mysqli(

    $host,
    $usuario,
    $password,
    $baseDatos

);

if ($conexion->connect_error){
    die("error de conexion". $conexion->connect_error);
}
echo "conexion correcta";
