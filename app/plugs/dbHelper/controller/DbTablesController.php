<?php

namespace app\plugs\dbHelper\controller;


use app\exception\ErrorCode;
use app\plugs\PlugsBaseController;
use think\facade\Db;

/**
 * Table类
 * Class DbTablesController
 * @package app\plugs\dbHelper\controller
 */
class DbTablesController extends PlugsBaseController
{
    /**
     * 获取数据库表列表
     * @return \think\response\Json|void
     */
    public function get_list()
    {
        try {
            $tables = Db::query('SHOW TABLES');
        }catch (\Exception $exception){
            return $this->send(ErrorCode::DB_EXCEPTION, [$exception->getLine()], $exception->getMessage());
        }
        $list = [];

        foreach ($tables as $value){
            foreach ($value as $item){
                $list[]= ['name'=>$item];
            }
        }
        return $this->send(ErrorCode::SUCCESS,['list'=>$list],'SUCCESS');
    }
}