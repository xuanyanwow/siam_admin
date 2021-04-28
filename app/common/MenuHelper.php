<?php


namespace app\common;

use app\model\SystemModel;

class MenuHelper
{
    public $auth_list = [];

    /**
     * 列表转化树形结构
     * @param $list
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list_to_tree($list)
    {
        $newList = [];
        foreach ($list as $key => $t){
            $newList[$t['auth_id']] = $t;
        }

        $this->auth_list = $newList;

        $System = SystemModel::where(['id' => 1])->field('auth_order')->find()->toArray();
        $order  = json_decode($System['auth_order'], TRUE);
        return $this->test($order);
    }

    /**
     * @param $order
     * @return array
     */
    private function test($order)
    {
        $return = [];
        foreach ($order as $key => $value) {
            // 未有权限
            if (empty($this->auth_list[$value['id']])) {
                continue;
            }

            $tem = $this->auth_list[$value['id']];
            if (isset($value['child'])) {
                $tem['child'] = $this->test($value['child']);
            }
            $return[] = $tem;
        }
        return $return;
    }

    /**
     * 列表转化html
     */
    public function tree_to_html($list)
    {
        $html = '';
        foreach ($list as $key => $value){
            if (!empty($value['child'])){

                $html .= <<<html
<li class="layui-nav-item">
    <a href="javascript:;" lay-tips="{$value['auth_name']}"  lay-direction="2">
    <i class="layui-icon layui-icon-home"></i>
        <cite>{$value['auth_name']}</cite>
    </a>
    <dl class="layui-nav-child">
html;

                foreach ($value['child'] as $v){
                    if (!empty($v['child'])){
                        // 三级
                        $html .= <<<html
<dd>
    <a href="javascript:;">{$v['auth_name']}</a>
    <dl class="layui-nav-child">
html;
                        foreach ($v['child'] as $threev){
                            $temUrl = url($threev['auth_rules']);
                            $html.= <<<html
<dd><a lay-href="{$temUrl}">{$threev['auth_name']}</a></dd>
html;
                        }
                        $html.="</dl>";
                        // 三级结束
                    }else{
                        $temUrl = url($v['auth_rules']);
                        $html .= <<<html
<dd>
    <a lay-href="{$temUrl}">
    {$v['auth_name']}
    </a>
</dd>
html;
                    }
                }

                $html.="</dl></li>";
                // 二级结束

            }else{
                // 一级的
                $temUrl = url("{$value['auth_rules']}");

                $html .= <<<html
<li data-name="{$value['auth_name']}" class="layui-nav-item">
    <a lay-href="{$temUrl}" lay-tips="{$value['auth_name']}" lay-direction="2">
        <i class="layui-icon layui-icon-home"></i>
        <cite>{$value['auth_name']}</cite>
    </a>
</li>
html;
            }
        }

        return $html;
    }
}