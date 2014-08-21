<?php
require dirname(__FILE__). "/config.php";
require dirname(__FILE__). "/db/DBConnection.php";
require dirname(__FILE__). "/db/DBManager.php";
require dirname(__FILE__). "/db/Threads.php";

$dbh = DBConnection::getConnection();
$sql = "SELECT id, post_content FROM wp_posts";
$data_list = DBManager::select($dbh, $sql, array());
foreach($data_list as $data){
    $contents = preg_replace('/<script async src=\"\/\/pagead2.googlesyndication.com\/(.)*?push\(\{\}\)\;(.)*?<\/script>/s', "%%%advertisement%%%" , $data['post_content']);
    var_dump($contents);

    $sql = "UPDATE wp_posts
            SET post_content = :post_content
            WHERE ID = :id
           ;";
    $params = array('id' => $data['id'],
                    'post_content' => $contents,
                   );
    DBManager::execute($dbh, $sql, $params);
}
