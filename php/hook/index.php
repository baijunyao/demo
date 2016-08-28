<?php

header("Content-type:text/html;charset=utf-8");

// 更改插件状态
if (isset($_GET['change'])) {
    // 获取到配置项
    $config=include './plugin/plugin'.substr($_GET['change'],-1).'/config.php';
    // 如果是开启 那就关闭 如果是关闭 则开启
    $config['status']=$config['status']==1 ? 0: 1;
    // 将更改后的配置项写入到文件中
    $str="<?php \r\n return ".var_export($config,true).';';
    file_put_contents('./plugin/'.$_GET['change'].'/config.php', $str);
    header('Location:./');
}

// 插件类
class Hook{
    // 注册添加插件
    public static function add($name,$func){
        $GLOBALS['hookList'][$name][]=$func;
    }

    // 执行插件
    public static function run($name,$params=null){
        foreach ($GLOBALS['hookList'][$name] as $k => $v) {
            call_user_func($v,$params);
        }
    }
}

class Test{
    public function index(){
        // 用户注册成功

        // 获取全部插件
        $pluginList=scandir('./plugin/');
        // 循环插件 // 排除. ..
        foreach ($pluginList as $k => $v) {
            
            if ($v=='.' || $v=='..') {
                unset($pluginList[$k]);
            }
        }
        echo "简易后台管理<hr>";
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

        // 前往网站首页
    }
}


$test=new Test();
$test->index();
