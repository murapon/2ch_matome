<?php
/*
   タグ、カテゴリー用テーブル
 */
class WpTerms {
    public static function insert($name) {

        $sql = "insert into wp_terms set
                name       = :name,
                slug       = :slug,
                term_group = 0
                ;";
        $params = array('name' => $name,
                        'slug' => urlencode($name),
                       );
        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }

    public static function get($name) {

        $sql = "SELECT term_id FROM wp_terms
                WHERE name = :name";

        $params = array('name' => $name);

        $dbh = DBConnection::getConnection();
        return DBManager::select($dbh, $sql, $params);
    }
}
?>