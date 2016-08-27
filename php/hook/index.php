<?php

header("Content-type:text/html;charset=utf-8");

// 更改插件状态
if (isset($_GET['change'])) {
    // 获取到配置项
    $config=include './plugin/'.$_GET['change'].'/config.php';
    // 如果是开启 那就关闭 如果是关闭 则开启
    $config['status']=$config['status']==1 ? 0: 1;
    // 将更改后的配置项写入到文件中
    $str="<?php \r\n return ".var_export($config,true).';';
    file_put_contents('./plugin/'.$_GET['change'].'/config.php', $str);
    header('Location:./');
}

//传递数据以易于阅读的样式格式化后输出
function p($data){
    // 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}



class Hook{
    public static function add($name,$func){
        $GLOBALS['hookList'][$name][]=$func;
    }

    public static function run($name,$params=null){
        foreach ($GLOBALS['hookList'][$name] as $k => $v) {
            call_user_func($v,$params);
        }
    }
}

class Test{
    public function index(){
        // 获取全部插件
        $pluginList=scandir('./plugin/');
        // 循环插件 // 排除. ..
        foreach ($pluginList as $k => $v) {
            
            if ($v=='.' || $v=='..') {
                unset($pluginList[$k]);
            }
        }

        // 插件管理
        foreach ($pluginList as $k => $v) {
            // 获取配置项
            $config=include './plugin/'.$v.'/config.php';
            $word=$config['status']==1 ? '点击关闭' : '点击开启';
            echo $config['title'].'<a href="./index.php?change='.$v.'">'.$word.'</a><br />';
        }
        echo '<hr>';
        // 输出插件内容
        foreach ($pluginList as $k => $v) {
            // 获取配置项
            $config=include './plugin/'.$v.'/config.php';
            if ($config['status']==1) {
                include './plugin/'.$v.'/index.php';
                // 运行插件
                Hook::run($v);
            }
        }
                





    }
}



$test=new Test();
$test->index();





