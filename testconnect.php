<?php

echo "confirm : 1<br/><br/>";

// oracle
//$conn = oci_connect('usr_jidsrv01', 'dywcD6pK','10.102.8.139:1522/jidd01');
$easy_connect = '(DESCRIPTION = (ENABLE = BROKEN)'.
    '(ADDRESS_LIST ='.
    '  (ADDRESS = (PROTOCOL = TCP)(HOST = rec2devdb01jid)(PORT = 1522))'.
    '  (ADDRESS = (PROTOCOL = TCP)(HOST = rec2devdb01jid)(PORT = 1523))'.
    '  (LOAD_BALANCE = OFF)'.
    '  (FAILOVER = ON))'.
    '(CONNECT_DATA = (SERVICE_NAME = jidd01)(SERVER = DEDICATED)) )';

echo $easy_connect."<br/>";

$conn = oci_connect('usr_jidsrv01', 'dywcD6pK',$easy_connect);
//$conn = oci_connect('usr_jidsrv01', 'dywcD6pK', 'rec2devdb01jid:1522/jidd01');


if (!$conn) {
   
    echo "no<br/>";
    $e = oci_error();
    echo $e;
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
else
{
 echo "run<br/>";
}

$stmt = oci_parse( $conn, "SELECT SYSDATE FROM DUAL" );
oci_execute( $stmt );
$row = oci_fetch_array( $stmt );
echo $row['SYSDATE'];
oci_close( $conn );

/*oci_execute($array);

while($row=oci_fetch_array($array))

{

echo $row[0]." ".$row[1]."<br>";

}*/

// mysql
//$conn=mysqli_connect("103.11.74.19","k4664082_ibai","1qaZXsw2!","k4664082_ibai") or die('Could not connect: ' . mysqli_error($conn));

?>