<?php 

$host= 'localhost';
$user= 'root';
$password ='';
$db='pasteleria';
$conection=@mysqli_connect($host,$user,$password,$db);
if(!$conection){
    echo "Error de Conexion";
}

?>