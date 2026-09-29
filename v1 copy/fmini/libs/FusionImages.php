<?php

final class FusionImages {

    protected $tags = [
        "jpg" => '<img src="data:image/jpeg;base64,', 
        "png" => '<img src="data:image/png;base64,', 
        "webp" => '<img src="data:image/webp;base64,',
        "gif" => '<img src="data:image/gif;base64,',
    ];

    protected $mthumb = ENV_HOST . "/scripts/mthumb/mthumb.php?src=";
    protected $tempurl = ENV_HOST . "/modules/module_dev/images/temp/";
    protected $temppath = ENV_PATH . "/images/temp/";

    public function clean_base64($content, $modulepath) {
        $contains = $this->contains_base64($content);
        while($contains) {

            list($imagetype, $imagetag) = $this->content_parts($content);
            if ($imagetype == "bmp") { continue; }

            $imgparts = explode("base64,", $content);
            // get full raw base64 string
            $rawbase64 = $this->get_rawbase64($imgparts[1]);
            // convert to decoded base64 image
            $base64image = base64_decode(str_replace(' ', '+', $rawbase64));
            // this is the base64 from the rawcontent to be replaced with a new custome image holder
            $toreplace = $imagetag . $rawbase64 . '">';
            // find . -daystart -maxdepth 1 -mmin +10 -type f
            // find . -daystart -maxdepth 1 -mmin +10 -type f -iname "*.jpeg"
            // find . -newerct "2023-01-03 11:00:00" -not -newerct "2023-01-03 12:00:00+1"
            // find . -daystart -maxdepth 1 -mmin +10 -type f -iname "*.png" -delete
            // find . -daystart -maxdepth 1 -mmin +10 -type f -iname "*.png" -printf "%-25p %t\n"
            // find . -type f -iname "*.png" -newermt "2023-10-03 11:00:00" ! -newermt "2023-10-03 11:30:00"

            $imagename = uniqid() . "." . $imagetype;
            $imageurl = "https://uat.fusiononq.com/$modulepath" . $imagename;
            $imagepath = "/var/www/uat.fusiononq.com/$modulepath" . $imagename;

            // save the image to the directory location
            $success = file_put_contents($imagepath, $base64image);
            if ($success) {
                if ($this->can_convert($imagetype)) {
                    // echo "can convert: " . $imagetype;
                    $this->converttojpg($imagepath, $imageurl, $imagetype);
                    $imageurl = str_replace(".{$imagetype}", ".jpg", $imageurl);
                    $imagepath = str_replace(".{$imagetype}", ".jpg", $imagepath);
                    $imageurl = $this->mthumb . $imageurl . "&w=360&q=100&zc=6";
                } else if ($imagetype == "jpg" || $imagetype == "png"){
                    $imageurl = $this->mthumb . $imageurl . "&w=360&q=100&zc=6";
                }
                $replacewith = '<img src="' . $imageurl . '">';
                $content = str_replace($toreplace, $replacewith, $content);
            }

            $contains = $this->contains_base64($content);
        }
        return $content;
    }

    public function temp_image($rawcontent){
        $imgparts = explode(";base64,", $rawcontent);
        $imagetype = explode('/', $imgparts[0])[1];
        $base64image = base64_decode(str_replace(' ', '+', $imgparts[1]));

        $imagename = uniqid() . "_temp." . $imagetype;
        $imageurl = $this->tempurl . $imagename;
        $imagepath = $this->temppath . $imagename;

        $success = file_put_contents($imagepath, $base64image);
        if ($success && $this->can_convert($imagetype)) {
            list($imageurl, $imagepath) = $this->converttojpg($imagepath, $imageurl, $imagetype);
        }

        if ($success) {
            return [
                "url" => $imageurl,
                "path" => $imagepath
            ];
        }
        return [];
    }

    public function content_parts($content){
        $rtag = "";
        $rtype = "";
        foreach ($this->tags as $type => $tag) {
            if ($this->has_tag($content, $tag)) {
                $rtag = $tag;
                $rtype = $type;
                break;
            }
        }   
        return [$rtype, $rtag];
    }

    public function get_rawbase64($content) {
        $index = 0;
        for($i = 0; $i < strlen($content); $i++) {
            if ($content[$i] == '"') {
                break;
            }
            $index++;
        }
        return substr($content, 0, $index);
    }

