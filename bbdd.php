<?php
include_once("admin/settings.php");

class BBDD {
	var $link = 0;
	
	function BBDD( ) {
		global $servidor, $usuario, $password, $bdatos;

		$this->link = mysql_connect( $servidor, $usuario, $password );
		if ( !( $this->link ) ) die( "BBDD::BBDD - Error de conexión a la BBDD" );
		if ( !mysql_select_db( $bdatos, $this->link ) ) die ( "BBDD::BBDD - Error al elegir la base de datos" );
	}
	
	// Devuelve el último id obtenido al insertar
	function exec( $sql ) {
		$query = mysql_query($sql, $this->link);
		if (!$query) return -1; //die("No se ha podido realizar la consulta");
		$id = mysql_insert_id();
		return $id;
	}
	
	// Devuelve una tupla
	function query( $sql ) {
		$query = mysql_query( $sql, $this->link );
		if ( !$query ) die( "BBDD::query - No se ha podido realizar la consulta [".$sql."]" );
		if ( is_bool( $query ) ) return $query;
		$result = mysql_fetch_assoc( $query );
		return $result;		
	}

	function queryArray( $sql ) {
		$query = mysql_query( $sql, $this->link );
		if ( !$query ) die( "BBDD::queryArray - No se ha podido realizar la consulta [".$sql."]" );
		$result = array( );
		while( $row = mysql_fetch_array( $query ) ) {
			$result[] = $row;
		}
		return $result;
	}
	
	function queryValue($sql) {
		$query = mysql_query( $sql, $this->link );	
		if ( !$query ) return null; 
		$result = mysql_fetch_array( $query );
		return $result[0];
	}
	
	function connected( ) {
		return $this->link != 0;
	}
}

?>