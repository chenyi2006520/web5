<?php 
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {

    //Controller中第一个运行的方法，也可看作初始化1
    public function _initialize() {
        // header('Content-type:text/html;charset=utf-8');
        // if (C('WEB_SITE_STATUS') == 'N') {
        //     $this->assign("message", '网站维护中，暂时无法访问');
        //     $this->error();
        // }
    }
}