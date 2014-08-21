<?php
/*
  登録したスレッド、対象外になったスレッドを保存するテーブル
 */
class ThreadInfo {

    public static function insert($category_id, $thread_no, $thread_name, $comment_datetime, $comment_count, $r_flg) {

        $sql = "INSERT INTO thread_info SET
                category_id      = :category_id,
                thread_no        = :thread_no,
                thread_name      = :thread_name,
                comment_datetime = :comment_datetime,
                comment_count    = :comment_count,
                r_flg            = :r_flg,
                r_datetime       =NOW()
                ;";

        $params = array('category_id'      => $category_id,
                        'thread_no'        => $thread_no,
                        'thread_name'      => $thread_name,
                        'comment_datetime' => $comment_datetime,
                        'comment_count'    => $comment_count,
                        'r_flg'            => $r_flg,
                       );

        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }


    public static function get($category_id, $thread_no) {

        $sql = "SELECT thread_no FROM thread_info
                WHERE category_id = :category_id
                 AND  thread_no = :thread_no";

        $params = array('category_id' => $category_id,
                        'thread_no'   => $thread_no,
                       );

        $dbh = DBConnection::getConnection();
        return DBManager::select($dbh, $sql, $params);
    }
}
?>