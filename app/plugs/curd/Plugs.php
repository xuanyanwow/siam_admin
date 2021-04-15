<?php

namespace app\plugs\curd;


use app\plugs\PlugsBase;
use app\plugs\PlugsConfig;
use think\facade\Route;

class Plugs extends PlugsBase
{
    
    
    public function get_config(): PlugsConfig
    {
        $config = new PlugsConfig();
        $config->setName("curd");
        $config->setIcon("");
        $config->setHandleModule(["admin","plugs"]);// 只有admin,plugs模块才会执行初始化
        $config->setHomeView("plugs/curd/index");
        return $config;
    }
    
    public function install()
    {
        file_put_contents(__DIR__.'\install.lock',date('Y-m-d H:i:s'));
    }
    
    public function remove()
    {
    
    }
    
    public function init()
    {
        
        // 在这里注入路由[api] 等事件
        Route::get('plugs/curd/index', function () {
            return $this->pre_render_file(__DIR__ . "/view/index.html");
        });
    
        Route::group(function () {
            Route::get('plugs/curd/create_curd', 'PlugsCurdController@create_curd');
            Route::get('plugs/curd/update_notes', 'PlugsCurdController@update_notes');
            Route::get('plugs/curd/create_html', 'PlugsCurdController@create_html');
        })->prefix('\app\plugs\curd\controller\\');
        
    }
}