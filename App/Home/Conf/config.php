<?php
return array(
    'URL_HTML_SUFFIX' => '',//URL路径是否带后缀名
	'TMPL_PARSE_STRING' => array(
        '__JS__' => __ROOT__ . '/Public/js',
        '__CSS__' => __ROOT__ . '/Public/css'
    ),
    
    'WEB_SITE_STATUS' => "N",//站点维护提示
    
        // /* 开启路由功能 */
        // 'URL_ROUTER_ON'   => true,
        // 'URL_ROUTE_RULES'=>array(
        //     //动态路由开始
        //     'news/:pinyinKey' => '/Home/Index/articleList/',
        // ),
);