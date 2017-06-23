<?php

namespace app\common\model;

use think\Model;

class Article extends Model
{
    protected $pk = 'arc_id';
    protected $table = 'blog_article';
    protected $auto = ['admin_id'];
    protected $insert = ['sendtime'];
    protected $update = ['updatetime'];

    protected function setAdminIdAttr()
    {
        return session('admin.admin_id');
    }

    protected function setSendTimeAttr()
    {
        return time();
    }

    protected function setUpdateTimeAttr()
    {
        return time();
    }

    /**
     * 获取文章列表数据
     * @$is_recycle  为1时调用回收站里的数据  为2时调用非回收站的数据
     */
    public function getAll($is_recycle=2)
    {
        // 这里查出文章的数据 需要关联到文章的分类表   关联表的方法：https://www.kancloud.cn/manual/thinkphp5/118083
        return db('article')->alias('a')                 //alias给表取别名
            ->join('__CATE__ c', 'a.cate_id=c.cate_id')   //关联blog_cate表  __CATE__ 代表blog_cate的表名
            ->where('a.is_recycle',$is_recycle)
            ->field('a.arc_id,a.arc_title,a.sendtime,a.arc_sort,a.arc_author,c.cate_name')   //获取指定的字段
            ->order('a.arc_sort desc,a.sendtime desc,a.arc_id desc')                         //对数据进行排序
            ->paginate(10);
    }

    /*
     * 添加文章
     */
    public function store($data)
    {
        //处理文章标签验证
        if (!isset($data['tag'])) {
            return ['valid' => 0, 'msg' => '请选择文章标签'];
        }
        //验证文章数据及添加文章数据
        $result = $this->validate(true)->allowField(true)->save($data);
        if ($result) {
            //TAG标签中间表数据添加
            foreach ($data['tag'] as $v) {
                $tagData = [
                    'arc_id' => $this->arc_id,
                    'tag_id' => $v,
                ];
                (new ArcTag())->save($tagData);
            }
            return ['valid' => 1, 'msg' => '添加成功'];
        } else {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }

    /*
     * 编辑
     */

    public function edit($data)
    {
        //处理文章标签验证
        if (!isset($data['tag'])) {
            return ['valid' => 0, 'msg' => '请选择文章标签'];
        }
        //验证文章数据及执行更新
        $result = $this->validate(true)->allowField(true)->save($data,[$this->pk=>$data['arc_id']]);
        if($result){
            //先将原有的标签数据删除  再进行添加
            (new ArcTag())->where('arc_id',$data['arc_id'])->delete();
            foreach ($data['tag'] as $v) {
                $tagData = [
                    'arc_id' => $this->arc_id,
                    'tag_id' => $v,
                ];
                (new ArcTag())->save($tagData);
            }
            return ['valid' => 1, 'msg' => '修改成功'];
        }else{
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }


    /*
     * 排序
     */
    public function changeSort($data){
        $result = $this->validate([
            'arc_sort'=>'require|between:1,9999'
        ],[
            'arc_sort.require' =>'请输入排序',
            'arc_sort.between' => '排序需要1-9999之间'
        ])->save($data,[$this->pk,$data['arc_id']]);
        if($result){
            return ['valid' => 1, 'msg' => '操作成功'];
        }else{
            return ['valid' => 0, 'msg' => $this->getError()];
        }
    }

    /**
     * 删除
     */
    public function del($arc_id){
        if(Article::destroy($arc_id)){
            (new ArcTag())->where('arc_id',$arc_id)->delete();
            return ['valid' => 1, 'msg' => '删除成功'];exit;
        }else{
            return ['valid' => 0, 'msg' => '删除失败'];exit;
        }
    }
}
