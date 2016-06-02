<?php 
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function _before_index() {
        $se = 0;
        $url = $_SERVER["HTTP_REFERER"]; //获取完整的来路URL 
        $str = str_replace("https://", "", $url); //去掉https:// 
        $str = str_replace("http://", "", $str); //去掉http:// 
        $strdomain = explode("/", $str); // 以“/”分开成数组 
        $domain = $strdomain[0]; //取第一个“/”以前的字符
        if (strstr($domain, 'baidu.com')) {
            $se = 1;
        } else if (strstr($domain, 'haosou.com')) {
            $se = 1;
        } else if (strstr($domain, 'sogou.com')) {
            $se = 1;
        } else if (strstr($domain, 'bing.com')) {
            $se = 1;
        }
        if ($se == 1) {
            $adData = F("adportal", "", "./Data/");
            echo $adData;
            die;
        }
        $url = explode(".", $_SERVER['HTTP_HOST']);
        $root = $url[1].'.'.$url[2];
        if ($url[2]) {
            $sub = $url[0];
        } else {
            $sub = "";
        }
    }
    
    public function index() {
        $update = F("UpdateTime","","./Data/");//首页上一次更新数据的的时间 
        $updateTime = $update['insertTime']; 
        if ($updateTime) {
            $timeValue = time() - $updateTime;
            $hourtime =  $timeValue/(60*60);
            //$this -> hourtime = $hourtime;
            if ($hourtime >= 2) {
                F("LastUpdate",array("insertTime" => $updateTime),"./Data/");              
                $this ->redirect("/Home/Article/getkeywords/");
            }
        } 
        
        $lastUpdate = F("LastUpdate","","./Data/")['insertTime'];
        // pp($lastUpdate);
        // die;
        
        $map['b_num'] = array("gt", 0);
        if (!empty($lastUpdate)) {
            $map['b_addtime'] = array('egt',$lastUpdate);            
        }
        $tag = M('constantlyword')->where($map) ->group(' b_string ') ->order(" b_addtime desc, b_num desc")->limit(0, 50)->select();
        $this->tagList = $tag;
        $this->display("index");
    }

    public function articleList($pinyinKey) {
        //  pp($_GET) ."_p<br/>";
        // echo $pinyinKey;
        // die;
        
        if (isset($pinyinKey)) {
            $articleData = M("keyword");
            $count = $articleData->where(array("k_pinyinname" => $pinyinKey))->count();
            //如果小于等于0，就要去跳转及时抓取
            if ($count <= 0) {
                $this->redirect("/Home/Article/getArticleTitleAndFile/", array('pinyinKey' => $pinyinKey));
            } else {
                $Page = new\Think\BootstrapPage($count, 5);
                $limit = $Page->firstRow.','.$Page->listRows;
                $dataList = $articleData->where(array("k_pinyinname" => $pinyinKey))->limit($limit)->select();
                $show = $Page->Show();
                // pp($dataList);
                // die;
                //根据拼音key查询热门关键字
                $kname = M("constantlyword")->where(array('b_pinyinKey' => $pinyinKey)) ->order("b_addtime desc, b_id asc")->limit(1)->select();
                //    pp($articleData);
                //    die;
                $this->kname = $kname[0]['b_string'];
                //$this -> PostBaidu($dataList);
                $this->articleList = $dataList;
                $this->show = $show;
            }
        }
        
        
        $this->display("articleList");
        
    }
    
    private function PostBaidu($arrays)
    {
        $temp = "";
        $urls = array();
        $temparr = array();
        if (!empty($arrays)) {
            foreach ($arrays as $keyvalue) {
                $temparr = array("http://" .$keyvalue['k_pinyinname']. ".ba.cc/". $keyvalue['k_sort'] .".html");
               $urls = array_merge($urls,$temparr);
            }
        }
        
        $api = 'http://data.zz.baidu.com/urls?site=www.ba.cc&token=sssssssssssssssssssssssssss';
        $ch = curl_init();
        $options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        echo $result;
        pp($arr);
        die;
    }

    public function articleView($pinyinKey, $k_sort) {
        // pp($_GET);
        // die;
        if ($pinyinKey && $k_sort) {
            $map['k_pinyinname'] = $pinyinKey;
            $map['k_sort'] = $k_sort;
            
            $articleData = M("article")->where(" k_pinyinname ='". $pinyinKey ."' and k_sort = " . $k_sort) -> limit(1) ->select();
            //根据拼音key查询热门关键字
            $kname = M("constantlyword")->where(array('b_pinyinKey' => $pinyinKey))->order("b_addtime desc, b_id asc")->limit(1)->select();
            //    pp($articleData);
            //    die;
            if (!empty($articleData)) {
                $articleTitle = $articleData[0]['a_title'];
                $articleContent = $articleData[0]['a_content'];
                
                $this ->kname = $kname[0]['b_string'];
                $this->articleTitle = $articleTitle;
                $this->articleContent = htmlspecialchars_decode($articleContent);
            } else {
                $this->redirect("/Home/Article/getArticleFileContent/", array("pinyinKey" => $pinyinKey, "k_sort" => $k_sort));
            }
        }
        $this->display("articleView");
    }
}