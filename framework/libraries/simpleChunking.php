<?php
class simpleChunking {

    public function __construct($file='files/')
    {
        $this->base = FILE . "html/cache/" . $file;
    }

    public function upload($name, $part, $file='fileToUpload')
    {
        $tmp_name = $_FILES['fileToUpload']['tmp_name'];

        $com = fopen($this->base . $name, "ab"); // append binary

        $temp_dir = $this->base . $name . '_part/';
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777, true);
        }
        $out = fopen($temp_dir . '/blob_' . $part, "wb"); // write binary

        if ( $out ) {
            // Read binary input stream and append it to temp file
            $in = fopen($tmp_name, "rb");
            if ( $in ) {
                while ( $buff = fread( $in, 1048576 ) ) {
                    fwrite($out, $buff); // temp_dir
                    fwrite($com, $buff); // final file
                }
            }
            fclose($in);
            fclose($out);
        }
        fclose($com);
        return;
    }

    // public function rrmdir($dir) {
    //     if (is_dir($dir)) {
    //         $objects = scandir($dir);
    //         foreach ($objects as $object) {
    //             if ($object != "." && $object != "..") {
    //                 if (filetype($dir . "/" . $object) == "dir") {
    //                     rrmdir($dir . "/" . $object);
    //                 } else {
    //                     unlink($dir . "/" . $object);
    //                 }
    //             }
    //         }
    //         reset($objects);
    //         rmdir($dir);
    //     }
    // }

    // public function createFileFromChunks($temp_dir, $fileName, $chunkSize, $totalSize, $total_files) {
    //     // count all the parts of this file
    //     $total_files_on_server_size = 0;
    //     $temp_total = 0;
    //     foreach(scandir($temp_dir) as $file) {
    //         $temp_total = $total_files_on_server_size;
    //         $tempfilesize = filesize($temp_dir.'/'.$file);
    //         $total_files_on_server_size = $temp_total + $tempfilesize;
    //     }
    //     // check that all the parts are present
    //     // If the Size of all the chunks on the server is equal to the size of the file uploaded.
    //     if ($total_files_on_server_size >= $totalSize) {
    //     // create the final destination file
    //         if (($fp = fopen('temp/'.$fileName, 'w')) !== false) {
    //             for ($i=1; $i<=$total_files; $i++) {
    //                 fwrite($fp, file_get_contents($temp_dir.'/'.$fileName.'.part'.$i));
    //                 _log('writing chunk '.$i);
    //             }
    //             fclose($fp);
    //         } else {
    //             _log('cannot create the destination file');
    //             return false;
    //         }
    //
    //         // rename the temporary directory (to avoid access from other
    //         // concurrent chunks uploads) and than delete it
    //         if (rename($temp_dir, $temp_dir.'_UNUSED')) {
    //             rrmdir($temp_dir.'_UNUSED');
    //         } else {
    //             rrmdir($temp_dir);
    //         }
    //     }
    // }

    public function clean()
    {
        // check final size of file vs expected size of file
        // delete temporary directory
        unlink(FILE . 'html/cache/files/blob');
    }

}
?>
