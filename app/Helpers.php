<?php

    function defaultimg($defupload){
        $path = $defupload == 1?'/defaultimg/boy.jpg':'/defaultimg/girl.jpg';
        return $path;
    }