    public function can_convert($imagetype){
        switch ($imagetype) {
            case "png":
            case "webp":
            case "gif":
                return true;
        }
        return false;
    }

    public function converttojpg($imagepath, $imageurl, $type){
        if ($type == "png") {
            $newimage = imagecreatefrompng($imagepath);
        } else if ($type == "webp") {
            $newimage = imagecreatefromwebp($imagepath);
        } else if ($type == "gif") {
            $newimage = imagecreatefromgif($imagepath);
        }

        $bg = imagecreatetruecolor(imagesx($newimage), imagesy($newimage));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $newimage, 0, 0, 0, 0, imagesx($newimage), imagesy($newimage));
        imagedestroy($newimage);

        $quality = 100; // 0 = low / smaller file, 100 = better / bigger file 

        $newimageurl = str_replace(".{$type}", ".jpg", $imageurl);
        $newimagepath = str_replace(".{$type}", ".jpg", $imagepath);
        
        imagejpeg($bg, $newimagepath, $quality);
        imagedestroy($bg);
        
        unlink($imagepath);

        return [$newimageurl, $newimagepath];
    }

    public function has_tag($content, $tag){
        if(strpos($content, $tag) !== false) {
            return true;
        }
        return false;
    }

    public function contains_base64($content){
        if (strpos($content, "base64,") != false) {
            return true;
        }
        return false;
    }

    public function handle_height_width($content){

        if(strpos($content, 'height="') !== false){
            $parts = explode('height="', $content)[1];
            $index = 0;
            for($i = 0; $i < strlen($parts); $i++){
                if ($parts[$i] == '"'){
                    break;
                }
                $index++;
            }
            $toreplace = 'height="' . substr($parts, 0, $index) . '"';
            $content = str_replace($toreplace, "", $content);
        }

        if(strpos($content, 'width="') !== false){
            $parts = explode('width="', $content)[1];
            $index = 0;
            for($i = 0; $i < strlen($parts); $i++){
                if ($parts[$i] == '"'){
                    break;
                }
                $index++;
            }
            $toreplace = 'width="' . substr($parts, 0, $index) . '"';
            $content = str_replace($toreplace, "", $content);
        }

        return $content;
    }

}



// foreach ($this->tags as $tag) {
//     if ($this->has_tag($content, $tag)) {
//         $img_parts = explode("base64,", $content);
//         $imagetype = $this->image_type($tag);

//         if ($imagetype == "bmp") { continue; }
        // $index = 0;
        // for($i = 0; $i < strlen($img_parts[1]); $i++) {
        //     if ($img_parts[1][$i] == '"') {
        //         break;
        //     }
        //     $index++;
        // }
//         $raw_base64 = substr($img_parts[1], 0, $index);

        // // this is the base64 from the rawcontent to be replaced with a new custome image holder
        // $to_replace = $tag . $raw_base64 . '">';

//         $image = str_replace(' ', '+', $raw_base64);
//         $base64image = base64_decode(str_replace(' ', '+', $raw_base64));
        
//         // $imagepath = "modules/module_dev/activity/images/";
        // $imagetype = $this->image_type($tag);
        // $imagename = uniqid() . "." . $imagetype;
        // $imageurl = "https://uat.fusiononq.com/$modulepath" . $imagename;
        // $imagepath = "/var/www/uat.fusiononq.com/$modulepath" . $imagename;

        // // save the image to the directory location
        // $success = file_put_contents($imagepath, $base64image);
        // if ($success) {
        //     if ($imagetype == "png" || $imagetype == "webp" || 
        //         $imagetype == "gif") {
        //         $this->converttotype($imagepath, $imagetype);
        //         $imageurl = str_replace(".{$imagetype}", ".jpg", $imageurl);
        //         $imagepath = str_replace(".{$imagetype}", ".jpg", $imagepath);

        //         $imageurl = $this->mthumb . $imageurl . "&w=360&q=100&zc=6";
        //         $replace_with = '<img src="' . $imageurl . '">';
        //         $content = str_replace($to_replace, $replace_with, $content);
        //     } else {
        //         $replace_with = '<img src="' . $imageurl . '">';
        //         $content = str_replace($to_replace, $replace_with, $content);
        //     }
        // }
//     }
// }



