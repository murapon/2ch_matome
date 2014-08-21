<?php
class Article {

    public static function make($domain, $category, $category_id, $condition_time, $condition_count, $display_count, $type){

        // スレッドURL、コメント数を一覧で取得
        $thread_extraction = new ThreadExtraction($domain, $category_id, $category, $type);
        $thread_list = $thread_extraction->getThreadList();

        // コメント数が一定数以上か、既に取得済みのスレッドかチェックし抽出
        $thread_list = $thread_extraction->extractionDownLoadThreadList($thread_list, $condition_count);
        // 記事対象となるスレッド取得
        $thread = $thread_extraction->searchThread($thread_list, $condition_time);
        if($thread == null){
            return false;
        }
        $thread_url = $thread['thread_url'];
        $raw_contents = $thread['raw_contents'];
        $comment_count = $thread['comment_count'];

        // タイトル、タグ取得
        $news_title_tag = Contents::getNewsTitleTag($raw_contents);

        // 投稿対象のコメント取得
        $contents = Contents::getCommentList($raw_contents, $display_count, $category, $type);

        $top_comment = $contents[0];
        if($top_comment['no'] != '1'){
            return false;
        }
        $val = array();
        $val['top_no'] = $top_comment['no'];
        $val['top_name'] = $top_comment['name'];
        $val['top_time'] = $top_comment['time'];
        $val['top_id'] = $top_comment['id'];
        $val['top_comment'] = Editing::removeAdUrl($top_comment['comment']);

        unset($contents[0]);
        $display_comment_list = array();
        foreach($contents as $content){
            $content['comment'] = Editing::removeAdUrl($content['comment']);
            $display_comment_list[] = $content;
        }

        $val['thread_url'] = $thread_url;
        $val['comment_list'] = $display_comment_list;

        // 平均投稿数
        $val['register_datetime'] = date( "Y/m/d H:i", time());
        $val['all_comment_count'] = $comment_count;
        $datetime = str_replace("/", "-", substr($top_comment['time'], 0, 10)). ' '. substr($top_comment['time'], strlen($top_comment['time']) - 8, strlen($top_comment['time']));
        $term = round((time() - strtotime($datetime)) / 3600, 1);
        $val['average_comment_count'] = round($comment_count / $term, 1);

        // 本文生成
        $blog_contents = htmltemplate::t_include(dirname(__FILE__). "/../contents.tpl",$val);

        // タグ付け
        if(array_key_exists('tag', $news_title_tag)){
            $tag_id = Tag::addTag($news_title_tag['tag']);
        } else {
            $tag_id = "1";
        }

        DBConnection::begin();
        try{
            $last_insert_id = WpPosts::insert($news_title_tag['title'], $blog_contents);
            WpTermRelationships::insert($last_insert_id, $tag_id);
            WpTermRelationships::insert($last_insert_id, $category_id);
            WpTermTaxonomy::updateCount($category_id);
            WpPosts::updateGuid($last_insert_id);
            DBConnection::db_commit();
        }
        catch(Exception $e){
            DBConnection::db_rollBack();
        }

        // tweet
        Twitter::tweet($news_title_tag['title']. "  http://syuzyou.info/archives/". $last_insert_id);
        return true;
    }
}
?>
