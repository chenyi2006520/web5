<?php 
namespace Home\Controller;
use Think\Controller;
use Overtrue\Pinyin;
class ArticleController extends Controller {

    // public function handlesordata()
    // {
    //     $thisTime = date("Y-m-d H:m:s", time());        
    //     $keywordModel = M("keyword");
    //     $keyList = $keywordModel->query("CALL `sp_test001`();");
    //     // $keyList = $keywordModel->query("SET @row_number = 0;SET @customer_no = '';SELECT @row_number := CASE WHEN  @customer_no = k_pinyinname  THEN  @row_number + 1  ELSE  1 END   AS  num, @customer_no := k_pinyinname  as    CustomerNumber,k_pinyinname,k_sort,k_NewsName,k_md5name FROM b_keyword ORDER BY  k_pinyinname limit 0,100;");
    //     pp($keyList);
    // }

    //定时更新数据
    public function Index() {
        $thisTime = date("Y-m-d H:m:s", time());
        $this->thisTime = $thisTime;
        $this->constantCount = M("constantlyword")->count();
        $this->ArticleTitleCount = M("keyword")->count();
        $this->totalArticleCount = M("article")->count();
        
        $update = F("UpdateTime","","./Data/");
        $updateTime = $update['insertTime'];
        if ($updateTime) {
            $timeValue = time() - $updateTime;
            $this->time2Units = time2Units($timeValue);
            $this ->hourtime = $timeValue/(60*60);
            $LastGetFile = F("LastGetFile","","./Data/");
            $temp001 = $LastGetFile['insertTime'];
            $tempLastGetFile = time() - $temp001;
            $this ->tempLast001 = $tempLastGetFile/(60*60);  //最后更新时间距离现在多少小时，也大于零的小数记录                   
            $this -> LastGetFile = time2Units($tempLastGetFile);
              
            $LastGetFileContent = F("LastGetFileContent","","./Data/");
            $temp002 = $LastGetFileContent['insertTime'];
            $tempLastGetFileContent = time() - $temp002;   
            $this -> tempLast002 = $tempLastGetFileContent/(60*60);  //最后更新时间距离现在多少小时，也大于零的小数记录       
            $this -> LastGetFileContent = time2Units($tempLastGetFileContent);            
        }
        $this->display();
        // ob_implicit_flush(true);
        // echo str_pad(" ", 256);
        // ignore_user_abort(); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
        // set_time_limit(0); // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        // $interval=1; // 每隔10秒运行
        // $i = 1;
        // do{
        //     $authTime = $i % 7200;
        //     if($authTime == 0)
        //     {
        //         $this -> getkeywords();
        //         $this -> getArticleTitleAndFile();
        //         $this -> getArticleFileContent();
        //     }
        //     echo  $i++ ." <br/>";
        //     flush();
        //     sleep($interval);// 等待10秒
        // }while(true);
    }

    public function indextwo() {
        echo TranslateValue("刘芸吧");
        die;
        $tasg = M('constantlyword');
        $tagData = $tasg->select();
        foreach($tagData as $key) {
            $arr = array('b_pinyinKey' => TranslateValue($key["b_string"]));
            $tasg->where(array('b_id' => $key['b_id']))->save($arr);
        }
    }


    //获得时时的微博热门搜索
    public function getKeywords() {
        set_time_limit(0);
        $stime = microtime(true);

        $url = ('http://s.weibo.com/top/summary?cate=realtimehot');
        $html = getUrlContent($url);
        $final = strip_tags($html, '<p>');
        $final = str_replace(" ", "", $final);
        $final = str_replace("hot!", "", $final);
        $final = str_replace("new!", "", $final);
        //preg_match_all('/star_name\\\">(.*?)\\\n<pclass/', $final, $n);

        $tagModel = M('constantlyword');

        //清空数据库
        //$tagModel -> execute("truncate b_constantlyword");
        $tag = array();
        //取得名字和热度所在的同一数据
        preg_match_all('/star_name\\\">(.*?)rank_long/', $final, $n);
        foreach($n[0] as $key) {
            preg_match_all('/star_name\\\">(.*?)\\\n<pclass/', $key, $name); //获得关键字
            preg_match_all('/star_num\\\">(.*?)\\\n<pclass/', $key, $num); //获得热度值
            // pp($num);
            // die;
            $star_name = unicode2utf8($name[1][0]);
            $arrayRP = array("热" => "", "荐" => "", "新" => "", );
            $star_name = strtr($star_name, $arrayRP);
            
            //echo mb_strlen($star_name).'<br/>';

            $star_num = $num[1][0];
            $pinyinkey = TranslateValue($star_name);
            //避免返回的拼音为空,且长度小于的才收录，考虑到有中英文混合的时候，所以使用mb_strlen
            if ($pinyinkey != "" && $pinyinkey && (mb_strlen($star_name) <=5 )) {
                $tag[] = array('b_string' => $star_name, 'b_pinyinKey' => $pinyinkey, 'b_addtime' => time(), 'b_num' => $star_num);
            }

        }
        // pp($tag);
        // die;
        //储存时时热门搜索关键词
        $tagModel->addAll($tag);
        F("UpdateTime",array("insertTime"=>time()),"./Data/");      
        redirect("http://ba.cc");
        
        // $etime = microtime(true);
        // $total = $etime - $stime; //计算差值
        // $this->total = $total;
        // $this->display("getKeywords");
    }


