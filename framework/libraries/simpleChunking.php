<?php
class simpleChunking {

    /* This object is meant to work within a custom MVC framework built off a
        standard apache build. The cache directory within the standard html
        directory is a specific location with open rwx (777) access. (All
        other folders are locked down.) */
    public function __construct($file='files')
    {
        $this->base = FILE . "html/cache/" . $file . '/';
    }

    public function upload($name=false, $file='fileToUpload')
    {
        $part = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_NUMBER_INT);
        $this->size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
        $this->chunk = filter_input(INPUT_POST, 'chunk', FILTER_SANITIZE_NUMBER_INT);
        $this->expected_parts = floor($this->size/$this->chunk);

        if ($name) {
            $this->name = $name;
        } else {
            $this->name = preg_replace("/[^a-zA-Z]/", '', $_FILES[$file]['name']);
        }
        if ($_FILES[$file]['error'] != 0) {
            exit('Error uploading file');
        }
        $tmp_name = $_FILES[$file]['tmp_name'];
        $file = fopen($this->base . $this->name . '_part_' . $part, "wb"); // write binary
        $in = fopen($tmp_name, "rb"); // read binary
        if ($in) {
            while ( $buff = fread( $in, $this->chunk ) ) {
                fwrite($file, $buff); // final file
            }
        }
        fclose($in);
        fclose($file);
        if ($part >= $this->expected_parts) {
            return $this->clean();
        } // else return NULL
    }

    public function compileParts($name=false, $expected_parts=false)
    {
        if ($name) { $this->name = $name; }
        if ($expected_parts) { $this->expected_parts = $expected_parts; }
        if (file_exists($this->base . $this->name)) {
            if (filter_input(INPUT_POST, 'prevent_overwrite', FILTER_SANITIZE_NUMBER_INT) == 1) {
                foreach (range(0, $this->expected_parts) as $i) {
                    unlink($this->base . $this->name . '_part_' . $i);
                }
                exit('This file already exists.');
            } else {
                echo ('Overwriting previous file. ');
            }
        }
        $file = fopen($this->base . $this->name, "wb"); // overwrite previous file
        foreach (range(0, $this->expected_parts) as $i) {
            $tmp_name = $this->base . $this->name . '_part_' . $i;
            $in = fopen($tmp_name, "rb"); // read binary
            if ($in) {
                while ( $buff = fread( $in, $this->chunk ) ) {
                    fwrite($file, $buff); // final file
                }
            } else { return false; }
            fclose($in);
            unlink($tmp_name);
        }
        return fclose($file);
    }

    public function clean()
    {
        if ($this->compileParts()) {
            if (filesize($this->base . $this->name) == $this->size) {
                echo ('Upload complete.');
                return $this;
            } // else
        } // else
        echo ('Upload failed. Please try again.'); return;
    }

}
?>
