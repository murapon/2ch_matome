<?php
class Contents {

    public static function getCondition($id){

        switch ($id) {
            case 1:
                // 芸能ニュース
                $condition = array();
                $condition['domain'] = 'http://hayabusa3.2ch.sc/';
                $condition['category'] = 'mnewsplus';
                $condition['category_id'] = '6';
                $condition['condition_time'] = '-3 day';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 2:
                // 痛いニュース
                $condition = array();
                $condition['domain'] = 'http://anago.2ch.sc/';
                $condition['category'] = 'dqnplus';
                $condition['category_id'] = '241';
                $condition['condition_time'] = '-7 day';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 3:
                // ニュース速報VIP+
                $condition = array();
                $condition['domain'] = 'http://hayabusa3.2ch.sc/';
                $condition['category'] = 'news4viptasu';
                $condition['category_id'] = '8';
                $condition['condition_time'] = '-1 day';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
//            case 4:
                // 面白ネタニュース
//                $condition = array();
//                $condition['domain'] = 'http://kohada.open2ch.net/';
//                $condition['category'] = 'be';
//                $condition['category_id'] = '1004';
//                $condition['condition_time'] = '-3 month';
//                $condition['condition_count'] = '100';
//                $condition['display_count'] = 50;
//                $condition['type'] = TYPE_2CH;
//                return $condition;
            case 5:
                // なんでも質問
                $condition = array();
                $condition['domain'] = 'http://ikura.2ch.sc/';
                $condition['category'] = 'nandemo';
                $condition['category_id'] = '1003';
                $condition['condition_time'] = '-3 month';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 6:
                // ほのぼのニュース
                $condition = array();
                $condition['domain'] = 'http://anago.2ch.sc/';
                $condition['category'] = 'femnewsplus';
                $condition['category_id'] = '1002';
                $condition['condition_time'] = '-3 month';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 7:
                // ＦＦ＆ドラクエだけの話題
                $condition = array();
                $condition['domain'] = 'http://nozomi.2ch.sc/';
                $condition['category'] = 'ff';
                $condition['category_id'] = '1059';
                $condition['condition_time'] = '-3 day';
                $condition['condition_count'] = '300';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 8:
                // ニュース
                $condition = array();
                $condition['domain'] = 'http://hayabusa3.2ch.sc/';
                $condition['category'] = 'news';
                $condition['category_id'] = '1076';
                $condition['condition_time'] = '-1 day';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 9:
                // 既婚女性
                $condition = array();
                $condition['domain'] = 'http://ikura.2ch.sc/';
                $condition['category'] = 'ms';
                $condition['category_id'] = '1077';
                $condition['condition_time'] = '-1 month';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 10:
                // ニュース速報(VIP)
                $condition = array();
                $condition['domain'] = 'http://viper.2ch.sc/';
                $condition['category'] = 'news4vip';
                $condition['category_id'] = '7';
                $condition['condition_time'] = '-1 day';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 11:
                // ニュース速報+
                $condition = array();
                $condition['domain'] = 'http://ai.2ch.sc/';
                $condition['category'] = 'newsplus';
                $condition['category_id'] = '4';
                $condition['condition_time'] = '-1 day';
                $condition['condition_count'] = '100';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
            case 12:
                // スマホゲーム
                $condition = array();
                $condition['domain'] = 'http://anago.2ch.sc/';
                $condition['category'] = 'applism';
                $condition['category_id'] = '1106';
                $condition['condition_time'] = '-7 day';
                $condition['condition_count'] = '200';
                $condition['display_count'] = 50;
                $condition['type'] = TYPE_2CH;
                return $condition;
        }
    }

    public static function getTitle($raw_contents){
        // タイトル取得
        preg_match('/<title>.+?<\/title>/', $raw_contents , $match_title);
        return Editing::removeStr($match_title[0], array("<title>", "</title>"));
    }

