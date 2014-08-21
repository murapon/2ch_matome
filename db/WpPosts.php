<?php
/*
   記事用テーブル
 */
class WpPosts {

    public static function insert($title, $content) {

        $sql = "insert into wp_posts set
                post_author       = :post_author,
                post_date         = now(),
                post_date_gmt     = NOW() - INTERVAL 9 HOUR,
                post_content      = :post_content,
                post_title        = :post_title,
                post_name         = :post_name,
                post_modified     = NOW(),
                post_modified_gmt = NOW() - INTERVAL 9 HOUR,
                guid              = :guid
                ;";

        $params = array('post_author' => '1',
                        'post_content' => $content,
                        'post_title'   => $title,
                        'post_name'    => $title,
                        'guid'          => ' ',
                       );

        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }

    public static function updateGuid($id) {

        $sql = "UPDATE wp_posts
                 SET guid = :guid
                 WHERE ID = :id
                ;";

        $params = array('id' => $id,
                        'guid' => "http://syuzyou.info/archives/". $id,
                       );

        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }

    public static function getNew() {

        $sql = "SELECT post_date_gmt FROM wp_posts
                ORDER BY ID DESC
                LIMIT 1";
        $params = array();
        $dbh = DBConnection::getConnection();
        return DBManager::select($dbh, $sql, $params);
    }
}
?>