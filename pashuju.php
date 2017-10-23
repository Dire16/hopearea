<?php
/**
 * Created by PhpStorm.
 * User: zz
 * Date: 2017/10/12
 * Time: 18:16
 */
//include 'simple_html_dom.php';
require 'phpQuery.php';
require 'QueryList.php';
use QL\QueryList;
//$html = file_get_html('http://www.cc8.cc/api.php?mod=js&bid=1190');
//foreach($html->find('img') as $element)
//        echo $element->src . '<br>';
////找到所有链接
//    foreach($html->find('a') as $element)
//        echo $element->href . '<br>';
$html='http://www.cc8.cc/api.php?mod=js&bid=1190';
$data = QueryList::Query($html,array(
    'image' => array('img','src')
))->getData(function($item){
    return $item['image'];
});

$data1 = QueryList::Query($html,array(
    'a' => array('a','href')
))->getData(function($item){
    return $item['a'];
});

$data2 = QueryList::Query($html,array(
    'span' => array('span','text')
))->getData(function($item){
    return $item['span'];
});

$returnarray=array();
for($i=0;$i<count($data);$i++)
{
    $returnarray[$i]['title']=$data2[$i];
    $returnarray[$i]['image']=$data[$i];
    $returnarray[$i]['target']=$data1[$i];
}
echo json_encode($returnarray,JSON_UNESCAPED_UNICODE);
?>
