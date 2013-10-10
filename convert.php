<?php

$url = urldecode( $_REQUEST['url'] );
$file = substr( $url, strrpos( $url, '/' ) + 1 );
$folder = str_replace( '/' . $file, '', $url );
$folder = substr( $folder, strrpos( $folder, '/' ) +1 );
$dir = dirname(__FILE__) . '/server/files/' . $folder;

$command = "./convert_linux.sh";
if( strpos( $dir, 'Users/' ) ){
    $command = "./convert.sh";
}
$input = $command . " \"" . $dir . "\" \"" . $file . "\"";

error_log( $input );

$output = shell_exec( $input );

$ext = substr( $file, strrpos($file, '.') + 1 );
$new_file = str_replace( $ext, 'pdf', $file );

if( file_exists( $dir . '/' . $new_file ) ){
    echo true;
}
else{
    echo false;
}

exit;