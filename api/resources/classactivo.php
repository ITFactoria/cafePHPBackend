<?php
require_once('classconectordb.php'); 

class QRactivo extends conectorDB {
	
// Metodos del QR	
	// Obtiene el ID del QR
	// vclave = valor de la clave del QR
	// Devuelve el idqr
	public function obtenerIdQR($vclave){
		$query="SELECT IF(idqr IS NULL,'0',idqr) AS id FROM qr WHERE clave=$vclave";
		$res = $this->leer($query,0);
		return $res['id'];
	}

	// Obtiene el registro completo del QR
	// vclave = valor de la clave del QR
	// vtar = 0 -> devuelve arreglo; 1 -> devuelve json
	public function obtenerRegQR($vclave,$vtar){
		$query="SELECT * FROM qr WHERE clave=$vclave";
		$res = $this->leer($query,$vtar);
		if ($res['idqr'] == ""){$res['idqr'] = '0';}
		return $res;
	}

	// Ingresa las fechas de compra y activación del QR
	// vidqr = valor del ID del QR
	// tact = COM -> fecha de compra; ACT -> Fecha de activacion
	// devuelve true o false
	public function actualizaFechaQR($vidqr,$tact){
		date_default_timezone_set($this->zonaH);
		$fecha=date("Y-m-d H:i:s");
		$tf="fechaactivacion";
		if ($tact == "COM"){$tf="fechacompra";}
		$set= array("$tf"=>$fecha);
		$cond= array("idqr"=>$vidqr);
		return $this->modificar("qr",$set,$cond);			 
	}

// Metodos de Activos

	// Actualiza latitud y longitud del activo
	// vactivo = id del activo
	// vlat = Latitud
	// vlon	= Longitud
	public function actualizaLatLonActivo($vactivo,$vlat,$vlon){
		$set= array("latitud"=>$vlat,"longitud"=>$vlon);
		$cond= array("idactivo"=>$vactivo);
		return $this->modificar("activos",$set,$cond);			 
	}

// Metodos de Correo y Token
	// Busca el idcorreo y el token de un correo
	// vcorreo = Correo al que se le va a buscar los datos
	// vtar = 0 -> Devuelve un arreglo; 1 -> Devuelve un json
	public function buscaCorreoToken($vcorreo,$vtar){
		$query="SELECT idcorreo,token FROM correos WHERE correo='$vcorreo'";
		$res = $this->leer($query,$vtar);
		if ($res['idcorreo'] == ""){$res['idcorreo'] = '0';}
		if ($res['token'] == ""){$res['token'] = '0';}
		return $res;
	}

	// Crea el Token
	// Devuelve el Token
	public function crearToken(){
		$error = true;
		while ($error){
			$vres = random_int(1000, 9999999);
			$query="SELECT idcorreo FROM correos WHERE token = $vres";
			$res = $this->leer($query,0);
			// Chequeamos si existe el token
			if ($res['idcorreo'] == ""){$error = false;}
		}
		return $vres;
	}

	// Inserta el correo y el token
	// vcorreo = Correo que se va a Crear
	// vtoken = Token del correo
	// Devuelve el idcorreo o 0 -> si el correo es nulo
	public function creaCorreoToken($vcorreo,$vtoken){
		if ($vcorreo != ""){
			$vreg= array("correo"=>$vcorreo,"token"=>$vtoken);
			return $this->insertar("correos",$vreg,1);			 
		}
		else {return 0;}
	}

	// Modifica el Token de un correo
	// vidcorreo = idcorreo al cual se le va a modificar el Token
	// vtoken = Nuevo Token
	// devuelve true o false
	public function modificaToken($vidcorreo,$vtoken){
		$set= array("token"=>$vtoken);
		$cond= array("idcorreo"=>$vidcorreo);
		return $this->modificar("correos",$set,$cond);			 
	}
	
