<?php

namespace app\admin\controller;

use app\common\model\Category;
use think\Controller;

class Article extends Controller
{
    protected $db;
    protected function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Article();
    }
    public function index()
    {
        $field = $this->db->getAll();
        $this->assign('field',$field);
        return $this->fetch();
    }
    /**
     * 文章添加
     */
    public function store()
    {
        if(request()->isPost())
        {
            $res = $this->db->store(input('post.'));
            if($res){
                $this->success($res['msg'],'index');exit;
            }else{
                $this->error($res['msg']);
            }
        }
        //获取分类数据
        $cateDate = (new Category())->getAll();
        //获取标签数据
        $tagDate = db('tag')->select();
        $this->assign('cateDate',$cateDate);
        $this->assign('tagDate',$tagDate);
        return $this->fetch();
    }

    /**
     * 文章回收站
     */
    public function recycle()
    {

        return $this->fetch();
    }

}
