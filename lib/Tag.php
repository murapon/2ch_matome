<?php
class Tag {

    public static function addTag($name){
        // タグの存在を確認しなければ新規登録、あればそれを利用する。
        $terms = WpTerms::get($name);
        if(count($terms) > 0 && array_key_exists('term_id', $terms[0])){
            $term_id = $terms[0]['term_id'];
        } else {
            $term_id = WpTerms::insert($name);
        }
        $term_taxonomy = WpTermTaxonomy::get($term_id, "post_tag");
        if(count($term_taxonomy) > 0 && array_key_exists('term_taxonomy_id', $term_taxonomy[0])){
            $tag_id = $term_taxonomy[0]['term_taxonomy_id'];
        } else {
            $tag_id = WpTermTaxonomy::insert($term_id, "post_tag", 1);
        }
        return $tag_id;
    }
}