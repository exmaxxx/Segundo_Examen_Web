<!DOCTYPE html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<title>Crear XML con acceso a datos 3</title>
	</head>
	<body style="background-color: #FFFFCC; color: #800000">
		<img src="imagenes/encabe.png" alt="" >
		<h2>Crear XML con acceso a datos formateando xml</h2>

		<?php
			//obtiene raiz del sitio
            $ruta = $_SERVER["DOCUMENT_ROOT"]."/php_06-main/";

			//Hablitia conexion con el motor de MySql.
      		include_once("codigos/conexion2.inc");

			//define consulta
			$AuxSql =  "select film.film_id, film.title, count(rental.inventory_id) ,sum(payment.amount) ";
			$AuxSql .= "from rental inner join inventory on rental.inventory_id = inventory.inventory_id inner join payment on payment.rental_id = rental.rental_id inner join film on inventory.film_id = film.film_id where rental.rental_date between '2005-05-25' and '2005-05-29'  group by film.film_id limit 10;";

			$Regis = mysqli_query($conex, $AuxSql) or die(mysqli_error($conex));
			//crea vector de datos
			$i = 0;
			while($fila = mysqli_fetch_array($Regis)){
				$codigo[$i] = $fila["film_id"];
				$nombre[$i] = $fila["title"];

				$codpro[$i] = $fila["count(rental.inventory_id)"];
				$nompro[$i] = $fila["sum(payment.amount)"];
				$i++;
			}

			//libera espacio de la consulta
			mysqli_free_result($Regis);

			//impresion de los datos (solo prueba)
			$canti = sizeof($codigo);
			$cate = "";
			for($j=0; $j < $canti; $j++){
			    if($cate != $codigo[$j]){
					print("-----------------------------------------------------------------------------------------------O<br>");
					printf("- Pelicula: %s - %s<br>", ($codigo[$j]),($nombre[$j]));
					print("-----------------------------------------------------------------------------------------------O<br>");
					$cate = $codigo[$j];
				}
				printf("Recaudacion: %s - %s<br>", ($codpro[$j]),($nompro[$j]));

			}
			print("<br>");

			//creacion del documento xml
			$xml = "<?xml version='1.0' encoding='utf-8' ?>";
			$xml .= "<?xml-stylesheet type='text/xsl'  href='esilos/formatos.xsl'?>";
			$xml .= "<informacion>";
			$xml .= "   <generalidades>";
			$xml .= "      <empresa>";
			$xml .= "         <nombre>Univesidad Técnica Nacional</nombre>";
			$xml .= "         <carrera>Tecnologías de la Información</carrera>";
			$xml .= "         <curso>Tecnologías y Sistemas Web2</curso>";
			$xml .= "      </empresa>";
			$xml .= "      <profesor>";
			$xml .= "         <nombre>Edgar Porras Ramírez</nombre>";
			$xml .= "         <experiencia>Estudiante de TI</experiencia>";
			$xml .= "      </profesor>";
			$xml .= "   </generalidades>";
			$xml .= "   <clasificacion>";

			$cate = $codigo[0];
			$Datos[0] = '<categoria>
		                    <codigo>'.$codigo[0].'</codigo>
		                    <nombre>'.($nombre[0]).'</nombre>';
			for($j=0; $j < $canti; $j++){
				if($cate != $codigo[$j]){
					if(isset($Datos[$j])){
						$Datos[$j] .= '</categoria>';
					}else{
						$Datos[$j] = '</categoria>';
					}

					$Datos[$j] .= '<categoria>
		                              <codigo>'.$codigo[$j].'</codigo>
		                              <nombre>'.($nombre[$j]).'</nombre>';

					$cate = $codigo[$j];
				}


				if(isset($Datos[$j])){
			    	$Datos[$j] .= '<articulos>';
			    }else{
			    	$Datos[$j] = '<articulos>';
			    }

			    if(isset($Datos[$j])){
					$Datos[$j] .= '<codart>'.$codpro[$j].'</codart><nomart>'.($nompro[$j]).'</nomart>';
				}else{
					$Datos[$j] = '<codart>'.$codpro[$j].'</codart><nomart>'.($nompro[$j]).'</nomart>';
				}
                
                $Datos[$j] .= '</articulos>';


		        $xml = $xml.$Datos[$j];
			}//fin del for

			$xml .= "      </categoria>";
			$xml .= "   </clasificacion>";
			$xml .= "</informacion>";

			//escribir archivo xml
			$ruta = $ruta."prueba.xml";

			try{
				$archivo = fopen($ruta,"w+");
				fwrite($archivo,$xml);
				fclose($archivo);
			}catch(Exception $e){
				echo "Error:..".$e->getMessage();
			}
		?>

		<a href="Reporte_1.xml">XML Generado</a><br />
		<a href="descargaxml.php">Descargar archivo xml</a>


	</body>
</html>