    //获得微博热门搜索词在搜索引擎里面的关键字列表，并处理列表页面关于文章和文章所在的url，最后通过url获取文章内容，利用MD5加密生成唯一文件名称并创建文件，
    public function getArticleTitleAndFile($pinyinKey = "0") {
        set_time_limit(0);
        $stime = microtime(true);
        // $dayUnix = strtotime("today"); //今天时间0点的时间戳
        $tempLastUpdate =  F("LastGetFile","","./Data/"); //获取上一次更新时间
        $dayUnix =  $tempLastUpdate['insertTime'];
        
        //获取当前数据库里面今天的热门关键词
        $map['b_addtime'] = array('gt', $dayUnix); //大于今天0点时间的关键词
        $map2['b_num'] = array('gt', 0); //搜索热度大于0的关键词，只在自动处理的时候使用

        //如果有指定处理的关键字b_pinyinKey，就查询指定的b_pinyinKey,入口只有/Home/Index/articleList/pinyinKey/nuegou
        if ($pinyinKey != "0") {
            $map3 = array('b_pinyinKey' => $pinyinKey);
            $tag = M('constantlyword')->where($map3)->group("b_string")->order("b_addtime desc, b_num desc")->limit(1)->select();
           
            if (!empty($tag)) {
                foreach($tag as $tagValue) {
                    $this->handleKeywordList($tagValue['b_string'], $tagValue['b_pinyinkey']);
                }
                
                redirect("/");
            } else {
                redirect("http://ba.cc");
            }

        } else {
            $tag = M('constantlyword')->where($map2)->where($map)->group("b_string")->order("b_addtime desc, b_num desc")->select();
            F("LastGetFile",array("insertTime"=>time()),"./Data/");            
            // foreach($tag as $tagValue) {
            //     $this->handleKeywordList($tagValue['b_string'], $tagValue['b_pinyinkey']);
            // }
        }

        $etime = microtime(true);
        $total = $etime - $stime; //计算差值
        $this->total = $total;
        $this->display();
    }

    //搜索列表页面文章标题和url文件处理内容单独处理，以便于其他函数调用
    private function handleKeywordList($searchKey, $pinyinKey) {
        set_time_limit(0);
        ini_set('memory_limit', '256M'); //设置单个线程的内存大小

        $keyword = M("keyword");
        $arr = array();
        $haosou = 'http://m.news.haosou.com/ns?q='.rawurlencode($searchKey); //关键字列表页面的link
        $k_pinyinName = $pinyinKey;
        //echo $haosou."<br/>";
        //将时时热门搜索关键词通过好搜索取相关文章标题与列表
        $news = getUrlContent($haosou);#匹配文章列表部分
        // echo $news;
        // die;
        preg_match('/<div id=main>(.*?)相关搜索/si', $news, $m);#匹配所有文章URL
        preg_match_all('/<a (.*?) <\/a>/si', $m[1], $aHtml);#匹配所有文章标题所在的a标签
        
        $k_sort = 0;
        $maxSort = $keyword ->where(array('k_pinyinName' => $k_pinyinName)) ->max('k_sort');
        if (empty($maxSort) || $maxSort <= 0 ) {
            $k_sort = 1;            
        }else
        {
            $k_sort = $maxSort;            
        }
        
        foreach($aHtml[0] as $key) {
            preg_match_all('/<a href="(.*?)">/si', $key, $url);#在a标签里面拿到href属性值url
            preg_match_all('/<h3 class=title>(.*?)<\/h3>/si', $key, $raw_title);#在a标签内容里面拿到title
            $k_Newsurl = "http://m.news.haosou.com".htmlspecialchars_decode($url[1][0]); //关键字页面文章的link
            $k_NewsName = strip_tags($raw_title[1][0]);

            $keyCount = $keyword ->where(array("k_NewsName" => htmlspecialchars($k_NewsName)))->count();

            //关键字大于零不添加
            if ($keyCount <= 0) {
                $fileMd5name = md5($k_NewsName.uniqid()); //文章标题和uniqid()函数结合生成值，降低重复概率
                $article_content = getUrlContent($k_Newsurl); //获得文章内容

                //获取文章标题
                preg_match('/<h1 id="news-title">(.*?)<\/h1>/si', $article_content, $title_raw);
                if (isset($title_raw[1])) { //如果文章标题存在才保存文章和关键字入库
                    getArticleFile($fileMd5name, $article_content); //将文章url所在内容储存到本地
                    $arr = array(
                        'k_pinyinname' => $k_pinyinName, 
                        'k_url' => $haosou, 
                        'k_NewsName' => htmlspecialchars($k_NewsName), 
                        'k_NewsUrl' => $k_Newsurl, 
                        'k_time' => time(), 
                        'k_md5name' => $fileMd5name, 
                        'k_sort' => $k_sort
                        );
                    // pp($arr);
                    // die;
                    $keyword->add($arr); //关键字数据入库
                    $k_sort++;
                }
            }
        }
    }


