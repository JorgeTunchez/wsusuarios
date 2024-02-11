<?php 

function getConexion(){
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "api_rest";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        return $conn;
    }
}

function executeQuery($strQuery){
    if( $strQuery!='' ){
        $conn = getConexion();
        $result = mysqli_query($conn, $strQuery);
        mysqli_close($conn);
        return $result;
    }
}

function debugLine(){
    print "linea #".__LINE__."\n";
}

function preformato($objeto){
    print "<pre>";
    print_r($objeto);
    print "</pre>";
}

?>