<?php

function redirect($to){
    return header("location:{$to}");
}