    //通过现有文章标题表里面的唯一文件名值处理文章内容保存到数据库 
    public function getArticleFileContent($pinyinKey = "", $k_sort = 0) {
        // pp($_GET);
        // die;
        set_time_limit(0);
        ini_set('memory_limit', '256M'); //设置单个线程的内存大小
        $stime = microtime(true);
        // $dayUnix = strtotime("today"); //今天时间0点的时间戳
        $tempLastUpdate =  F("LastGetFileContent","","./Data/"); //获取上一次更新时间
        $dayUnix =  $tempLastUpdate['insertTime'];
        
        $titleField = array('k_id','k_sort', 'k_md5name', 'k_time', 'k_pinyinName', 'k_newsurl'); //获取只需的字段，减少内存消耗
        //查询条件
        $map['k_time'] = array('gt', $dayUnix);


        if ($pinyinKey != "" && $k_sort > 0) { //处理单个文章标题,请求入口是/Home/Index/articleView/pinyinKey/nuegou/k_sort/2903
            $map2 = array('k_pinyinName' => $pinyinKey, 'k_sort' => $k_sort);
            $titleData = M("keyword")->field($titleField)->where("k_pinyinName = '".$pinyinKey ."' And k_sort = ". $k_sort)->limit(1)->select();
            // pp($titleData);
            // die;
            if (!empty($titleData)) {
                foreach($titleData as $key) {
                    $this->handleSingleTitle($key);
                }
                // echo "http://" . handelHOST() . '/'.$k_sort.'.html';
                // die;
                redirect("http://" . handelHOST() . '/'.$k_sort.'.html');
            }else
            {
                redirect("http://ba.cc");
            }
            
        } else {
            // $titleData = M("keyword")->field($titleField)->select();
            $titleData = M("keyword")  ->field($titleField) ->where($map) ->select();
            // pp($pinyinKey);
            // die;
            F("LastGetFileContent",array("insertTime"=>time()),"./Data/");
            foreach($titleData as $key) {
                $this->handleSingleTitle($key);
            }
        }
        $etime = microtime(true);
        $total = $etime - $stime; //计算差值
        $this->total = $total;
        $this->display();
    }

