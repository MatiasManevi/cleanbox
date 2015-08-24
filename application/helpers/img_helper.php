<?php
if (!function_exists('default_img')) {
    function default_img($id,$thumb=false) {
        if(!$thumb)
            $dir = new DirectoryIterator('./img/gallery/'.$id);
        else
            $dir = new DirectoryIterator('./img/gallery/'.$id.'/thumb');
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
    function get_imgs($dir) {
        $dir = new DirectoryIterator($dir);
        $ret=array();


        foreach ($dir as $directory) {

            if ($directory->isFile()) {
                $ret[]= $directory->getFilename();
            }

        }
        return $ret;
    }
}
if (!function_exists('get_folders')) {
    function get_folders($dir='./img/gallery/') {
        $dir = new DirectoryIterator('./img/gallery/');
        $ret=array();


        foreach ($dir as $directory) {

            if ($directory->isDir() && !$directory->isDot()) {
                $ret[]= $directory->getFilename();
            }

        }
        return $ret;
    }
}
if (!function_exists('del_folder')) {
    function del_folder($nombre) {
        foreach(glob($nombre."/*") as $file) {
            if (is_dir($file)) del_folder($file);
            else unlink($file);
        }
        rmdir($nombre);
    }

}
if (!function_exists('isIn')) {
    function isIn($array,$comentario) {
        $ret= false;
        for($i=0; $i < count($array); $i++) {
            if($array[$i]== $comentario){
                $ret= true;
                break;
            }
        }
        return $ret;
    }

}
