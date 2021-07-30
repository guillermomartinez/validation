<?php
class Validation
{
	public $data = [];
	public $rules = [];
	public $errors = [];
	public $fields = [
		'nombres' => 'Nombres',
		'apellidos' => 'Apellidos',
		'documento' => 'Documento',
		'fecha' => 'Fecha',
		'fecha_nacimiento' => 'Fecha de nacimiento',
		'edad' => 'Edad',
		'telefono' => 'Teléfono',
		'email' => 'Email',
		'email_confirm' => 'Email confirmar',
		'consulta' => 'Consulta',
		'otros_documentos' => 'Otros documentos',
		'politicas_privacidad' => 'Politicas de privacidad',
		'conformidad' => 'Conformidad',
		'distrito' => 'Distrito',
		'horario' => 'Horario',
		'direccion' => 'Dirección',
	];

	public function getExtension($nameFile){
		$extension = substr( $nameFile, ( strrpos($nameFile, '.') + 1 ) ) ;
		$extension = strtolower( $extension ) ;
		return $extension;
	}
	public function humanFilesize($size, $precision = 2) {
		$units = array('kB','MB','GB','TB','PB','EB','ZB','YB');
		$step = 1024;
		$i = 0;
		while (($size / $step) > 0.9) {
			$size = $size / $step;
			$i++;
		}
		return round($size, $precision).' '.$units[$i];
	}
	public function validationResultByField($field,$valor,$regla_config,$campos)
	{

		$regla_nombre = $regla_config[0];
		$parametro = (isset($regla_config[1])) ? $regla_config[1] : '';
		if ($regla_nombre=='required') {
			if(!empty($valor)){
				return true;
			}else{
				return "$field es obligatorio.";
			}
		}elseif ($regla_nombre=='numeric') {
			if(is_numeric($valor)){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}elseif ($regla_nombre=='min') {
			$numero = (int) $valor;
			if(empty($valor) || $numero>=$parametro){
				return true;
			}else{
				return "$field debe ser mayor o igual a $parametro.";
			}
		}elseif ($regla_nombre=='max') {
			$numero = (int) $valor;
			if(empty($valor) || $numero<=$parametro){
				return true;
			}else{
				return "$field debe ser mayor o igual a $parametro.";
			}
		}elseif ($regla_nombre=='minlenght') {
			$n = strlen($valor);
			if(empty($valor) || $n>=$parametro){
				return true;
			}else{
				return "$field debe ser mayor o igual a $parametro.";
			}
		}elseif ($regla_nombre=='maxlenght') {
			$n = strlen($valor);
			if(empty($valor) || $n<=$parametro){
				return true;
			}else{
				return "$field no debe ser mayor a $parametro.";
			}
		}elseif ($regla_nombre=='regex') {
			$matches = [];
			preg_match($parametro, $valor, $matches);
			if(empty($valor) || count($matches)>0){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}elseif ($regla_nombre=='date_format') {
			$d = \DateTime::createFromFormat($parametro, $valor);
			if( empty($valor) || ($d && $d->format($parametro) == $valor) ){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}elseif ($regla_nombre=='time_format') {
			$parametros = str_split($parametro);
			$parametro = implode(':',$parametros);
			$ahora = new \DateTime();
			$d = \DateTime::createFromFormat('Y-m-d '.$parametro, $ahora->format('Y-m-d').' '.$valor);
			if( empty($valor) || ($d && $d->format($parametro) == $valor) ){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}elseif ($regla_nombre=='file_required') {
			if(isset($valor['tmp_name']) && $valor['tmp_name']!=""){
				return true;
			}else{
				return "$field es obligatorio.";
			}
		}elseif ($regla_nombre=='file') {
			if($valor['tmp_name']=="" || is_uploaded_file($valor['tmp_name'])){
				return true;
			}else{
				return "$field no se pudo subir.";
			}
		}elseif ($regla_nombre=='file_size') {
			if($valor['tmp_name']==""){
				return true;
			}elseif (isset($valor['name']) && isset($valor['size'])) {
				if ($valor['size'] <= $parametro * 1024) {
					return true;
				} else {
					return "$field maximo permitido ".$this->humanFilesize($parametro);
				}
			}
		}elseif ($regla_nombre=='file_ext') {
			if($valor['tmp_name']==""){
				return true;
			}elseif (isset($valor['name']) && isset($valor['size'])) {
				$name = $valor['name'];
				$ext = $this->getExtension($name);
				if (in_array($ext, explode(',',$parametro))) {
					return true;
				} else {
					return "$field solo se permite $parametro";
				}
			}
		}elseif ($regla_nombre=='files_required') {
			if(isset($valor['tmp_name'][0]) && strlen($valor['tmp_name'][0])>0){
				return true;
			}else{
				return "$field es obligatorio.";
			}
		}elseif ($regla_nombre=='files') {
			$i = 0;
			foreach ($valor['tmp_name'] as $key => $value) {
				if(is_uploaded_file($valor['tmp_name'][$key])){
					$i++;
				}
			}
			if ( (isset($valor['tmp_name'][0]) && strlen($valor['tmp_name'][0])==0) || count($valor['tmp_name']) == $i ) {
				return true;
			} else {
				return "$field no se pudo subir.";
			}
		}elseif ($regla_nombre=='files_size') {
			$i = 0;
			foreach ($valor['tmp_name'] as $key => $value) {
				if($valor['size'][$key] <= $parametro * 1024 ){
					$i++;
				}
			}
			if ( (isset($valor['tmp_name'][0]) && strlen($valor['tmp_name'][0])==0) || count($valor['tmp_name']) == $i) {
				return true;
			} else {
				return "$field maximo permitido ".$this->humanFilesize($parametro);
			}
		}elseif ($regla_nombre=='files_ext') {
			$i = 0;
			foreach ($valor['tmp_name'] as $key => $value) {
				$name = $valor['name'][$key];
				$ext = $this->getExtension($name);
				if(in_array($ext, explode(',',$parametro))){
					$i++;
				}
			}
			if ( (isset($valor['tmp_name'][0]) && strlen($valor['tmp_name'][0])==0) || count($valor['tmp_name']) == $i) {
				return true;
			} else {
				return "$field solo se permite $parametro";
			}
		}elseif ($regla_nombre=='email') {
			if(empty($valor) || filter_var($valor, FILTER_VALIDATE_EMAIL)){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}elseif ($regla_nombre=='confirmed') {
			if(isset($campos[$parametro]) && $valor == $campos[$parametro]){
				return true;
			}else{
				return "$field no coincide.";
			}
		}elseif ($regla_nombre=='accepted') {
			if(empty($valor) || $valor == $parametro){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}elseif ($regla_nombre=='required_unless') {
			$valor2 = $campos[$parametro];
			if(empty($valor2) && empty($valor)){
				$res = "$field o $parametro es obligatorio";
				$res = str_replace($parametro,$this->fields[$parametro],$res);
				return $res;
			}else{
				return true;
			}
		}elseif ($regla_nombre=='array') {
			if(is_array($valor)){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}elseif ($regla_nombre=='array_min') {
			if(is_array($valor) && count($valor)>=$parametro && $valor[0]!=""){
				return true;
			}else{
				return "$field no tiene un formato válido.";
			}
		}
	}
	public function validation($campos=[],$rules=[])
	{

		$this->errors = [];
		foreach ($rules as $field => $value) {
			$regla_by_field = explode("|", $value);
			foreach ($regla_by_field as $key => $regla) {
				$regla_config = explode(":", $regla);
				$regla_nombre = $regla_config[0];
				$parametro = (isset($regla_config[1])) ? $regla_config[1] : '';
				$error = $this->validationResultByField($field,$campos[$field],$regla_config,$campos);
				if ($error!==true && !is_null($error)) {
					if (!array_key_exists($field, $this->errors)) {
						$this->errors[$field] = '';
					}
					$this->errors[$field] = $error;
					break;
				}
			}
		}
		foreach ($this->errors as $key => $value) {
			if (array_key_exists($key,$this->fields)) {
				$this->errors[$key] = str_replace($key,$this->fields[$key],$value);
			}
		}
		return $this->errors;
	}
	public static function make($data=[],$rules=[],$fields=[])
	{
		$validate = new Validation();
		if (count($fields)>0) {
			$validate->fields = $fields;
		}
		if (count($data)>0) {
			$validate->data = $data;
			$validate->rules = $rules;
		}
		$validate->validation($validate->data,$validate->rules);
		return $validate;
	}
	public function fails()
	{
		if (count($this->errors)>0) {
			return true;
		}else{
			return false;
		}
	}
	public function passed()
	{
		if (count($this->errors)>0) {
			return false;
		}else{
			return true;
		}
	}
}
?>
