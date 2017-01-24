<?php
class simpleChunking {

    /* This object does NOT check the order of the blobs */

    public function __construct($file='files')
    {
        $this->base = FILE . "html/cache/" . $file . '/';
    }

    public function upload($name=false, $file='fileToUpload')
    {
        $part = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
        $this->size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);

        if ($name === false) {
            $name = preg_replace("/[^a-zA-Z]/", '', $_FILES[$file]['name']);
        }
        if ($_FILES[$file]['error'] != 0) {
            exit('Error uploading file');
        }
        $tmp_name = $_FILES[$file]['tmp_name'];
        $uploaded_to = $this->base . $name;
        $file = fopen($uploaded_to, "ab"); // append binary
        $in = fopen($tmp_name, "rb"); // read binary
        if ($in) {
            while ( $buff = fread( $in, 1048576 ) ) {
                fwrite($file, $buff); // final file
            }
        }
        fclose($in);
        fclose($file);
        if ($part >= floor($this->size/1048576)) {
            return $this->clean($uploaded_to);
        }
    }

    public function clean($name)
    {
        if (filesize($name) == $this->size) {
            echo ('Upload complete.'); return;
        } else {
            exit('Upload failed. Please try again.');
        }
    }

}
?>
