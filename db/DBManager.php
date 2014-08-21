<?php
class DBManager {

    public static function execute($dbh, $sql, $params=array()){

        $stmt = $dbh->prepare($sql);
        if(! empty($params)){
            foreach ($params as $key => $param) {
                $stmt->bindValue(':'. $key, $param);
            }
        }
        $stmt->execute($params);
        return $dbh->lastInsertId();
    }

    public static function select($dbh, $sql, $params=array()){

        $stmt = $dbh->prepare($sql);
        if(! empty($params)){
            foreach ($params as $key => $param) {
                $stmt->bindValue(':'. $key, $param);
            }
        }
        $stmt->execute($params);
        // 実行結果の取得
        $rows = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }

}
