<?php
// Clase para conectar a BD con los metodos CRUD basicos

class conectorDB{
	private $conector;
	private $servidorBD="localhost";
	private $usuarioBD="root";
	private $claveBD = "Admin2020";
	private $nombreBD="cafe";
	private $url="http://149.56.130.61:8080/cafev3/";
	public $zonaH;
	
	function __construct($vzona){
		$this->zonaH = $vzona;
		$this->conectarBD();
	}
	public function conectarBD(){
		$this->conector = mysqli_connect($this->servidorBD, $this->usuarioBD, $this->claveBD, $this->nombreBD);
		mysqli_query($this->conector, "SET NAMES 'utf8'");
		$tz = (new DateTime('now', new DateTimeZone($this->zonaH)))->format('P');
		mysqli_query($this->conector, "SET TIME_ZONE='$tz'");

		if(mysqli_connect_error()){
			die("Conexión a la base de datos falló ".mysqli_connect_error().mysqli_connect_errno());
		}
	}

	public function devuelveUrl(){
		return $this->url;
	}
// Método para insertar registros en Tabla 
// Arreglo ---> array("campostring"=>'cadena1',"camponumero"=>numero2,"camponulo"=>'NULL',"campoimagen",idimagen)
// did ---> 0 -> No retorna id del insert; 1 -> Si retorna el id del insert
	public function insertar($tabla, $arreglo,$did){
		$campos = implode(",",array_keys($arreglo));
		$valores = implode("', '",array_values($arreglo));
		$valoresF= str_replace("'NULL'","NULL",$valores);
		$sql="INSERT INTO $tabla ($campos) VALUES ('$valoresF')";
		$resultado = mysqli_query($this->conector, $sql) or die ("Error del query ($sql): ".$conector->error);
		if($resultado){
			if ($did == 1){return mysqli_insert_id($this->conector);}
			else {return true;}
		}
		else{return false;}
	}

//Devuelve el último id del insert secuencial
   public function ultimoID(){
      return mysqli_insert_id($this->conector);
   }
   
   
// Método para modificar registros de Tabla
// arregloset y arreglocondicion ---> array("campostring"=>'cadena1',"camponumero"=>numero2,"camponulo"=>NULL,"campoimagen",idimagen)
	public function modificar($tabla, $arregloset, $arreglocondicion){
		$i=0;
		foreach($arregloset as $indice=>$valor) {
			$datoset[$i] = $indice." = '".$valor."'";
			$i++;
		}
		$resultadoset = implode(", ",$datoset);
		$i=0;
		foreach($arreglocondicion as $indice=>$valor) {
			$datocond[$i] = $indice." = '".$valor."'";
			$i++;
		}
		$resultadocond = implode(" AND ",$datocond);

		$sql="UPDATE $tabla SET $resultadoset WHERE $resultadocond ";
		$resultado = mysqli_query($this->conector, $sql) or die ("Error del query ($sql): ".$conector->error);

		if($resultado){return "Ok";}
		else{return $conector->error;}
	}

// Método para eliminar registros en Tabla
// arreglocondicion ---> array("campostring"=>'cadena1',"camponumero"=>numero2,"camponulo"=>NULL,"campoimagen",idimagen)
	public function eliminar($tabla, $arreglocondicion){
		$i=0;
		foreach($arreglocondicion as $indice=>$valor) {
			$datocond[$i] = $indice." = '".$valor."'";
			$i++;
		}
		$resultadocond = implode(" AND ",$datocond);

		$sql="DELETE FROM $tabla WHERE $resultadocond ";
		$resultado = mysqli_query($this->conector, $sql) or die ("Error del query ($sql): ".$conector->error);

		if($resultado){return "Ok";}
		else{return $conector->error;}
	}

// Método para leer registros de Tabla
// dv ---> 0 -> devuelve arreglo; 1 -> devuelve json
	public function leer($sql,$dv){
		$resultado = mysqli_query($this->conector, $sql) or die ("Error del query ($sql): ".$conector->error);

		while ($fila = mysqli_fetch_assoc($resultado)){
			$arreglo[] = $fila;
		}
		if ($dv == 1){return json_encode($arreglo);}
		else {
			if (count($arreglo) > 1){return $arreglo;}
			else {return $arreglo[0];}
		}
	}
	
	public function cerrar(){
		mysqli_close($this->conector);
	}
}








?>