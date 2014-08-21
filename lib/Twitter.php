<?php
require dirname(__FILE__). "/twitteroauth.php";

class Twitter {

    public static function tweet($tweet){
        //token文字列
        //https://dev.twitter.com/appsで発行したtokenを設定して下さい。
        $consumer_key       ="y3niGHJSpWlJe0ft567MNA";
        $consumer_secret    ="7ijE8CLbvOHn7ca5CYuzLdP3zxr5AuFVMRm2Hixw";
        $oauth_token        ="2280381848-9m4Vq45vl6gsEhWGuzOU0puI7TuY5rPEJtNnrD8";
        $oauth_token_secret ="lwG1FlKj49XEDjUXGDfXQbqT0bpAroGv29hx9b2jX0I1a";
        //TwitterOAuthのインスタンスを生成
        $twitter = new TwitterOAuth(
            $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret
        );

        //メソッドを指定(ここではつぶやくメソッドを指定)
        $method = "statuses/update";
        //パラメータを指定(ここではつぶやく文字列を指定)
        $parameters = array("status" => $tweet);
        //メソッドを実行(ここではつぶやきます。)
        $response = $twitter->post($method, $parameters);

        //戻り値取得
        $http_info = $twitter->http_info;
        $http_code = $http_info["http_code"];
        if($http_code == "200" && !empty($response)){
            //つぶやき成功
            return true;
        } else {
            //つぶやき失敗
            return false;
        }
    }
}