	// Busca los campos relacionados al Token
	// vtoken = Token a validar
	public function buscaToken($vtoken){
		$query="SELECT IF(idcorreo IS NULL,'0',idcorreo) AS id FROM correos WHERE token = $vtoken";
		$res = $this->leer($query,0);
		return $res['id'];
	}
		
// Metodos de IntegraQR
	// Busca un tipo de relacion específico del QR
	// vidqr = valor del ID del QR
	// vtran = VEN -> Vendedor; COM -> Comprador; ADM -> Administrador; FAB -> Fabricante; PRO -> Propietario; TEC -> Tecnico
	// vtar = 0 -> Devuelve un arreglo; 1 -> Devuelve un json
	public function buscaIntegraQR($vidqr,$vtran,$vtar){
		$query="SELECT * FROM integraqr WHERE idactivo=$vidqr and tiporelacion = '$vtran'";
		$res = $this->leer($query,$vtar);
		if ($res['idempresa'] == ""){$res['idempresa'] = "0";}
		if ($res['idcontacto'] == ""){$res['idcontacto'] = "0";}
		if ($res['idcorreo'] == ""){$res['idcorreo'] = "0";}
		return $res;
	}

	// Crea un Integra
	// vactivo = id del activo
	// vcorreo = id del correo
	// vempresa = id de la empresa
	// vcontacto = id del contacto
	// vtipo = Tipo de relacion ---> VEN -> Vendedor; COM -> Comprador; ADM -> Administrador; FAB -> Fabricante; PRO -> Propietario; TEC -> Tecnico
	public function creaIntegra($vactivo,$vcorreo,$vempresa,$vcontacto,$vtipo){
		if ($vactivo != "" && $vcorreo != "" && $vtipo != ""){
			$vreg= array("idactivo"=>$vactivo,"idcorreo"=>$vcorreo,"idempresa"=>$vempresa,"idcontacto"=>$vcontacto,"tiporelacion "=>$vtipo);
			return $this->insertar("integraqr",$vreg,0);			 
		}
		else {return 0;}
	}

	// Busca la Empresa a la que le pertenece el correo
	// vidcorreo = Id del correo
	// Devuelve el idempresa
	public function buscaEmpresaCorreo($vidcorreo){
		$query="SELECT distinct idempresa FROM integraqr WHERE idcorreo= $vidcorreo and idempresa IS NOT NULL";
		$res = $this->leer($query,0);
		$empre = $res['idempresa'];
		if ($empre == ""){$empre = "0";}
		return $empre;
	}
	
	// Busca el Rol de la empresa
	// vactivo = id del activo
	// vcorreo = id del correo
	// vempresa = id de la empresa
	public function buscaRolEmpresa($vactivo,$vcorreo,$vempresa){
		$query="SELECT min(tiporelacion) tipo
					FROM integraqr 
					WHERE idactivo= $vactivo and idcorreo= $vcorreo and idempresa = $vempresa and tiporelacion IN ('ADM','FAB','PRO','TEC')";
		$res = $this->leer($query,0);
		return $res['tipo'];
	}
	
// Metodos de Empresa específicos de activos

	// Actualiza latitud y longitud de la Empresa
	// vactivo = id del activo
	// vlat = Latitud
	// vlon	= Longitud
	public function actualizaLatLonEmpresa($vempresa,$vlat,$vlon){
		$set= array("latitud"=>$vlat,"longitud"=>$vlon);
		$cond= array("idempresa"=>$vempresa);
		return $this->modificar("empresas",$set,$cond);			 
	}

// Metodo para insertar logcafe
	
	public function creaLogCafe($vactivo,$vempresa,$vapl,$vlat,$vlon,$vtoken,$vnota){
		if ($vapl != "" && $vactivo != "" && $vempresa != "" && $vnota != ""){
			$vreg= array("idactivo"=>$vactivo,"idempresa"=>$vempresa,"aplicacion"=>$vapl,"latitud"=>$vlat,"longitud"=>$vlon,"token"=>$vtoken,"nota"=>$vnota);
			return $this->insertar("logcafe",$vreg,0);			 
		}
	}
}


?>