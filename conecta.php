<?php

define("HOST",'localhost');
define("BD",'d02');
define("USER_BD",'root');
define("PASS_BD",'');

function conecta(){
    $con = new mysqli(HOST, USER_BD, PASS_BD, BD);
    return $con;
}

?>