    public static function getNewsTitleTag($raw_contents){
        // タイトル取得
        $title = self::getTitle($raw_contents);
        $news = array();
        $title_temp = Editing::removePreg($title, array("/【.+】/"));
        if($title_temp != ''){
            $news["title"] = $title_temp;
        } else {
            $news["title"] = Editing::removeStr($title, array("【", "】"));
        }
        // タグ取得
        $flg = preg_match('/【.+】/', $title , $match_tag);
        if($flg){
            $news["tag"] = Editing::removeStr($match_tag[0], array("【", "】"));
        }
        return $news;
    }

    public static function getCommentList($raw_contents, $display_count, $category, $type){
        $comment_list = self::extraction($raw_contents, $type);
        $comment_list = self::cleansing($comment_list);
        $comment_list = self::setReply($comment_list);
        $comment_list = self::setOverlap($comment_list);
        $comment_list = self::arrange($comment_list);
        if($category == 'news' ||
           $category == 'newsplus' ||
           $category == 'mnewsplus'){
            $comment_list = self::reductionForNews($display_count, $comment_list);
        } else {
            $comment_list = self::reductionForVip($display_count, $comment_list);
        }
        return $comment_list;
    }

    public static function extraction($raw_contents, $type){

        $org_comment_list = array();
        // スレッド取得
        mb_regex_encoding('UTF-8');
        mb_ereg('<dl class=\"thread\">(\n|.)*?<\/dl>', $raw_contents, $match_dl);
        if($type == TYPE_2CH){
            preg_match_all('/<dt>(\n|.)*?<br><br>/', $match_dl[0] , $match_dt);
        } else {
            preg_match_all('/<dt>(\n|.)*?<\/dt>/', $match_dl[0] , $match_dt);
        }
        foreach($match_dt[0] as $dt){
            $comment_data = array();
            // コメントNo
            if($type == TYPE_2CH){
                preg_match('/<dt>\d+/', $dt , $match_comment_no);
                $comment_data["no"] = Editing::removeStr($match_comment_no[0], array("<dt>"));
            } else {
                preg_match('/<dt><a class=num val=\d+/', $dt , $match_comment_no);
                $comment_data["no"] = Editing::removeStr($match_comment_no[0], array("<dt><a class=num val="));
            }

            // ハンドルネーム
            preg_match('/<b>.*?<\/b><\//', $dt , $match_name);
            $comment_data["name"] = Editing::removeStr($match_name[0], array("<b>", "</b></"));

            // 投稿時間
            if($type == TYPE_2CH){
                preg_match('/\d{4}\/\d{2}\/\d{2}\(.*?\)\s\d{2}:\d{2}:\d{2}/', $dt , $match_time);
            } else {
                preg_match('/\d{4}\/\d{2}\/\d{2}\(.*?\)\d{2}:\d{2}:\d{2}/', $dt , $match_time);
            }
            $comment_data["time"] = $match_time[0];

            // ID
            if($type == TYPE_2CH){
                preg_match('/ID:.*<dd>/', $dt , $match_id);
            } else {
                preg_match('/ID:.*<\/a>/', $dt , $match_id);
            }
            if(isset($match_id[0])){
                if($type == TYPE_2CH){
                    $comment_data["id"] = Editing::removeStr($match_id[0], array("ID:", "<dd>"));
                } else {
                    $comment_data["id"] = Editing::removeStr($match_id[0], array("ID:", "</a>"));
                }
            } else {
                $comment_data["id"] = "";
            }

            // コンテンツ
            if($type == TYPE_2CH){
                preg_match('/<dd>.*\s<br><br>$/', $dt , $match_contents);
            } else {
                preg_match('/<dd>(\n|.)*?<\/dd>/', $dt , $match_contents);
            }
            $contents = Editing::removeStr($match_contents[0], array("<dd>", "<\/dd>"));
            if($type == TYPE_2CH){
                $contents = str_replace("<br>", "<br/>", $contents);
            }
            // 返信抜き出し
            preg_match('/<a href=\"\.\.\/test\/read.cgi\/.*?\" target=\"_blank\">.*?<\/a>/', $contents, $match_reply);
            if(count($match_reply) > 0){
                $reply_no = Editing::removePreg($match_reply[0], array('/<a href=\"\.\.\/test\/read.cgi\/.*?\" target=\"_blank\">\&gt\;\&gt\;/', '/<\/a>/'));
                if($reply_no < $comment_data["no"]){
                    $comment_data["reply_no"] = $reply_no;
                }
            }
            $comment_data["comment"] = preg_replace('/<a href=\"\.\.\/test\/read.cgi\/.*?\" target=\"_blank\">.*?<\/a>/', "", $contents);
            $org_comment_list[] = $comment_data;
        }
        return $org_comment_list;
    }

