<?php
session_start(); // Senza questo non salva le sessioni
// http://www.sciax2.it/forum/php-mysql/login-sessioni-ajax-jquery-194098.html

include_once("./decibel.v1.php");

function login(){
  /* $u='asdasd'; */
  /* $p=md5(trim('asdasd')); */
  $u=$_POST['user'];
  $p=md5($_POST['pass']);
  $a=new stdClass();
  $a->answer=null; // Inizializzazione oggetto di risposta
  $g=new decibel('127.0.0.1','root','password','test'); // Connessione al database
  $t='form'; // table
  $ifuser=$g->select(COUNTALL,$t)->where('username="'.$u.'"')
    ->query()->values()->last[0][0];
  
  if($ifuser){ // L'utente esiste (diverso da zero)?

    $ifpass=$g->select('password',$t)->where('username="'.$u.'"')
      ->query()->values()->last[0][0];
    
    if($ifpass==$p){ // La password corrisponde a quella dell'utente
      $_SESSION['user'] = $u; // Salvo la sessione dell'username
      $_SESSION['pass'] = $p; // Salvo la sessione della password
      $a->answer='info';
    }else{
      $a->answer='warn';
    }
  }else{
    $a->answer='err';
  }
  echo json_encode($a); //->answer;
  return;
}


function private_area(){
if (empty($_SESSION['user'])){ // verifico che esista la sessione di autenticazione
  header("Location: ./cine.html");
  //  echo json_encode(["user"=>"not identified"]);
  exit;
}
/* echo json_encode(["user"=>"ciccio"]); */
echo json_encode($_SESSION);

}

function logout(){
  session_destroy();
  //  header("Location: ./cine.html");
  exit;
}


////////// MAIN //////////

if(!isset($what))
  $what=$_GET['what'];

if($what=='login')
  login();

if($what=='private_area')
 private_area();

if($what=='logout')
 logout();


?>
