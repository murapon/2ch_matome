<?php
require dirname(__FILE__). "/config.php";
require dirname(__FILE__). "/lib/Article.php";
require dirname(__FILE__). "/lib/Contents.php";
require dirname(__FILE__). "/lib/Editing.php";
require dirname(__FILE__). "/lib/Tag.php";
require dirname(__FILE__). "/lib/ThreadExtraction.php";
require dirname(__FILE__). "/lib/Twitter.php";
require dirname(__FILE__). "/lib/htmltemplate.php";
require dirname(__FILE__). "/db/DBConnection.php";
require dirname(__FILE__). "/db/DBManager.php";
require dirname(__FILE__). "/db/ThreadInfo.php";
require dirname(__FILE__). "/db/WpPosts.php";
require dirname(__FILE__). "/db/WpTerms.php";
require dirname(__FILE__). "/db/WpTermRelationships.php";
require dirname(__FILE__). "/db/WpTermTaxonomy.php";

ini_set('max_execution_time', '1500');
date_default_timezone_set('Asia/Tokyo');

$condition = Contents::getCondition($argv[1]);
Article::make($condition['domain'], $condition['category'], $condition['category_id'], $condition['condition_time'], $condition['condition_count'], $condition['display_count'], $condition['type']);
exit();
?>
