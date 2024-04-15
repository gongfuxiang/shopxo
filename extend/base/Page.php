<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace base;

/**
 * 分页驱动
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Page
{
	private $page;
	private $page_size;
	private $total;
	private $bt_number;
	private $where;
	private $not_fields;
	private $page_total;
	private $url;
	private $html;
	private $page_join;
	private $tips_msg;
	private $is_next_bt;

	/**
	 * 构造方法
	 * @param [int]    $params['page'] 				[页码]
	 * @param [int]    $params['page_size / number'][每页数据条数]
	 * @param [int]    $params['total'] 			[数据总数]
	 * @param [int]    $params['bt_number'] 		[分页显示按钮个数]
	 * @param [array]  $params['where'] 			[额外条件(键值对)]
	 * @param [array]  $params['not_fields'] 		[不参与条件拼接的字段]
	 * @param [string] $params['url'] 				[url地址]
	 * @param [int]    $params['is_next_bt'] 		[是否展示上/下一页按钮]
	 */
	public function __construct($params = [])
	{
		$this->page = max(1, isset($params['page']) ? intval($params['page']) : 1);
		$this->page_size = empty($params['page_size']) ? (empty($params['number']) ? 10 : intval($params['number'])) : intval($params['page_size']);
		$this->total = max(1, isset($params['total']) ? intval($params['total']) : 1);
		$this->bt_number = isset($params['bt_number']) ? intval($params['bt_number']) : 2;
		$this->where = (isset($params['where']) && is_array($params['where'])) ? $params['where'] : '';
		$this->not_fields = (!empty($params['not_fields']) && is_array($params['not_fields'])) ? $params['not_fields'] : [];
		$this->url = isset($params['url']) ? $params['url'] : '';
		$this->tips_msg = empty($params['tips_msg']) ? '' : trim($params['tips_msg']);
		$this->is_next_bt = (!isset($params['is_next_bt']) || $params['is_next_bt'] == 1) ? 1 : 0;
		$this->page_total = 1;
		$this->html = '';
		// 插件基础参数不参与条件
		$this->not_fields[] = 'pluginsname';
		$this->not_fields[] = 'pluginscontrol';
		$this->not_fields[] = 'pluginsaction';

		/* 参数设置 */
		$this->SetParem();
	}

	/**
	 * 参数设置
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2023-01-19
	 * @desc    description
	 */
	private function SetParem()
	{
		/* 防止超出最大页码数 */
		$this->page_total = ceil($this->total/$this->page_size);
		if($this->page > $this->page_total) $this->page = $this->page_total;

		/* url是否包含问号 */
		$state = stripos($this->url, '?');

		/* 额外条件url设置 */
		if(!empty($this->where) && is_array($this->where))
		{
			$tmp = true;
			foreach($this->where as $k=>$v)
			{
				if(!in_array($k, $this->not_fields) && !is_array($v))
				{
					// 分页不参与url拼接
					if($k == 'page')
					{
						continue;
					}

					// 数据处理
					$k = empty($k) ? $k : htmlspecialchars($k);
					$v = empty($v) ? $v : htmlspecialchars($v);

					// 拼接参数
					if($tmp)
					{
						$this->url .= ($state === false) ? '?' : '&';
						$this->url .= $k.'='.$v;
						$tmp = false;
					} else {
						$this->url .= '&'.$k.'='.$v;
					}
				}
			}
			$this->page_join = ($tmp == false) ? '&' : (($state === false) ? '?' : '&');
		} else {
			$this->page_join = ($state === false) ? '?' : '&';
		}
	}

	/**
	 * 获取生成好的分页代码
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2023-01-19
	 * @desc    description
	 */
	public function GetPageHtml()
	{
		$before_disabled = ($this->page > 1) ? '' : ' am-disabled';
		$after_disabled = ($this->page > 0 && $this->page < $this->page_total) ? '' : ' am-disabled';

		$this->html .= '<ul class="am-pagination-container am-pagination am-pagination-right pagination-margin-top">';
		$this->html .= '<li class="first-before-page-submit '.$before_disabled.'">';
		$this->html .= '<a href="javascript:;" data-url="'.$this->url.$this->page_join.'page=1" data-value="1" class="am-icon-angle-double-left"></a>';
		$this->html .= '</li>';

		// 上一页
		if($this->is_next_bt == 1)
		{
			$this->html .= '<li class="prev-before-page-submit '.$before_disabled.'">';
			$this->html .= '<a href="javascript:;" data-url="'.$this->url.$this->page_join.'page='.($this->page-1).'" data-value="'.($this->page-1).'" class="am-radius am-icon-angle-left"></a>';
			$this->html .= '</li>';
		}

		$this->html .= $this->GetButtonNumberHtml();

		// 下一页
		if($this->is_next_bt == 1)
		{
			$this->html .= '<li class="next-after-page-submit '.$after_disabled.'">';
			$this->html .= '<a href="javascript:;" data-url="'.$this->url.$this->page_join.'page='.($this->page+1).'" data-value="'.($this->page+1).'" class="am-radius am-icon-angle-right"></a>';
			$this->html .= '</li>';
		}

		$this->html .= '<li class="last-after-page-submit '.$after_disabled.'">';
		$this->html .= '<a href="javascript:;" data-url="'.$this->url.$this->page_join.'page='.$this->page_total.'" data-value="'.$this->page_total.'" class="am-radius am-icon-angle-double-right"></a>';
		$this->html .= '</li>';

		$this->html .= '<span class="current-page-input">';
		$this->html .= '<span class="am-margin-left-sm">'.MyLang('common_extend.base.page.each_page_name').'</span>';
		$this->html .= '<input type="text" name="page_size" min="1" data-is-clearout="0" class="am-form-field am-inline-block am-text-center am-margin-horizontal-xs am-radius pagination-input" value="'.$this->page_size.'" data-type="size" data-default-value="'.$this->page_size.'" data-url="'.str_replace(['&page_size='.$this->page_size, 'page_size='.$this->page_size.'&'], '', $this->url).$this->page_join.'page_size=" onclick="this.select()" />';
		$this->html .= '<span>'.MyLang('common_extend.base.page.page_strip').'</span>';
		$this->html .= '</span>';

		$this->html .= '<span class="to-page-input">';
		$this->html .= '<span class="am-margin-left-sm">'.MyLang('common_extend.base.page.jump_to_text').'</span>';
		$this->html .= '<input type="text" name="page" min="1" data-is-clearout="0" class="am-form-field am-inline-block am-text-center am-margin-horizontal-xs am-radius pagination-input" value="'.$this->page.'" data-type="page" data-default-value="1" data-value-max="'.$this->page_total.'" data-url="'.$this->url.$this->page_join.'page=" onclick="this.select()" />';
		$this->html .= '<span>'.MyLang('common_extend.base.page.page_unit').'</span>';
		$this->html .= '</span>';

		$this->html .= '<div>';
		$this->html .= '<span>'.MyLang('common_extend.base.page.data_total', ['total'=>$this->total]).'</span>';
		$this->html .= '<span class="am-margin-left-sm">'.MyLang('common_extend.base.page.page_total', ['total'=>$this->page_total]).'</span>';
		if(!empty($this->tips_msg))
		{
			$this->html .= '<span class="am-margin-left-sm">'.$this->tips_msg.'</span>';
		}
		$this->html .= '</div>';
		$this->html .= '</ul>';

		return $this->html;
	}

	/**
	 * 获取button显示个数的html
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2023-01-19
	 * @desc    description
	 * @return  按钮个数html代码
	 */
	private function GetButtonNumberHtml()
	{
		$html_before = '';
		$html_after = '';
		$html_page = '<li class="am-active"><a class="am-radius">'.$this->page.'</a></li>';
		if($this->bt_number > 0)
		{
			/* 前按钮 */
			if($this->page > 1)
			{
				$total = ($this->page-$this->bt_number < 1) ? 1 : $this->page-$this->bt_number;
				for($i=$this->page-1; $i>=$total; $i--)
				{
					$html_before = '<li><a href="javascript:;" data-url="'.$this->url.$this->page_join.'page='.$i.'" data-value="'.$i.'" class="am-radius">'.$i.'</a></li>'.$html_before;
				}
			}

			/* 后按钮 */
			if($this->page_total > $this->page)
			{
				$total = ($this->page+$this->bt_number > $this->page_total) ? $this->page_total : $this->page+$this->bt_number;
				for($i=$this->page+1; $i<=$total; $i++)
				{
					$html_after .= '<li><a href="javascript:;" data-url="'.$this->url.$this->page_join.'page='.$i.'" data-value="'.$i.'" class="am-radius">'.$i.'</a></li>';
				}
			}
		}
		return $html_before.$html_page.$html_after;
	}

	/**
	 * 获取分页起始值
	 * @author  Devil
	 * @blog    http://gong.gg/
	 * @version 1.0.0
	 * @date    2023-01-19
	 * @desc    description
	 */
	public function GetPageStarNumber()
	{
		return intval(($this->page-1)*$this->page_size);
	}
}
?>