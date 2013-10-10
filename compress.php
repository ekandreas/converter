<?php

$url = urldecode( $_REQUEST['url'] );
$file = substr( $url, strrpos( $url, '/' ) + 1 );
$folder = str_replace( '/' . $file, '', $url );
$folder = substr( $folder, strrpos( $folder, '/' ) +1 );
$dir = dirname(__FILE__) . '/server/files/' . $folder;

$ext = substr( $file, strrpos($file, '.') );
$new_file = str_replace( $ext, '-comp.pdf', $file );

$input = "./compress.sh \"" . $dir . "\" \"" . $file . "\" \"" . $new_file . "\"";

error_log( $input );

$output = shell_exec( $input );

if( file_exists( $dir . '/' . $new_file ) ){
    echo true;
}
else{
    echo false;
}

exit;