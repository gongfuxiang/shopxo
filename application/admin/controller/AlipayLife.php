<?php
namespace app\admin\controller;

use app\service\AlipayLifeService;

/**
 * 生活号管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AlipayLife extends Common
{
    /**
     * 构造方法
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:39:08+0800
     */
    public function __construct()
    {
        // 调用父类前置方法
        parent::__construct();

        // 登录校验
        $this->Is_Login();

        // 权限校验
        $this->Is_Power();
    }

    /**
     * [Index 生活号列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
    public function Index()
    {
        // 参数
        $params = input();

        // 分页
        $number = MyC('admin_page_number', 10, true);

        // 条件
        $where = AlipayLifeService::AlipayLifeListWhere($params);

        // 获取总数
        $total = AlipayLifeService::AlipayLifeTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  url('admin/alipaylife/index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'     => $page->GetPageStarNumber(),
            'n'     => $number,
            'where' => $where,
            'field' => 'a.*',
        );
        $data = AlipayLifeService::AlipayLifeList($data_params);
        $this->assign('data_list', $data['data']);

        // 是否上下架
        $this->assign('common_goods_is_shelves_list', lang('common_goods_is_shelves_list'));

        // 生活号分类
        $alipay_life_category = AlipayLifeService::AlipayLifeCategoryList(['field'=>'id,name']);
        $this->assign('alipay_life_category', $alipay_life_category['data']);

        // 参数
        $this->assign('params', $params);
        return $this->fetch();
    }

    /**
     * [SaveInfo 添加/编辑页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-14T21:37:02+0800
     */
    public function SaveInfo()
    {
        // 参数
        $params = input();

        // 数据
        if(!empty($params['id']))
        {
            // 获取列表
            $data_params = array(
                'm'     => 0,
                'n'     => 1,
                'where' => ['a.id'=>intval($params['id'])],
                'field' => 'a.*',
            );
            $data = AlipayLifeService::AlipayLifeList($data_params);

            if(!empty($data['data'][0]))
            {
                // 获取分类关联数据
                $category_ids = AlipayLifeService::AlipayLifeCategoryIds(['where'=>['alipay_life_id'=>$data['data'][0]['id']]]);
                 $data['data'][0]['category_ids'] = $category_ids['data'];

                $this->assign('data', empty($data['data'][0]) ? [] : $data['data'][0]);
            }
        }

        // 是否上下架
        $this->assign('common_goods_is_shelves_list', lang('common_goods_is_shelves_list'));

        // 生活号分类
        $alipay_life_category = AlipayLifeService::AlipayLifeCategoryList(['field'=>'id,name']);
        $this->assign('alipay_life_category', $alipay_life_category['data']);

        // 参数
        $this->assign('params', $params);

        // 编辑器文件存放地址
        $this->assign('editor_path_type', 'alipay_life');

        return $this->fetch();
    }

    /**
     * [Save 生活号保存]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Save()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        $ret = AlipayLifeService::AlipayLifeSave($params);
        return json($ret);
    }

    /**
     * [Delete 生活号删除]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-25T22:36:12+0800
     */
    public function Delete()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        $params['user_type'] = 'admin';
        $ret = AlipayLifeService::AlipayLifeDelete($params);
        return json($ret);
    }

    /**
     * [StatusUpdate 状态更新]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2017-01-12T22:23:06+0800
     */
    public function StatusUpdate()
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 开始处理
        $params = input();
        $params['alipay_life_id'] = isset($params['id']) ? $params['id'] : 0;
        if(isset($params['state']))
        {
            $params['status'] = $params['state'];
        }
        $ret = AlipayLifeService::LifeStatus($params);
        return json($ret);
    }
}
?>