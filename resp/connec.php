<?php

	function pgSql_db()
	{
  $HostDB="pgsql93.hub.org";
    $PortDB="5432";          
    $UserDB="3455_moxup"; 
    $PassDB="vmjf2013";
    $NameDB="3455_moxup";
		$dbPg = pg_connect("host=$HostDB port=$PortDB dbname=$NameDB user=$UserDB password=$PassDB");		 

	
return $dbPg;
	}
	function pgSql_db2()
	{

	  $HostDB="pgsql92.hub.org";
    $PortDB="5432";          
    $UserDB="3455_admin_db"; 
    $PassDB="npr26c";
    $NameDB="3455_gis";
		$dbPg = pg_connect("host=$HostDB port=$PortDB dbname=$NameDB user=$UserDB password=$PassDB");		 

	
return $dbPg;
	}
?>
