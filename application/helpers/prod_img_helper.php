<?php
if (!function_exists('default_img')) {
    function default_img($id,$thumb=false) {
        if(!$thumb)
        $dir = new DirectoryIterator('./img/products/'.$id);
        else
        $dir = new DirectoryIterator('./img/products/'.$id.'/thumb');
        $name=null;
        
        foreach ($dir as $directory) {

            if ($directory->isFile()) {
                return $directory->getFilename();
            }

        }
        return $name;
    }
}

if (!function_exists('get_imgs')) {
    function get_imgs($id) {
        $dir = new DirectoryIterator('./img/products/'.$id);
        $ret=array();


        foreach ($dir as $directory) {

            if ($directory->isFile()) {
                $ret[]= $directory->getFilename();
            }

        }
        return $ret;
    }
}