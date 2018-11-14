<?php

namespace Admin\Controller;

use Service\ResourcesService;

/**
 * 商品管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class GoodsController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 商品列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 参数
		$param = array_merge($_POST, $_GET);

		// 模型对象
		$m = M('Goods');

		// 条件
		$where = $this->GetIndexWhere();

		// 分页
		$number = MyC('admin_page_number');
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/Goods/Index'),
			);
		$page = new \Library\Page($page_param);

		// 获取列表
		$list = $this->SetDataHandle($m->where($where)->limit($page->GetPageStarNumber(), $number)->order('id desc')->select());

		// 是否上下架
		$this->assign('common_goods_is_shelves_list', L('common_goods_is_shelves_list'));

		// 是否首页推荐
		$this->assign('common_is_text_list', L('common_is_text_list'));

		// 参数
		$this->assign('param', $param);

		// 分页
		$this->assign('page_html', $page->GetPageHtml());

		// 数据列表
		$this->assign('list', $list);

		$this->display('Index');
	}

	/**
	 * [SetDataHandle 数据处理]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-29T21:27:15+0800
	 * @param    [array]      $data [商品数据]
	 * @return   [array]            [处理好的数据]
	 */
	private function SetDataHandle($data)
	{
		if(!empty($data))
		{
			foreach($data as &$v)
			{
				// 分类名称
				$category_id = M('GoodsCategoryJoin')->where(['goods_id'=>$v['id']])->getField('category_id', true);
				$category_name = M('GoodsCategory')->where(['id'=>['in', $category_id]])->getField('name', true);
				$v['category_text'] = implode('，', $category_name);

				// 产地
				$v['place_origin_text'] = M('Region')->where(['id'=>$v['place_origin']])->getField('name');

				// 品牌
				$v['brand_name'] = empty($v['brand_id']) ? null : M('Brand')->where(['id'=>$v['brand_id']])->getField('name');

				// 商品url地址
				$v['goods_url'] = HomeUrl('Goods', 'Index', ['id'=>$v['id']]);

				// 创建时间
				$v['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

				// 更新时间
				$v['upd_time'] = date('Y-m-d H:i:s', $v['upd_time']);
			}
		}
		return $data;
	}

	/**
	 * [GetIndexWhere 商品列表条件]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-10T22:16:29+0800
	 */
	private function GetIndexWhere()
	{
		$where = ['is_delete_time'=>0];

		// 模糊
		if(!empty($_REQUEST['keyword']))
		{
			$like_keyword = array('like', '%'.I('keyword').'%');
			$where[] = array(
					'title'		=>	$like_keyword,
					'model'		=>	$like_keyword,
					'_logic'	=>	'or',
				);
		}

		// 是否更多条件
		if(I('is_more', 0) == 1)
		{
			// 等值
			if(I('is_shelves', -1) > -1)
			{
				$where['is_shelves'] = intval(I('is_shelves', 0));
			}
			if(I('is_home_recommended', -1) > -1)
			{
				$where['is_home_recommended'] = intval(I('is_home_recommended', 0));
			}

			// 表达式
			if(!empty($_REQUEST['time_start']))
			{
				$where['add_time'][] = array('gt', strtotime(I('time_start')));
			}
			if(!empty($_REQUEST['time_end']))
			{
				$where['add_time'][] = array('lt', strtotime(I('time_end')));
			}
		}

		return $where;
	}

	/**
	 * [SaveInfo 商品添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 商品信息
		$data = empty($_REQUEST['id']) ? array() : M('Goods')->find(I('id'));

		// 地区信息
		$this->assign('region_province_list', M('Region')->where(['pid'=>0, 'is_enable'=>1])->select());

		// 商品分类
		$field = 'id,name';
		$m = M('GoodsCategory');
		$category = $m->field($field)->where(['is_enable'=>1, 'pid'=>0])->order('sort asc')->select();
		if(!empty($category))
		{
			foreach($category as &$v)
			{
				$two = $m->field($field)->where(['is_enable'=>1, 'pid'=>$v['id']])->order('sort asc')->select();
				if(!empty($two))
				{
					foreach($two as &$vs)
					{
						$vs['items'] = $m->field($field)->where(['is_enable'=>1, 'pid'=>$vs['id']])->order('sort asc')->select();
					}
				}
				$v['items'] = $two;
			}
		}
		$this->assign('category_list', $category);

		// 品牌分类
		$brand_list = M('BrandCategory')->where(['is_enable'=>1])->select();
		if(!empty($brand_list))
		{
			foreach($brand_list as &$v)
			{
				$v['items'] = M('Brand')->field('id,name')->where(['is_enable'=>1, ['brand_category_id'=>$v['id']]])->order('sort asc')->select();
			}
		}
		$this->assign('brand_list', $brand_list);

		// 基础数据处理
		if(!empty($data))
		{
			// 相册
			$data['photo'] = M('GoodsPhoto')->where(['goods_id'=>$data['id'], 'is_show'=>1])->field('id,images,sort')->order('sort asc')->select();

			// 手机详情
			$data['content_app'] = M('GoodsContentApp')->where(['goods_id'=>$data['id']])->field('id,images,content,sort')->order('sort asc')->select();

			// 属性
			$data['attribute'] = $this->GetGoodsAttribute($data['id']);

			// 分类id
			$data['category_ids'] = M('GoodsCategoryJoin')->where(['goods_id'=>$data['id']])->getField('category_id', true);

			// pc详情
			$data['content_web'] = ResourcesService::ContentStaticReplace($data['content_web'], 'get');
		}
		$this->assign('data', $data);

		// 状态
		$this->assign('common_merchant_status', L('common_merchant_status'));

		$this->display('SaveInfo');
	}

	/**
	 * 获取商品属性
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [int]          $goods_id [商品id]
	 * @return  [array]                  [属性]
	 */
	private function GetGoodsAttribute($goods_id)
	{
		$data = M('GoodsAttributeType')->where(['goods_id'=>$goods_id])->field('id,type,name,sort')->order('sort asc')->select();
		if(!empty($data))
		{
			foreach($data as &$v)
			{
				$v['find'] = M('GoodsAttribute')->where(['goods_id'=>$goods_id, 'attribute_type_id'=>$v['id']])->field('id,name,sort')->order('sort asc')->select();
			}
		}
		return $data;
	}

	/**
	 * [Save 商品添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 详情
		$attribute = $this->GetFormGoodsAttributeParams();
		if($attribute['status'] === false)
		{
			$this->ajaxReturn($attribute['msg'], -1);
		}
		
		// 相册
		$photo = $this->GetFormGoodsPhotoParams();
		if($photo['status'] === false)
		{
			$this->ajaxReturn($photo['msg'], -2);
		}

		// 手机端详情
		$content_app =$this->GetFormGoodsContentAppParams();
		if($content_app['status'] === false)
		{
			$this->ajaxReturn($content_app['msg'], -3);
		}

		// 集合主图片
		$images_field = ['file_home_recommended_images'];
		$images = $this->GetGoodsImagesParams($images_field);
		if($images['status'] === false)
		{
			$this->ajaxReturn($images['msg'], -4);
		}

		// 视频
		$video_field = ['file_video'];
		$video = $this->GetGoodsVideoParams($video_field);
		if($video['status'] === false)
		{
			$this->ajaxReturn($video['msg'], -5);
		}

		// 基础数据
		$data = [
			'title'						=> I('title'),
			'title_color'				=> I('title_color'),
			'model'						=> I('model'),
			'place_origin'				=> intval(I('place_origin')),
			'inventory'					=> intval(I('inventory')),
			'inventory_unit'			=> I('inventory_unit'),
			'original_price'			=> round(I('original_price'), 2),
			'price'						=> round(I('price'), 2),
			'give_integral'				=> intval(I('give_integral')),
			'buy_min_number'			=> intval(I('buy_min_number', 1)),
			'buy_max_number'			=> intval(I('buy_max_number', 0)),
			'is_deduction_inventory'	=> intval(I('is_deduction_inventory')),
			'is_shelves'				=> intval(I('is_shelves')),
			'content_web'				=> ResourcesService::ContentStaticReplace($_POST['content_web'], 'add'),
			'images'					=> isset($photo['data'][0]) ? $photo['data'][0] : '',
			'photo_count'				=> count($photo['data']),
			'is_home_recommended'		=> intval(I('is_home_recommended')),
			'home_recommended_images'	=> empty($images['data']['file_home_recommended_images']) ? trim($_POST['home_recommended_images']) : $images['data']['file_home_recommended_images'],
			'brand_id'					=> intval(I('brand_id')),
			'video'						=> empty($video['data']['file_video']['url']) ? trim($_POST['video']) : $video['data']['file_video']['url'],
		];

		// 添加/编辑
		$m = D('Goods');

		// 商品
		$goods_id = I('id');
		$goods = $m->find($goods_id);

		$type = empty($goods) ? 1 : 2;
		if($m->create($_POST, $type))
		{
			// 开启事务
 			$m->startTrans();

			if($type == 1)
			{
				// 添加
				$data['add_time'] = time();
				$goods_id = $m->add($data);
			} else {
				// 更新
				$data['upd_time'] = time();
				if($m->where(['id'=>$goods_id])->save($data) === false)
				{
					// 回滚事务
 					$m->rollback();

					$this->ajaxReturn(L('common_operation_edit_error'), -100);
				}
			}

			// 分类
			$ret = $this->GoodsCategoryInsert(explode(',', I('category_id')), $goods_id);
			if($ret['status'] === false)
			{
				// 回滚事务
 				$m->rollback();

				$this->ajaxReturn($ret['msg'], -101);
			}

			// 属性
			$ret = $this->GoodsAttributeInsert($attribute['data'], $goods_id);
			if($ret['status'] === false)
			{
				// 回滚事务
 				$m->rollback();

				$this->ajaxReturn($ret['msg'], -101);
			}

			// 相册
			$ret = $this->GoodsPhotoInsert($photo['data'], $goods_id);
			if($ret['status'] === false)
			{
				// 回滚事务
 				$m->rollback();

				$this->ajaxReturn($ret['msg'], -101);
			}

			// 手机详情
			$ret = $this->GoodsContentAppInsert($content_app['data'], $goods_id);
			if($ret['status'] === false)
			{
				// 回滚事务
 				$m->rollback();

				$this->ajaxReturn($ret['msg'], -101);
			}

			// 提交事务
			$m->commit();

			// 删除原来的视频
			if(!empty($goods['video']) && !empty($video['data']['file_video']['url']))
			{
				$this->FileDelete($goods['video']);
			}

			// 提示
			if($type == 1)
			{
				$this->ajaxReturn(L('common_operation_add_success'));
			} else {
				$this->ajaxReturn(L('common_operation_edit_success'));
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * 商品短视频
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-08-07
	 * @desc    description
	 * @param   [array]          $data [字段列表]
	 */
	private function GetGoodsVideoParams($data)
	{
		$result = [];
		if(!empty($data))
		{
			// 定义图片目录
			$path = DS.'Public'.DS.'Upload'.DS.'goods_video'.DS.date('Y').DS.date('m').DS.date('d').DS;

			// 类库
			$file_obj = new \Library\FileUpload(['root_path'=>ROOT_PATH, 'path'=>$path]);
			foreach($data as $field)
			{
				if(!empty($_FILES[$field]))
				{
					$ret = $file_obj->Save($field);
					if($ret['status'] !== true)
					{
						return $ret;
					}
					$result[$field] = $ret['data'];
				}
			}
		}
		return ['status'=>true, 'data'=>$result];
	}

	/**
	 * 获取属性参数
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-09
	 * @desc    description
	 */
	private function GetFormGoodsAttributeParams()
	{
		$result = [];
		foreach($_POST as $k=>$v)
		{
			if(substr($k, 0, 9) == 'attribute')
			{
				// key分解
				$key = explode('_', $k);

				// 当前key是否是具体属性数据
				if(in_array('find', $key))
				{
					$result[$key[1]][$key[2]][$key[3]][$key[4]] = $v;
				} else {
					// 属性类型数据
					$result[$key[1]][$key[2]][$key[3]] = $v;
				}
			}
		}
		return ['status'=>true, 'data'=>$result];
	}

	/**
	 * 获取app内容
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-09
	 * @desc    description
	 */
	private function GetFormGoodsContentAppParams()
	{
		// 图像类库
		$images_obj = \Library\Images::Instance(['is_new_name'=>false]);

		// 定义图片目录
		$root_path = ROOT_PATH;
		$path = 'Public'.DS.'Upload'.DS.'goods_app'.DS;
		$date = DS.date('Y').DS.date('m').DS.date('d').DS;

		// 开始处理
		$result = [];
		$name = 'content_app_';
		foreach($_POST AS $k=>$v)
		{
			if(substr($k, 0, 12) == $name)
			{
				$key = explode('_', str_replace($name, '', $k));
				if(count($key) == 2)
				{
					$result[$key[1]][$key[0]] = $v;

					if($key[0] == 'images')
					{
						$images_key = $name.$key[0].'_file_'.$key[1];
						if(isset($_FILES[$images_key]))
						{
							// 文件上传校验
							$error = FileUploadError($images_key);
							if($error !== true)
							{
								return ['status'=>false, 'msg'=>$error];
							}

							// 存储图片
							$temp_all = [
									'tmp_name'	=>	$_FILES[$images_key]['tmp_name'],
									'type'		=>	$_FILES[$images_key]['type']
								];
							$original = $images_obj->GetOriginal($temp_all, $root_path.$path.'original'.$date);
							if(!empty($original))
							{
								// 根据原图再次生成小图
								$compr = $images_obj->GetBinaryCompress($root_path.$path.'original'.$date.$original, $root_path.$path.'compr'.$date, 600);
								$small = $images_obj->GetBinaryCompress($root_path.$path.'original'.$date.$original, $root_path.$path.'small'.$date, 100, 100);

								if(!empty($compr))
								{
									$result[$key[1]][$key[0]] = DS.$path.'compr'.$date.$small;
								} else {
									// 如果图片格式有误，则删除原图片
									$this->FileDelete($path.'original'.$date.$original);
								}
				 			}
						}
					}
				}
			}
		}
		return ['status'=>true, 'data'=>$result];
	}

	/**
	 * 获取商品相册
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @return  [array]          [一维数组但图片地址]
	 */
	private function GetFormGoodsPhotoParams()
	{
		// 原始图片
		if(!empty($_POST['photo']) && is_array($_POST['photo']))
		{
			$result = $_POST['photo'];
		} else {
			$result = [];
		}

		// 开始处理图片存储
		$images_name = 'photo_file';
		if(!empty($_FILES[$images_name]))
		{
			// 定义图片目录
			$root_path = ROOT_PATH;
			$path = 'Public'.DS.'Upload'.DS.'goods_photo'.DS;
			$date = DS.date('Y').DS.date('m').DS.date('d').DS;

			// 图像类库
			$images_obj = \Library\Images::Instance(['is_new_name'=>false]);
			
			// 图片存储处理
			for($i=0; $i<count($_FILES[$images_name]['tmp_name']); $i++)
			{
				// 文件上传校验
				$error = FileUploadError($images_name, $i);
				if($error !== true)
				{
					return ['status'=>false, 'msg'=>$error];
				}

				// 存储图片
				$temp_all = [
						'tmp_name'	=>	$_FILES[$images_name]['tmp_name'][$i],
						'type'		=>	$_FILES[$images_name]['type'][$i],
					];
				$original = $images_obj->GetOriginal($temp_all, $root_path.$path.'original'.$date);
				if(!empty($original))
				{
					// 根据原图再次生成小图
					$compr = $images_obj->GetBinaryCompress($root_path.$path.'original'.$date.$original, $root_path.$path.'compr'.$date, 600);
					$small = $images_obj->GetBinaryCompress($root_path.$path.'original'.$date.$original, $root_path.$path.'small'.$date, 100, 100);

					if(!empty($compr))
					{
						$result[] = DS.$path.'compr'.$date.$small;
					} else {
						// 如果图片格式有误，则删除原图片
						$this->FileDelete($path.'original'.$date.$original);
					}
	 			}
			}
		}

		return ['status'=>true, 'data'=>$result];
	}

	/**
	 * 商品图片集合处理
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-08-07
	 * @desc    description
	 * @param   [array]          $data [字段列表]
	 */
	private function GetGoodsImagesParams($data)
	{
		$result = [];
		if(!empty($data))
		{
			// 定义图片目录
			$root_path = ROOT_PATH;
			$path = 'Public'.DS.'Upload'.DS.'goods_images'.DS;
			$date = DS.date('Y').DS.date('m').DS.date('d').DS;

			// 图像类库
			$images_obj = \Library\Images::Instance(['is_new_name'=>false]);

			foreach($data as $field)
			{
				if(!empty($_FILES[$field]))
				{
					$file = $_FILES[$field];

					// 文件上传校验
					$error = FileUploadError($field);
					if($error !== true)
					{
						return ['status'=>false, 'msg'=>$error];
					}
					
					// 存储图片
					$temp_all = [
							'tmp_name'	=>	$file['tmp_name'],
							'type'		=>	$file['type'],
						];
					$original = $images_obj->GetOriginal($temp_all, $root_path.$path.'original'.$date);
					if(!empty($original))
					{
						// 根据原图再次生成小图
						$compr = $images_obj->GetBinaryCompress($root_path.$path.'original'.$date.$original, $root_path.$path.'compr'.$date, 600);
						$small = $images_obj->GetBinaryCompress($root_path.$path.'original'.$date.$original, $root_path.$path.'small'.$date, 100, 100);

						if(!empty($compr))
						{
							$result[$field] = DS.$path.'compr'.$date.$small;
						} else {
							// 如果图片格式有误，则删除原图片
							$this->FileDelete($path.'original'.$date.$original);
						}
		 			}
		 		}
			}
		}

		return ['status'=>true, 'data'=>$result];
	}

	/**
	 * 商品分类添加
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [array]          $data     [数据]
	 * @param   [int]            $goods_id [商品id]
	 * @return  [array]                    [boolean | msg]
	 */
	private function GoodsCategoryInsert($data, $goods_id)
	{
		$m = M('GoodsCategoryJoin');
		$m->where(['goods_id'=>$goods_id])->delete();
		if(!empty($data))
		{
			foreach($data as $category_id)
			{
				$temp_category = [
					'goods_id'		=> $goods_id,
					'category_id'	=> $category_id,
					'add_time'		=> time(),
				];
				if($m->add($temp_category) <= 0)
				{
					return ['status'=>false, 'msg'=>'商品分类添加失败'];
				}
			}
		}
		return ['status'=>true, 'msg'=>'添加成功'];
	}

	/**
	 * 商品手机详情添加
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [array]          $data     [数据]
	 * @param   [int]            $goods_id [商品id]
	 * @return  [array]                    [boolean | msg]
	 */
	private function GoodsContentAppInsert($data, $goods_id)
	{
		$m = M('GoodsContentApp');
		$m->where(['goods_id'=>$goods_id])->delete();
		if(!empty($data))
		{
			foreach(array_values($data) as $k=>$v)
			{
				$temp_content = [
					'goods_id'	=> $goods_id,
					'images'	=> $v['images'],
					'content'	=> $v['text'],
					'sort'		=> $k,
					'add_time'	=> time(),
				];
				if($m->add($temp_content) <= 0)
				{
					return ['status'=>false, 'msg'=>'手机详情添加失败'];
				}
			}
		}
		return ['status'=>true, 'msg'=>'添加成功'];
	}

	/**
	 * 商品相册添加
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [array]          $data     [数据]
	 * @param   [int]            $goods_id [商品id]
	 * @return  [array]                    [boolean | msg]
	 */
	private function GoodsPhotoInsert($data, $goods_id)
	{
		$m = M('GoodsPhoto');
		$m->where(['goods_id'=>$goods_id])->delete();
		if(!empty($data))
		{
			foreach($data as $k=>$v)
			{
				$temp_photo = [
					'goods_id'	=> $goods_id,
					'images'	=> $v,
					'is_show'	=> 1,
					'sort'		=> $k,
					'add_time'	=> time(),
				];
				if($m->add($temp_photo) <= 0)
				{
					return ['status'=>false, 'msg'=>'相册添加失败'];
				}
			}
		}
		
		return ['status'=>true, 'msg'=>'添加成功'];
	}

	/**
	 * 商品属性添加
	 * @author   Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2018-07-10
	 * @desc    description
	 * @param   [array]          $data     [数据]
	 * @param   [int]            $goods_id [商品id]
	 * @return  [array]                    [boolean | msg]
	 */
	private function GoodsAttributeInsert($data, $goods_id)
	{
		$attr = M('GoodsAttribute');
		$attr_type = M('GoodsAttributeType');

		// 删除原来的数据
		$attr->where(['goods_id'=>$goods_id])->delete();
		$attr_type->where(['goods_id'=>$goods_id])->delete();

		// 开始添加数据
		if(!empty($data))
		{
			foreach(array_values($data) as $k=>&$v)
			{
				$attribute_type_data = [
					'goods_id'	=> $goods_id,
					'type'		=> $v['data']['type'],
					'name'		=> $v['data']['name'],
					'sort'		=> $k,
					'add_time'	=> time(),
				];
				$attribute_type_id = $attr_type->add($attribute_type_data);
				if($attribute_type_id <= 0)
				{
					return ['status'=>false, 'msg'=>'属性类型添加失败'];
				}
				if(!empty($v['find']))
				{
					foreach(array_values($v['find']) as $ks=>$vs)
					{
						$attribute_data = [
							'attribute_type_id'		=> $attribute_type_id,
							'goods_id'				=> $goods_id,
							'name'					=> $vs['name'],
							'sort'					=> $ks,
						];
						$attr_id = $attr->add($attribute_data);
						if($attr_id <= 0)
						{
							return ['status'=>false, 'msg'=>'属性添加失败'];
						}
					}
				}
			}
		}
		return ['status'=>true, 'msg'=>'添加成功'];
	}

	/**
	 * [Delete 商品删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function Delete()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数处理
		$id = I('id');

		// 删除数据
		if(!empty($id))
		{
			// 商品模型
			$m = M('Goods');

			// 商品是否存在
			$data = $m->where(array('id'=>$id))->getField('id');
			if(empty($data))
			{
				$this->ajaxReturn(L('common_data_no_exist_error'), -2);
			}

			// 删除商品
			if($m->where(array('id'=>$id))->save(['is_delete_time'=>time()]) !== false)
			{
				$this->ajaxReturn(L('common_operation_delete_success'));
			} else {
				$this->ajaxReturn(L('common_operation_delete_error'), -100);
			}
		} else {
			$this->ajaxReturn(L('common_param_error'), -1);
		}
	}

	/**
	 * [StatusShelves 上下架状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusShelves()
	{
		// 参数
		if(empty($_POST['id']) || !isset($_POST['state']))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 数据更新
		if(M('Goods')->where(array('id'=>I('id')))->save(array('is_shelves'=>I('state'))))
		{
			$this->ajaxReturn(L('common_operation_edit_success'));
		} else {
			$this->ajaxReturn(L('common_operation_edit_error'), -100);
		}
	}

	/**
	 * [StatusHomeRecommended 是否首页推荐状态更新]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-12T22:23:06+0800
	 */
	public function StatusHomeRecommended()
	{
		// 参数
		if(empty($_POST['id']) || !isset($_POST['state']))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 数据更新
		if(M('Goods')->where(array('id'=>I('id')))->save(array('is_home_recommended'=>I('state'))))
		{
			$this->ajaxReturn(L('common_operation_edit_success'));
		} else {
			$this->ajaxReturn(L('common_operation_edit_error'), -100);
		}
	}
}
?>