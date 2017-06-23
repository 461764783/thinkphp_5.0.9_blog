<?php
namespace app\index\controller;

use think\Controller;

class Index extends Common
{
    public function index()
    {
        $articleDate = db('article')
            ->alias('a')
            ->join('__CATE__ c','a.cate_id = c.cate_id')
            ->where('a.is_recycle','2')
            ->order('sendtime desc')
            ->select();

        //先查询出所有的tag标签放到articleDate里面  然后再帅选
        foreach($articleDate as $k => $v){
            $articleDate[$k]['tags'] = db('arc_tag')->alias('at')
                ->join('__TAG__ t','at.tag_id = t.tag_id')
                ->where('at.arc_id',$v['arc_id'])
                ->field('t.tag_name,t.tag_id')
                ->select();
        }
        $this->assign('articledata',$articleDate);
        return $this->fetch();
    }
}
