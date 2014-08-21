<?php
/*
  登録したスレッド、対象外になったスレッドを保存するテーブル
 */
class Threads {

    public static function insert($thread_no, $thread_name, $comment_datetime, $comment_count, $r_flg) {

        $sql = "INSERT INTO threads SET
                thread_no        = :thread_no,
                thread_name      = :thread_name,
                comment_datetime = :comment_datetime,
                comment_count    = :comment_count,
                r_flg            = :r_flg,
                r_datetime       =NOW()
                ;";

        $params = array('thread_no'        => $thread_no,
                        'thread_name'      => $thread_name,
                        'comment_datetime' => $comment_datetime,
                        'comment_count'    => $comment_count,
                        'r_flg'            => $r_flg,
                       );

        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }


    public static function get($thread_no) {

        $sql = "SELECT thread_no FROM threads
                WHERE thread_no = :thread_no";

        $params = array('thread_no'        => $thread_no);

        $dbh = DBConnection::getConnection();
        return DBManager::select($dbh, $sql, $params);
    }
}
?>