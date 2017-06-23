<?php

namespace app\index\controller;

use think\Controller;

class Lists extends Common
{
    public function index()
    {
        return $this->fetch();
    }
}
