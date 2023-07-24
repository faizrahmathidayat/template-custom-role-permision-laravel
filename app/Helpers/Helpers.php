<?php

if (!function_exists('uploadPath')) {
    function uploadPath($path = null)
    {
        return rtrim(app()->basePath('public/uploads/' . $path), '/');
    }
}

if (!function_exists('urlUpload')) {
    function urlUpload($path = null)
    {
        return url('uploads/' . $path);
    }
}