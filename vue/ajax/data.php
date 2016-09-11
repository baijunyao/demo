<?php


$data=array(
    'category'=>'vue入门学习',
    'article'=>array(
        array(
            'title'=>'对照着jquery来学vue.js系列之安利篇',
            'author'=>'李磊'
            ),
        array(
            'title'=>'对照着jquery来学vue.js系列之ajax获取数据',
            'author'=>'韩梅梅'
            )
        )
    );
echo json_encode($data);