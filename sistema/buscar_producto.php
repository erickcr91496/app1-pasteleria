<?php 
	session_start();
	include "../conexion.php";	
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php";?>
	<title>Lista de Productos</title>
</head>
<body>
<?php include "includes/header.php";?>
	<section id="container">
		<?php
$busqueda='';
$search_proveedor='';
if(empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor'])){
header("Location: lista_producto.php");
}
if(!empty($_REQUEST['busqueda'])){
 $busqueda=strtolower($_REQUEST['busqueda']);
$where="( p.coproducto LIKE '%$busqueda%' OR p.descripcion LIKE '%$busqueda%') AND p.estatus = 1";
$buscar='busqueda'.$busqueda;
}

if(!empty($_REQUEST['proveedor'])){
	$search_proveedor=$_REQUEST['proveedor'];
	$where="p.proveedor LIKE $search_proveedor AND p.estatus = 1 ";
	$buscar='proveedor'.$search_proveedor;
   }

?>
    <h1>Lista de Productos</h1>
 <a href="registro_producto.php" class="btn_new">Ingresar Producto</a>

 <form action="buscar_producto.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>
<table>
<tr>
    <th>ID</th>
    <th>Descripcion</th>
    <th>Proveedor</th>
	<th>Precio</th>
	<th>Cantidad</th>
    <th>Foto</th>
    <th>Acciones</th>
</tr>
 <?php


//Paginador
$sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM producto  as p WHERE  $where ");

$result_register = mysqli_fetch_array($sql_registe);
$total_registro = $result_register['total_registro'];


$por_pagina = 10;
if(empty($_GET['pagina']))
{
    $pagina = 1;
}else{
    $pagina = $_GET['pagina'];
}
$desde = ($pagina-1) * $por_pagina;
$total_paginas = ceil($total_registro / $por_pagina);

 $query = mysqli_query($conection, "SELECT p.coproducto, p.descripcion,pr.proveedor,p.precio, p.existencia,p.foto
  FROM producto p  INNER JOIN proveedores pr on p.proveedor= pr.idproveedor  WHERE $where
  ORDER BY p.coproducto ASC LIMIT $desde,$por_pagina");


mysqli_close($conection);
$result=mysqli_num_rows($query);

 if($result>0){
    while($data=mysqli_fetch_array($query)){
  if($data['foto']!='img_producto.png'){
      $foto='img/uploads/'.$data['foto'];
  }else{
    $foto='img/'.$data['foto'];
  }

      ?>
<tr class="row<?php echo $data["coproducto"]; ?>">
    <td><?php echo $data["coproducto"]; ?></td>
    <td><?php echo $data["descripcion"]  ; ?></td>
    <td><?php echo $data["proveedor"] ;  ?></td>
	<td class="celPrecio"><?php echo $data["precio"];?></td>
	<td class="celExistencia"><?php echo $data["existencia"];?></td>

	<td class="img_producto"><img src="<?php echo $foto ; ?>" alt="<?php echo $data["descripcion"]  ; ?>"></td>
	
    <?php if ($_SESSION['rol']==1){ ?>
        <td>

    <a class="link_add add_product" product ="<?php echo $data["coproducto"]; ?>" href="#">Agregar</a>
    |
    <a class="link_edit" href="editar_producto.php?id=<?php echo $data["coproducto"]; ?>">Editar</a>
		|
		<a class="link_delete del_product" href="#"  product ="<?php echo $data["coproducto"]; ?>">Eliminar</a>
	</td>
	
	
    <?php }?>
</tr>
       <?php
    }
  }
 ?>






</table>

<div class="paginador">
			<ul>
			<?php 
				if($pagina != 1)
				{
			 ?>
				<li><a href="?pagina=<?php echo 1; ?>&<?php echo $buscar;?>"></a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>&<?php echo $buscar;?>"></a></li>
			<?php 
				}
				for ($i=1; $i <= $total_paginas; $i++) { 
					# code...
					if($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';
					}else{
						echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
					}
				}

				if($pagina != $total_paginas)
				{
			 ?>
				<li><a href="?pagina=<?php echo $pagina + 1; ?>&<?php echo $buscar;?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?> &<?php echo $buscar;?>">>|</a></li>
			<?php } ?>
			</ul>
		</div>

	</section>
	<?php include "includes/footer.php";?>
</body>
</html> 