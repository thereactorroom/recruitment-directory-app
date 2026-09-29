<?php

final class WhatsApp {

    public static $whatsAppInstance = "7103611562";
    public static $whatsAppBaseURI = "https://7103.api.greenapi.com";
    public static $whatsAppApiToken = "1452e67187964466b6aab7deb89e928393aeef47d18840b38c";

    public static function send($type, $payload) {
        $curl = new Curl();
        $url = self::$whatsAppBaseURI . "/waInstance". self::$whatsAppInstance. "/{$type}/" . self::$whatsAppApiToken;
        $curl->postJson($url, json_encode($payload));
        return $curl->json();
    }

}