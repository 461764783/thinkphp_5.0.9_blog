<?php

namespace app\common\model;

use houdunwang\arr\Arr;
use think\Model;

class Category extends Model
{
    //申明主键
    protected $pk = 'cate_id';
    //申明要操作的数据表  要写完整的表名
    protected $table = 'blog_cate';

    /*
     * 处理树状结构
     */
    public function getAll()
    {
        //Arr类的用法：https://www.kancloud.cn/houdunwang/hdphp3/215245
        return Arr::tree(db('cate')->order('cate_sort desc,cate_id')->select(), 'cate_name', $fieldPri = 'cate_id', $fieldPid = 'cate_pid');
    }

    /*
     * 添加顶级分类
     */
    public function store($data)
    {
        //执行验证 并添加数据   模型验证   https://www.kancloud.cn/manual/thinkphp5/129355
        $result = $this->validate(true)->save($data);
        if(false === $result){
            // 验证失败 返回错误信息给控制器
            return ['valid'=>0,'msg'=>$this->getError()];
        }else{
            //验证成功 才会添加数据  我们也把验证成功的信息返回给控制器
            return ['valid'=>1,'msg'=>'添加成功'];
        }
    }

    /*
     * 处理所属分类
     */
    public function getCateDate($cate_id)
    {
        //1、首先找到$cate_id的子栏目
        $cate_ids = $this->getSon(db('cate')->select(),$cate_id);
        //2、将自己追加进去
        $cate_ids[] = intval($cate_id);
        //3、找到除了他们之外的数据
        $field = db('cate')->whereNotIn('cate_id',$cate_ids)->select();
        return Arr::tree($field,'cate_name','cate_id','cate_pid');
    }
    /*
     * 找子集
     */
    public function getSon($data,$cate_id)
    {
        static $tmp = [];
        foreach($data as $k =>$v){
            if($cate_id == $v['cate_pid']){
                $tmp[] = $v['cate_id'];
                //递归能寻找出所有的子栏目
                $this->getSon($data,$v['cate_id']);
            }
        }
        return $tmp;
    }
    /*
     * 修改栏目
     */
    public function edit($data)
    {
        //save 执行编辑的时候需要添加第二个参数  即需要编辑的数据的主键 和主键对应的值 这里的主键为cate_id（即$this->pk）而他的值为$data['cate_id']
        $result = $this->validate(true)->save($data,[$this->pk => $data['cate_id']]);
        if(false === $result){
            // 验证失败 返回错误信息给控制器
            return ['valid'=>0,'msg'=>$this->getError()];
        }else{
            //验证成功 才会添加数据  我们也把验证成功的信息返回给控制器
            return ['valid'=>1,'msg'=>'编辑成功'];
        }
    }

    /*
     * 删除栏目
     */
    public function del($cate_id)
    {
        //此处的原理：先找出要删除的栏目的pid  然后把要删除的栏目下的子栏目的 pid设置为要删除栏目的pid  (即把当前要删除的栏目的子栏目往上移动一级)  最后再删除
        //1、获取当前要删除的栏目的cate_pid
        $cate_pid = $this->where('cate_id',$cate_id)->value('cate_pid');
        //halt($cate_pid);
        //2、将当前要删除的$cate_id的子栏目的数据的pid修改为$cate_pid
        $this->where('cate_pid',$cate_id)->update(['cate_pid'=>$cate_pid]);
        //3、执行删除
        if(Category::destroy($cate_id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
}
