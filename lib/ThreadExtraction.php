<?php
class ThreadExtraction {

    private $domain;
    private $category_id;
    private $category;

    function __construct($domain, $category_id, $category, $type){
        $this->domain = $domain;
        $this->category_id = $category_id;
        $this->category = $category;
        $this->type = $type;
    }

    public function getThreadList(){
        $raw_contents = file_get_contents($this->domain. $this->category. "/subback.html");
        if($this->type == TYPE_2CH){
            $raw_contents = mb_convert_encoding($raw_contents, "UTF-8", "SJIS");
            preg_match_all('/<a href=\"\d+\/l50\">.+?\(\d.+?\)<\/a>/', $raw_contents , $matches);
        } else {
            preg_match_all('/<a href=\".+?\/l50\">.+?\(\d.+?\)<\/a>/', $raw_contents , $matches);
        }
        $thread_list = array();
        foreach($matches[0] as $data){
            $thread = array();
            if($this->type == TYPE_2CH){
                preg_match('/href=\"\d.+?\//', $data, $thread_url);
            } else {
                preg_match('/href=\".+?\/l50/', $data, $thread_url);
            }
            $url = Editing::removeStr($thread_url[0], array("href=\""));

            if($this->type == TYPE_2CH){
                $thread_no = Editing::removePreg($url, array("/href=\"/", "/\//"));
            } else {
                $thread_no = Editing::removePreg($url, array("/\/test\/read.cgi\/". $this->category. "\//", "/\/l50/"));
            }
            $thread['thread_no'] = $thread_no;
            preg_match('/\(\d+?\)<\/a>$/', $data , $comment_count);
            $thread['comment_count'] = Editing::removeStr($comment_count[0], array("(", ")</a>"));
            $thread_list[] = $thread;
        }
        return $thread_list;
    }

    public function extractionDownLoadThreadList($thread_list, $condition_count){

        $extraction_thread_list = array();
        foreach($thread_list as $thread){
            // コメント数が一定以上かチェック
            if($thread['comment_count'] < $condition_count){
                // 一定以下なら次へ
                continue;
            }

            // 既に取得したスレッドかチェック
            $data = ThreadInfo::get($this->category_id, $thread["thread_no"]);
            if(count($data) > 0){
                // 一定以下なら次へ
                continue;
            }
            $extraction_thread_list[] = $thread;
        }
        return $extraction_thread_list;
    }


    public function searchThread($thread_list, $condition_time){

        $head_url = $this->domain. "test/read.cgi/". $this->category. "/";

        $access_count = 0;
        foreach($thread_list as $thread){
            $access_count++;
            $url = $head_url. $thread["thread_no"]. "/";
            $raw_contents = file_get_contents($url);

            if($this->type == TYPE_2CH){
                $raw_contents = mb_convert_encoding($raw_contents, "UTF-8", "SJIS");
            }
            // 一つ目のコメント抜き出し
            mb_regex_encoding('UTF-8');
            mb_ereg('<dl class=\"thread\">(\n|.)*?<\/dl>', $raw_contents , $match_dl);
            preg_match_all('/<dt>(\n|.)*?<br><br>/', $match_dl[0] , $match_dt);
            $comment_data = $match_dt[0][0];

            // 投稿時間取得
            if($this->type == TYPE_2CH){
                preg_match('/\d{4}\/\d{2}\/\d{2}\(.*?\)\s\d{2}:\d{2}:\d{2}/', $comment_data , $match_date);
            } else {
                preg_match('/\d{4}\/\d{2}\/\d{2}\(.*?\)\d{2}:\d{2}:\d{2}/', $comment_data , $match_date);
            }
            $datetime = str_replace("/", "-", substr($match_date[0], 0, 10)). ' '. substr($match_date[0], strlen($match_date[0]) - 8, strlen($match_date[0]));
            // タイトル取得
            $title = Contents::getTitle($raw_contents);

            if (strtotime(date("Y-m-d H:i:s",strtotime($condition_time))) <= strtotime($datetime) &&
                preg_match("/転載/", $title) == 0 &&
                preg_match("/転載/", $comment_data) == 0){
                $thread['thread_url'] = $url;
                $thread['raw_contents'] = $raw_contents;

                // 取得したスレッドをテーブルに登録
                ThreadInfo::insert($this->category_id, $thread["thread_no"], Contents::getTitle($raw_contents), $datetime, $thread["comment_count"], 1);
                return $thread;
            }

            // 取得対象外のスレッドをテーブルに登録
            ThreadInfo::insert($this->category_id, $thread["thread_no"], Contents::getTitle($raw_contents), $datetime, $thread["comment_count"], 0);
            if($access_count % 10){
                sleep(1);
            }
        }
        return null;
    }

}
