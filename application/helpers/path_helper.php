<?php
/*
    Document   : path_helper
    Created on : 14-dic-2010, 18:14:43
    Author     : Rodrigo E. Torres
    Sobrelaweb Web Developer
    rtorres@sobrelaweb.com | torresrodrigoe@gmail.com
*/
if (!function_exists('asset_url'))
{
    function asset_url()
    {
        $CI =& get_instance();
        return base_url() . $CI->config->item('asset_path');
    }
}
if (!function_exists('img_url'))
{
    function img_url()
    {
        $CI =& get_instance();
        return base_url() . $CI->config->item('img_path');
    }
}
?>
