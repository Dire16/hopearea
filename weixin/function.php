<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 2017/10/17
 * Time: 9:59
 */
function debug($val,$dump =false ,$exit =true){
    if($dump) {
        $func = 'var_dump';
    }
    else{
        $func=(is_array($val) || is_object($val)) ? 'print_r' :'printf';
    }
    header("Content-type:text/html;charset=utf-8");
    echo '<pre>debug out:<hr />';
    $func($val);
    echo '</pre>';
    if($exit) exit;
}