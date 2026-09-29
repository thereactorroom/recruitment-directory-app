<?php

final class Utils {

    public static $member_pic_url = ENV_HOST . "/modules/member_profile/";

    public static $intent_code = "ServiceDirectory";

    public static function response($status = true, $message = "", $data = []) {
        return json_encode(["status" => $status, "message" => $message, "data" => $data]);
    }

    public static function getTime($datetime = null) {
        if ($datetime) {
            $date = new DateTime($datetime);
            return $date->format("H:i:s");
        } else {
            $now = new DateTime();
            return $now->format("H:i:s");
        }
    }

    public static function getTimeShort($datetime = null) {
        if ($datetime) {
            $date = new DateTime($datetime);
            return $date->format("H:i");
        } else {
            $now = new DateTime();
            return $now->format("H:i");
        }
    }

    public static function getDate($datetime = null) {
        if ($datetime) {
            $date = new DateTime($datetime);
            return $date->format("Y-m-d");
        } else {
            $now = new DateTime();
            return $now->format("Y-m-d");
        }
    }

    public static function getDateTime($datetime = null) {
        if ($datetime) {
            $date = new DateTime($datetime);
            return $date->format("Y-m-d H:i:s");
        } else {
            $now = new DateTime();
            return $now->format("Y-m-d H:i:s");
        }
    }

    public static function getWeekdayFromDate($datetime = null) {
        if ($datetime) {
            return date('D', strtotime($datetime));
        } else {
            return date('D', strtotime(self::getDate()));
        }
    }

    public static function formateDateTime($datetime) {
        if(!$datetime){
            return null;
        }
        $date = new DateTime($datetime);
        return $date->format("d M, Y") . " at " . $date->format("H:i:s");
    }

    public static function formateDate($datetime) {
        if(!$datetime){
            return null;
        }
        $date = new DateTime($datetime);
        return $date->format("d M, Y");
    }

    public static function isValidUnixTimestamp($unix_timestamp){
        return (1 === preg_match( '~^[1-9][0-9]*$~', $unix_timestamp));
    }

    public static function formatUnixDate($unix_date){
        if(!$unix_date){
            return null;
        }
        if(self::isValidUnixTimestamp($unix_date)) {
            return date("Y-m-d", $unix_date);
        }
        else {
            return $unix_date;
        }
    }

    public static function formatUnixDatetime($unix_datetime){
        if(!$unix_datetime){
            return null;
        }
        if(self::isValidUnixTimestamp($unix_datetime)) {
            return date("Y-m-d H:i:s", $unix_datetime);
        }
        else {
            return $unix_datetime;
        }
    }

    public static function incrementDate($increment, $date = null) {
        if (!$date) {
            $date = new DateTime(self::getDate());
        } else {
            $date = new DateTime($date);
        }
        if (!empty($increment)) {
            $date->modify($increment);
        }
        return $date->format("Y-m-d");
    }

    public static function incrementDateTime($increment, $datetime = null) {
        if (!$datetime) {
            $datetime = new DateTime(self::getDateTime());
        } else {
            $datetime = new DateTime($datetime);
        }
        if (!empty($increment)) {
            $datetime->modify($increment);
        }
        return $datetime->format("Y-m-d H:i:s");
    }

    public static function getDOBFromIdentity($identity) {
        // substring identity to get bday
        $date = substr($identity, 0, 6);
    
        // use built-in DateTime object to work with dates
        $date = DateTime::createFromFormat('ymd', $date);
        $now = new DateTime();
    
        // compare birth date with current date: 
        // if it's bigger bd was in previous century
        if ($date > $now) {
            $date->modify('-100 years');
        }
        
        return $date->format("Y-m-d");
    }

    public static function getGenderFromIdentity($identity) {
        $gender = (int) substr($identity, 6, 1);
        return ($gender >= 0 && $gender <= 4) ? 'Female' : 'Male';
    }

    public static function memPicUrl($pic){
        return ENV_HOST . "/modules/members-profile/images/" . $pic;
    }

    public static function getAgeFromDOB($dob) {
        $birthdate = new DateTime($dob);
        $date = new DateTime();
        $interval = $date->diff($birthdate);
        return $interval->y;
    }

    public static function strContains($haystack, $needle){
        if(strpos($haystack, $needle) !== false){
            return true;
        }
        return false;
    }

    public static function getMember($userId, $communityId) {
        $curl = new Curl();
        $query = ENV_HOST . "/modules/members-management-v2/api?action=getMember&userId=$userId&communityId=$communityId";
        $member = $curl->get($query, true);
        return $member->member;
    }

    public static function getPractitioner($community_id, $user_id, $country_code, $mobile){
        $curl = new Curl();
        $query = ENV_HOST . "/api/?action=getPracticeUser&communityId=$community_id&userId=$user_id&countryCode=$country_code&mobile=$mobile";
        $practitioner = $curl->get($query, true);
        return $practitioner->practitioner;
    }

    public static function checkWhatsApp($code, $mobile){
        $curl = new Curl();
        $url = "https://uat.fusiononq.com/modules/members-add-v4";
        if(!SANDBOX) {
            $url = "https://app.fusiononq.com/modules/members-add-v4";
        } 
        $query = $url . "/api?action=checkWhatsAppNumber&countryCode=$code&mobile=$mobile";
        $response = $curl->get($query, true);
        return (bool)$response->result;
    }

    public static function injectCSS($file = "", $primary = "", $secondary = "", $unique_key = ""){
        $css_content = "";
        if (file_exists($file)){
            $css_content = file_get_contents($file);
            if (Utils::strContains($css_content, "pri_key")){
                $css_content = str_replace("pri_key", $primary, $css_content);
            }
    
            if (Utils::strContains($css_content, "sec_key")){
                $css_content = str_replace("sec_key", $secondary, $css_content);
            }
    
            if (Utils::strContains($css_content, ":unq_key")){
                $css_content = str_replace(":unq_key", $unique_key, $css_content);
            }
        }
        return $css_content;
    }
    
}