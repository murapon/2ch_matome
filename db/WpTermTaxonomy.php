<?php
/*
   タグ用テーブル
 */
class WpTermTaxonomy {
    public static function insert($term_id, $taxonomy, $count) {

        $sql = "insert into wp_term_taxonomy set
                term_id       = :term_id,
                taxonomy       = :taxonomy,
                count = :count
                ;";
        $params = array('term_id' => $term_id,
                        'taxonomy' => $taxonomy,
                        'count' => $count,
                       );
        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }

    public static function get($term_id, $taxonomy) {

        $sql = "SELECT term_taxonomy_id FROM wp_term_taxonomy
                WHERE term_id = :term_id
                 AND  taxonomy = :taxonomy";
        $params = array('term_id' => $term_id,
                        'taxonomy' => $taxonomy);

        $dbh = DBConnection::getConnection();
        return DBManager::select($dbh, $sql, $params);
    }

    public static function updateCount($term_id) {

        $sql = "UPDATE wp_term_taxonomy
                   SET count = (SELECT count(*) FROM wp_term_relationships WHERE term_taxonomy_id = :term_taxonomy_id)
                WHERE term_id = :term_id";
        $params = array('term_taxonomy_id' => $term_id,
                        'term_id' => $term_id);

        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }
}
?>