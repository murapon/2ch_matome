<?php
/*
   タグ、カテゴリ付け用テーブル
 */
class WpTermRelationships {
    public static function insert($object_id, $term_taxonomy_id) {

        $sql = "insert into wp_term_relationships set
                object_id        = :object_id,
                term_taxonomy_id = :term_taxonomy_id,
                term_order       = 0
                ;";

        $params = array('object_id' => $object_id,
                        'term_taxonomy_id' => $term_taxonomy_id,
                       );

        $dbh = DBConnection::getConnection();
        return DBManager::execute($dbh, $sql, $params);
    }
}
?>