    private function handleSingleTitle($dataArray) {
        if (is_array($dataArray)) {
            $arr = array();
            $article = M("article");
            $md5Filename = $dataArray['k_md5name'];
            // $dateValue = '2016-03-01';
            $dateValue = date("Y-m-d", $dataArray["k_time"]);
            $filecontent = F($md5Filename, "", "./Data/".$dateValue."/");
            
            
            if (empty($filecontent)) {//可能文件已经不存在了
                $filecontent = getUrlContent($dataArray['k_newsurl']);
            }
            
            
            $pinyinname = $dataArray['k_pinyinname'];
            //echo $filecontent;  die;
            if (!empty($filecontent) && $pinyinname && $pinyinname != "") {

                //获取文章标题
                preg_match('/<h1 id="news-title">(.*?)<\/h1>/si', $filecontent, $title_raw);
                // pp($title_raw);
                // die;
                if (isset($title_raw[1])) {
                    $a_title = htmlspecialchars($title_raw[1]);

                    //查询当前文章标题是否有内容,列表页的标题加了样式，但是新闻的页面的没有，可能存在误差 
                    $titleCount = $article->where(array('a_title' => $a_title, 'k_pinyinName' => $dataArray['k_pinyinname'])) -> limit(1)->select();
                    // pp(($titleCount));
                    // die;

                    //如果没有内容才数据组合
                    if (empty($titleCount)) {
                        $filecontent = preg_replace('/http:\/\/m.haosou.com(.*?) class="qkw">/si', '/">', $filecontent);
                        preg_match('/<span id=source>(.*?)<\/span>/si', $filecontent, $author_raw);
                        preg_match('/<time id=time>(.*?)<\/time>/si', $filecontent, $time_raw);
                        preg_match('/<div id="news-body">(.*?)<\/div>/si', $filecontent, $content_raw);#$link[$num] = $link_raw;

                        //处理文章里面的a标签href值,防止关键字跳出站点,但是关键字这一块，如果没有点击就不入库
                        $content = $content_raw[1];
                        preg_match_all('/<a href(.*?)<\/a>/si', $content, $k);
                        foreach($k[0] as $aTag) {
                            preg_match_all('/>(.*?)<\/a>/si', $aTag, $textValue);
                            $aText = $textValue[1][0]; //a标签里面的文本
                            $checkPinYinVal = $this->checkConstantlyWords($aText); //检查是否存在这个关键字
                            //pp($checkPinYinVal);
                            if (!$checkPinYinVal) {
                                $aNewTag =$aText;
                            }else
                            {
                               $aNewTag = '<a  href="http://'.$checkPinYinVal.'.ba.cc"  class="qkw">'.$aText.'</a>';                      
                            }
                            $content = str_replace($aTag, $aNewTag, $content);
                            //     pp($content);
                            // die;
                        }

                        $content = preg_replace('/(\?size=\d+x\d+)/si', "", $content); //去掉图片链接后面的尺寸
                        $content = preg_replace('/<p><img /si', "<p  class=\"text-center\"><img ", $content); //图片包裹的p标签添加剧中样式

                        $arr = array('k_pinyinName' => $pinyinname, 'k_md5name' => $dataArray['k_md5name'], 'k_sort' => $dataArray['k_sort'], 'a_title' => $a_title, 'a_author' => $author_raw[1], 'a_time' => $time_raw[1], 'a_content' => htmlspecialchars($content), 'a_addTime' => time(),'k_id' =>$dataArray['k_id']);
                        // pp(htmlspecialchars($content));
                        // die;
                        $article->add($arr);
                    }
                    else
                    {
                        // pp( "http://" . handelHOST() . '/'. $titleCount[0]['k_sort'] .'.html');
                        // die;
                        redirect( "http://" . handelHOST() . '/'. $titleCount[0]['k_sort'] .'.html');                        
                    }
                }
                else
                {
                    redirect("ba.cc");
                }  
            }
        }
    }

    // //文章内容里面也会有关键字，将获得的关键字入库
    private function checkConstantlyWords($value) {
        $pinyinkey = null;
        if ($value) {
            // $value = preg_replace('/[\d\W_]/u', '', $value);
            preg_match_all('/[\x{4e00}-\x{9fff}a-zA-Z]+/u', $value, $matches);
            $value = join('', $matches[0]);
            $pinyinkey = TranslateValue($value);
            if ($pinyinkey && $value && $value != "" && $pinyinkey != "") {
                $contantlyWord = M("constantlyword");
                $count = $contantlyWord->where(array('b_string' => $value, 'b_pinyinKey' => $pinyinkey))->count();
                if ($count <= 0) {
                    $arr = array('b_string' => $value, 'b_pinyinKey' => $pinyinkey, 'b_num' => 0, 'b_addtime' => time());
                    //   pp($arr);
                    //   die;
                    $contantlyWord->add($arr); //关键字入库
                }
            }
        }
        return $pinyinkey;
    }
    
    
    public function getLastUpdateTime()
    {
        $update = F("UpdateTime","","./Data/");
        $updateTime = $update['insertTime'];
        $timeValue = time() - $updateTime;
        $LastGetWord[0] = $timeValue/(60*60);
        $LastGetWord[1] = getRealTime($updateTime);
                
        $LastGetFileTemp = F("LastGetFile","","./Data/");
        $temp001 = $LastGetFileTemp['insertTime'];
        $tempLastGetFile = time() - $temp001;
        $LastGetFile[0] = $tempLastGetFile/(60*60);
        $LastGetFile[1] = getRealTime($temp001);
        
        $LastGetFileContentTemp = F("LastGetFileContent","","./Data/");
        $temp002 = $LastGetFileContentTemp['insertTime'];
        $tempLastGetFileContent = time() - $temp002;   
        $LastGetFileContent[0] = $tempLastGetFileContent/(60*60);
        $LastGetFileContent[1] = getRealTime($temp002);        
        
        $arr = array(
            'LastGetWord' => $LastGetWord,
            'LastGetFile' => $LastGetFile,
            'LastGetFileContent' => $LastGetFileContent
            );
        // $arr2  = array('insertTime' => $arr );
        $this -> ajaxReturn($arr,"json");
    }
}