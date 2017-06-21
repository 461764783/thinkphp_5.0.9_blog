<?php

namespace app\system\controller;

use think\Controller;
use think\Db;

class Component extends Controller
{
    //上传文件
    public function uploader()
    {
        //halt($_POST);
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . DS . 'uploads');
        if ($info) {
            $data = [
                'name' => input('post.name'),
                'filename' => $info->getFilename(),
                'path' => str_replace('\\', '/', 'uploads' . DS . $info->getSaveName()),
                'extension' => $info->getExtension(),
                'createtime' => time(),
                'size' => $info->getSize(),
            ];
            Db::name('attachment')->insert($data);
            echo json_encode(['valid' => 1, 'message' => str_replace('\\', '/', '/uploads' . DS . $info->getSaveName())]);

        } else {
            // 上传失败获取错误信息
            echo json_encode(['valid' => 0, 'message' => $file->getError()]);
        }
    }


    //获取文件列表
    public function filesLists()
    {
        $db = Db::name('attachment')
            ->whereIn('extension', explode(',', input('post.extensions')))
            ->order('id DESC');
        $Res = $db->paginate(32);
        $data = [];
        if ($Res->toArray()) {
            foreach ($Res as $k => $v) {
                $data[$k]['createtime'] = date('Y/m/d', $v['createtime']);
                $data[$k]['size'] = $v['size'];
                $data[$k]['url'] = str_replace('\\', '/', '/' . $v['path']);
                $data[$k]['path'] = str_replace('\\', '/', '/' . $v['path']);;
                $data[$k]['name'] = $v['name'];
            }
        }
        echo json_encode(['data' => $data, 'page' => $Res->render() ?: '']);
    }

}
