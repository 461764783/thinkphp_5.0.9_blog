<?php

namespace app\admin\controller;

use app\common\model\Category;
use think\Controller;

class Article extends Common
{
    protected $db;
    protected function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Article();
    }
    public function index()
    {
        $field = $this->db->getAll(2);
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
            if($res['valid']){
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
     * 编辑文章
     */
    public function edit(){
        $arc_id = input('param.arc_id');
        if(request()->isPost())
        {
            $res = $this->db->edit(input('post.'));
            if($res['valid']){
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

        //获取旧数据
        $oldDate = db('article')->find($arc_id);

        //获取标签id
        $tag_ids = db('arc_tag')->where('arc_id',$arc_id)->column('tag_id');

        $this->assign('tag_ids',$tag_ids);
        $this->assign('oldDate',$oldDate);
        return $this->fetch();
    }


    /**
     * 修改排序
     */
    public function changeSort(){
        if(request()->isAjax()){
            $res = $this->db->changeSort(input('post.'));
            if($res['valid']){
                $this->success($res['msg'],'index');exit;
            }else{
                $this->error($res['msg']);
            }
        }
    }

    /**
     * 删除文章到回收站
     */
    public function delToRecycle()
    {
        $arc_id = input('param.arc_id');
        if($this->db->save(['is_recycle'=>1],['arc_id'=>$arc_id])){
            $this->success('操作成功','index');exit;
        }else{
            $this->error('操作失败');exit;
        }
    }

    /*
     * 文章回收站
     */
    public function recycle()
    {
        $field = $this->db->getAll(1);
        $this->assign('field',$field);

        return $this->fetch();
    }
    /*
     * 从回收恢复数据
     */
    public function outToRecycle(){
        $arc_id = input('param.arc_id');
        if($this->db->save(['is_recycle'=>2],['arc_id'=>$arc_id])){
            $this->success('操作成功','index');exit;
        }else{
            $this->error('操作失败');exit;
        }
    }
    /*
     * 从回收站删除
     */
    public function del(){
        $arc_id = input('get.arc_id');
        $res = $this->db->del($arc_id);
        if($res['valid']){
            $this->success('操作成功','index');exit;
        }else{
            $this->error('操作失败');exit;
        }
    }

}
