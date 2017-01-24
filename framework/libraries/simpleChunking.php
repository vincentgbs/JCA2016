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

        $com = fopen($target_path . $complete, "ab");
        error_log($target_path);

        // Open temp file
        $out = fopen($target_path . '/blob' . $_POST['count'], "wb");

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

    public function clean()
    {
        // check final size of file vs expected size of file
        unlink(FILE . 'html/cache/files/blob');
    }

}
?>
