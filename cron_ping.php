<?php
require dirname(__FILE__). "/config.php";
require dirname(__FILE__). "/db/DBConnection.php";
require dirname(__FILE__). "/db/DBManager.php";
require dirname(__FILE__). "/db/WpPosts.php";

date_default_timezone_set('Asia/Tokyo');

$title = '衆生遊楽';
$url = "http://syuzyou.info/";
$ping_list[] = array("host" => "blogsearch.google.com",    "path" => "/ping/RPC2");
$ping_list[] = array("host" => "api.my.yahoo.co.jp",       "path" => "/RPC2");
$ping_list[] = array("host" => "blog.goo.ne.jp",           "path" => "/XMLRPC");
$ping_list[] = array("host" => "blogsearch.google.co.jp",  "path" => "/ping/RPC2");
$ping_list[] = array("host" => "blogsearch.google.com",    "path" => "/ping/RPC2");
$ping_list[] = array("host" => "ping.bloggers.jp",         "path" => "/rpc/");
$ping_list[] = array("host" => "ping.blogranking.net",     "path" => "/");
$ping_list[] = array("host" => "ping.fc2.com",             "path" => "/");
$ping_list[] = array("host" => "ping.freeblogranking.com", "path" => "/xmlrpc/");
$ping_list[] = array("host" => "pingoo.jp",                "path" => "/ping/");
$ping_list[] = array("host" => "rpc.weblogs.com",          "path" => "/RPC2");
$ping_list[] = array("host" => "serenebach.net",           "path" => "/rep.cgi");
$ping_list[] = array("host" => "www.i-learn.jp",           "path" => "/ping/");
$ping_list[] = array("host" => "www.blogpeople.net",       "path" => "/servlet/weblogUpdates");
$ping_list[] = array("host" => "www.hypernavi.com",        "path" => "/ping/");
$ping_list[] = array("host" => "rpc.pingomatic.com",       "path" => "/");
$ping_list[] = array("host" => "api.my.yahoo.co.jp",       "path" => "/rss/ping?u=http://syuzyou.info/feed");

// エラーになったPING
//$ping_list[] = array("host" => "ping.blo.gs",              "path" => "/");
//$ping_list[] = array("host" => "ping.dendou.jp",           "path" => "/");
//$ping_list[] = array("host" => "taichistereo.net",         "path" => "/xmlrpc/");
//$ping_list[] = array("host" => "ping.speenee.com",         "path" => "/xmlrpc");

foreach($ping_list as $ping){
    $flg = sendPing($ping['host'], $ping['path'],$title, $url);
    var_dump($ping['host']);
    var_dump($flg);
}
exit();


// ping送信を行う
function sendPing($host, $path, $title, $url) {

    // 送付用XML
    $title = htmlspecialchars($title);
    $xml =<<<PING
<?xml version="1.0"?>
<methodCall>
    <methodName>weblogUpdates.ping</methodName>
    <params>
        <param>
            <value>$title</value>
        </param>
        <param>
            <value>$url</value>
        </param>
    </params>
</methodCall>
PING;

    // POST内容
    $xmlLen = strlen($xml);
    $req = <<<REQ
POST $path HTTP/1.0
Host: $host
Content-Type: text/xml
Content-Length: $xmlLen

$xml
REQ;

    // 送信
    $s = @fsockopen($host, 80, $errNo, $errStr, 3);

    $res = "";
    if($s){
        fputs($s, $req);
        while(!feof($s)) {$res .= fread($s, 1024);}
    }
    // Ping送信先からの戻り内容を返す
    return $res;
}
?>
