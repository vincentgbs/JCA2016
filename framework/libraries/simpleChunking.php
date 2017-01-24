<?php
class simpleChunking {

    public function __construct()
    {
        //
    }

    public function upload($complete, $file='fileToUpload', $target='files/')
    {
        $target_path = FILE . "html/cache/files/";
        $tmp_name = $_FILES['fileToUpload']['tmp_name'];
        $size = $_FILES['fileToUpload']['size'];
        $name = $_FILES['fileToUpload']['name'];

        $target_file = $target_path . basename($name);

        $com = fopen($target_path . $complete, "ab");
        error_log($target_path);

        // Open temp file
        $out = fopen($target_file, "wb");

        if ( $out ) {
            // Read binary input stream and append it to temp file
            $in = fopen($tmp_name, "rb");
            if ( $in ) {
                while ( $buff = fread( $in, 1048576 ) ) {
                    fwrite($out, $buff);
                    fwrite($com, $buff);
                }
            }
            fclose($in);
            fclose($out);
        }
        fclose($com);
        return;
    }

}
?>