    // 重複しているデータを除く
    // AAを除く
    // ttp://を除く
    public static function cleansing($before_comment_list){
        $after_comment_list = array();
        $overlap_data = array();
        $top_flg = true;
        foreach($before_comment_list as $before_comment){
            preg_match_all("/   |　　/", $before_comment["comment"], $matches_aa);
            preg_match_all("/http:\/\/|https:\/\//", $before_comment["comment"], $matches_http);
            preg_match_all("/ID:/", $before_comment["comment"], $matches_id);
            $comment_count = mb_strlen($before_comment['comment'], 'UTF-8');
            if($top_flg == true ||
               (in_array($before_comment["comment"], $overlap_data) == false &&
                count($matches_aa[0]) < 3 &&
                count($matches_http[0]) == 0 &&
                count($matches_id[0]) == 0 &&
                $comment_count > 10)){
                $after_comment_list[] = $before_comment;
                $overlap_data[] = $before_comment["comment"];
                $top_flg = false;
            }
        }
        return $after_comment_list;
    }


    // 返信関係にあるデータに、親子を設定する。
    public static function setReply($before_comment_list){
        // 返信しているコメントを取得
        $reply_list = array();
        foreach($before_comment_list as $before_comment){
            if(isset($before_comment["reply_no"])){
                $reply_list[] = $before_comment;
            }
        }
        $after_comment_list = array();
        foreach($before_comment_list as $before_comment){
            $before_comment['reply_child'] = false;
            $before_comment['reply_parents'] = false;
            $before_comment['no_reply'] = false;
            if(isset($before_comment["reply_no"])){
                // 返信していたら子を設定
                $before_comment['reply_child'] = true;
            } else {
                foreach($reply_list as $reply){
                    if($before_comment['no'] == $reply['reply_no']){
                        // 返信されていたら親を設定
                        $before_comment['reply_parents'] = true;
                        break;
                    }
                }
            }
            // 返信関係になければ返信なしを設定
            if($before_comment['reply_child'] == false && $before_comment['reply_parents'] == false){
                $before_comment['no_reply'] = true;
            }
            $after_comment_list[] = $before_comment;
        }
        return $after_comment_list;
    }


    // IDが重複してたらフラグをセットし重要に見せる。
    public static function setOverlap($before_comment_list){
        // 重複したIDの算出
        $temp_id_list = array();
        $overlap_id_list = array();
        foreach($before_comment_list as $before_comment){
            foreach($temp_id_list as $id){
                if($before_comment['id'] == $id){
                    $overrap_id_list[] = $before_comment['id'];
                    break;
                }
            }
            $temp_id_list[] = $before_comment['id'];
        }

        // 重複フラグのセット
        $after_comment_list = array();
        foreach($before_comment_list as $before_comment){
            if(in_array($before_comment['id'],$overlap_id_list)){
                $before_comment['overlap_flg'] = true;
            } else {
                $before_comment['overlap_flg'] = false;
            }
            $after_comment_list[] = $before_comment;
        }
        return $after_comment_list;
    }


