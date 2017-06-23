<?php

namespace app\index\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    public function __construct(Request $request=null)
    {
        parent::__construct($request);
        //1、读取配置项的数据
        $webset = $this->loadWebSet();
        $this->assign('_webset',$webset);

        //2、获取顶级分类
        $cateData = $this->loadCateData();
        $this->assign('_cateData',$cateData);

        //3、获取全部分类
        $allcatedata = $this->loadAllCateData();
        $this->assign('_allcatedata',$allcatedata);

        //4、获取标签数据
        $tagData = $this->loadTagData();
        $this->assign('_tagdata',$tagData);

        //5、最新文章
        $articleData = $this->loadArticleData();
        $this->assign('_articleData',$articleData);

        //6、友情链接
        $linkData = $this->loadLinkData();
        $this->assign('_linkdata',$linkData);
    }

    private function loadWebSet()
    {
        return db('webset')->column('webset_value','webset_name');
    }

    private function loadCateData()
    {
        return db('cate')->where('cate_pid',0)->order('cate_sort desc')->select();
    }

    private function loadAllCateData()
    {
        return db('cate')->order('cate_sort desc,cate_id desc')->select();
    }

    private function loadTagData()
    {
        return db('tag')->select();
    }
    private function loadArticleData()
    {
        return db('article')->order('sendtime desc')->limit(3)->field('arc_id,arc_title,sendtime')->select();
    }
    private function loadLinkData()
    {
        return db('link')->order('link_sort desc')->select();
    }
}
