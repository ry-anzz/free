<?php
$usuario = 'root';
$host = 'localhost';
$password = '';
$bd = 'avaliafisio';


$conexao = new mysqli($host,$usuario,$password,$bd);

//  if($conexao ->connect_error){    
//      echo 'erro na conexao';
//   }
//  else{
//      echo 'conectado';
//   }

// ?>