    public static function arrange($before_comment_list){

        // 返信したもの以外のコメント一覧
        $comment_list = array();
        foreach($before_comment_list as $before_comment){
            if(!isset($before_comment["reply_no"])){
                $comment_list[] = $before_comment;
            }
        }

        // 返信しているコメント一覧
        $reply_list = array();
        foreach($before_comment_list as $before_comment){
            if(isset($before_comment["reply_no"])){
                $before_comment["comment"] = preg_replace('/^ <br> /', '', $before_comment["comment"]);
                $reply_list[] = $before_comment;
            }
        }

        $display_comment_list = array();
        $top_flg = true;
        foreach($comment_list as $comment){
            $top_flg = false;
            if($comment['no_reply']){
                // 返信関係になければ文字数チェック
                $comment_count = mb_strlen($comment['comment'], 'UTF-8');
                $display_comment_list[] = $comment;
            } else {
                // 返信関係があれば親を表示
                $display_comment_list[] = $comment;
                // 子を登録
                foreach($reply_list as $reply){
                    if($comment['no'] == $reply['reply_no']){
                         $display_comment_list[] = $reply;
                    }
                }
            }
        }
        return $display_comment_list;
    }


    // ニュース系掲示板は、ばっさり削る
    public static function reductionForNews($display_count, $comment_list){
        // 重複かつ返信親件数取得
        // 返信親件数取得
        $overlap_reply_parents_count = 0;
        foreach($comment_list as $comment){
            if($comment['reply_parents'] == true &&
                $comment['overlap_flg'] == true ){
                $overlap_reply_parents_count++;
            }
        }
        $reply_ramdom_num = (count($comment_list) - $overlap_reply_parents_count) / ($display_count * 3);
        if($reply_ramdom_num == 0){
            $reply_ramdom_num = 1;
        }
        $non_reply_ramdom_num = $reply_ramdom_num * 5;
        $display_comment_list = array();
        $top_flg = true;
        foreach($comment_list as $comment){
            if($top_flg){
                $display_comment_list[] = $comment;
                $top_flg = false;
            } else if($comment['reply_parents'] == true &&
                $comment['overlap_flg'] == true ){
                $display_comment_list[] = $comment;
            } else if($comment['reply_parents']){
                // 返信親
                if(rand(0, $reply_ramdom_num) == 0){
                    $display_comment_list[] = $comment;
                }
            } else if($comment['reply_child']){
                // 返信子
                foreach($display_comment_list as $display_comment){
                    if($display_comment['no'] == $comment['reply_no']){
                        $display_comment_list[] = $comment;
                        break;
                    }
                }
            } else {
                if(rand(0, $non_reply_ramdom_num) == 0){
                    $display_comment_list[] = $comment;
                }
            }
        }
        return $display_comment_list;
    }


    // VIP系掲示板は、返信関係を重視し残す
    public static function reductionForVip($display_count, $comment_list){
        // 全体のコメント量調整
        $comment_count = count($comment_list);
        if($comment_count > $display_count){
            // 返信親件数取得
            $reply_parents = 0;
            foreach($comment_list as $comment){
                if($comment['reply_parents']){
                    $reply_parents++;
                }
            }
            $delete_target_count = $comment_count - $reply_parents;
            $delete_count = $display_count - $reply_parents;
            if($delete_count < 0){
                $delete_count = 0;
                $ramdom_num = 100;
            } else {
                $ramdom_num = round($delete_target_count / $delete_count) - 1;
            }
            $no_reply_ramdom_num = round($ramdom_num * 3);
            $child_ramdom_num = round($ramdom_num / 2);
            $top_flg = true;
            $display_comment_list = array();
            // 返信関係を重視し残す
            foreach($comment_list as $comment){
                if($top_flg){
                    $display_comment_list[] = $comment;
                    $top_flg = false;
                } else {
                    if($comment['reply_parents'] || $comment['reply_child']){
                        $display_comment_list[] = $comment;
                    } else {
                        if(rand(0, $no_reply_ramdom_num) == 0){
                            $display_comment_list[] = $comment;
                        }
                    }
                }
            }
            return $display_comment_list;
        }
        return $comment_list;
    }
}