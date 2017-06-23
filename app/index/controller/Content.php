<?php

namespace app\index\controller;

use think\Controller;

class Content extends Common
{
    public function index()
    {
        return $this->fetch();
    }
}
