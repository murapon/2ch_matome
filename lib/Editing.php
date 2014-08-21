<?php
class Editing {
    public static function removeStr($data, $remove_array){
        foreach($remove_array as $remove_data){
            $data = str_replace($remove_data, "", $data);
        }
        trim(mb_convert_kana($data, "s"));
        return $data;
    }

    public static function removePreg($data, $remove_array){
        foreach($remove_array as $remove_data){
            $data = preg_replace($remove_data, "", $data);
        }
        return $data;
    }

    public static function removeAdUrl($data){
        $data = self::removeStr($data, array("ime.nu/"));
        return $data;
    }


}