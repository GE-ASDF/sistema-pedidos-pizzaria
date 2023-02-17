<?php

function formatDate($data){
    $newData = strtotime($data);
    $dateFormated = date('d/m/Y', $newData);
    return $dateFormated;
}