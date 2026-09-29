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
    protected $temppath = ENV_PATH . "images/temp/";

    public function saveTempImage($rawcontent) {
        $imgparts = explode(";base64,", $rawcontent);
        $imagetype = explode('/', $imgparts[0])[1];
        $base64image = base64_decode(str_replace(' ', '+', $imgparts[1]));

        $imagename = uniqid() . "_temp." . $imagetype;
        $imageurl = $this->tempurl . $imagename;
        $imagepath = $this->temppath . $imagename;

        $success = file_put_contents($imagepath, $base64image);
        if ($success && $this->canConvert($imagetype)) {
            list($imageurl, $imagepath) = $this->convertToJpg($imagepath, $imageurl, $imagetype);
        }

        if ($success) {
            return [
                "url" => $imageurl,
                "path" => $imagepath
            ];
        }
        return [];
    }

    public function moveTempImages($content, $images, $s_width, $host, $baseurl, $basepath){
        foreach ($images as $image) {
            $imagename = uniqid() . ".jpg";
            $newurl = $baseurl . $imagename;
            $newpath = $basepath . $imagename;
            if (copy($image["path"], $newpath)) {
                unlink($image["path"]);
                $newurl = "{$host}/scripts/mthumb/mthumb.php?src={$newurl}&w={$s_width}&q=100&zc=6";
                $content = str_replace($image["url"], $newurl, $content);
            }
        }
        return $content;
    }

    public function canConvert($imagetype){
        switch ($imagetype) {
            case "png":
            case "webp":
            case "gif":
                return true;
        }
        return false;
    }

    public function convertToJpg($imagepath, $imageurl, $type){
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

    public function extractBase64ImagesFromContent($content) {
        // test with post id: 1129 -> it has three base64 images
        $images = [];
        preg_match_all(
            '/<img\s+[^>]*src="data:image\/([^;]+);base64,([^"]+)"/i',
            $content,
            $matches,
            PREG_SET_ORDER
        );
        if (!empty($matches)) {
            foreach ($matches as $i => $match) {
                $images[] = [
                    'tag' => $match[0],
                    'type' => strtolower($match[1]),
                    'base64' => $match[2],
                ];
            }
        }
        return $images;
    }

    public function saveBase64ImagesToFile($content, $images, $s_width, $host, $baseurl, $basepath) {
        if (count($images) > 0) {
            foreach($images as $image) {
                $image_name = uniqid() . "." . $image['type'];
                $image_url = $baseurl . $image_name;
                $image_path = $basepath . $image_name;

                $base64image = base64_decode(str_replace(' ', '+', $image['base64']));
                $success = file_put_contents($image_path, $base64image);
                if ($success) {
                    $image_url = "{$host}/scripts/mthumb/mthumb.php?src={$image_url}&w={$s_width}&q=100&zc=6";
                    $img_tag = '<img src="' . $image_url . '"';
                    $content = str_replace($image['tag'], $img_tag, $content);
                }
            }
        }
        return $content;
    }

}
