<?php

$idusuario = isset($_SESSION[SESSION_LOGIN]->idusuario) ? $_SESSION[SESSION_LOGIN]->idusuario:null;
$colaborador = isset($_SESSION[SESSION_LOGIN]->colaborador) ? $_SESSION[SESSION_LOGIN]->colaborador:null;
define("IDUSUARIO", $idusuario);
define("NIVEL", $colaborador);