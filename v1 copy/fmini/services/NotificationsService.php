<?php

class NotificationsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    // public function skipIfMe($input){
    //     $postsModel = new PostsModel($this->db);
    //     $post = $postsModel->get($input->post_id);

    //     if ($post && (int)$post->user_key_id === (int)$input->user_key_id) {
    //         return true;
    //     }
    //     return false;
    // }

    public function sendListingReminder($listing, $message) {
        $user_ids[] = $listing->user_id;
        $results = $this->getUserDeviceTokens($user_ids);
        
        if ((bool)$results->result && count($results->tokens) > 0) {
            $tokens = $results->tokens;
            $unique_tokens = array_values(array_unique($tokens));

            $data = "";
            $intent_code = $this->getIntentCode($listing->community_id, $listing->content_id);
            if (!empty($intent_code)) {
                $data = '{"breadcrumbs":"' . $intent_code . '"}';
            }
            $this->sendPushNotification (
                "Service Directory",
                $message,
                $unique_tokens,
                $data
            );
        }
        return true;
    }

    public function getUserDeviceTokens($userIds) {
        $payload = [
            "action" => "getTokensByUserIds",
            "userIds" => $userIds
        ];
        $this->curl->post(ENV_HOST . "/api/", $payload);
        return $this->curl->json();
    }

    public function sendPushNotification($title = "", $message = "", $tokens = [], $data = "") {
        $payload = [
            "action" => "sendPushNotification",
            "title" => $title,
            "message" => $message,
            "tokens" => $tokens,
        ];

        if ($data != "") {
            $payload["data"] = $data;
        }

        $this->curl->post(ENV_HOST . "/api/", $payload);
        $response = $this->curl->json();

        return true; //$response['result'];
    }

    public function getIntentCode($community_id, $content_id){
        $code = str_replace(' ', '', $community_id) . $this->utils::$intent_code;

        $intent = $this->getIntent($code);
        if (!$intent) {
            $intent = $this->createIntent($community_id, $content_id, $code);
        }

        return $intent->code;
    }

    protected function getIntent($code){
        $response = $this->curl->get(ENV_HOST . "/api/?action=getIntent&code=$code", true);
        return (bool)$response->result ? $response->intent : false;
    }

    protected function createIntent($community_id, $content_id, $code) {
        $instruction = [
            [
                "type" => "community",
                "communityId" => $community_id
            ],
            [
                "data" => [
                    "action" => "Launch noticeboard."
                ],
                "type" => "content",
                "trigger" => "click",
                "contentId" => $content_id,
                "communityId" => $community_id
            ]
        ];

        $payload = [
            "action" => "addIntent",
            "code" => $code,
            "instruction" => $instruction,
            "status" => "Active"
        ];
        $this->curl->post(ENV_HOST . "/api/", $payload);

        if ($this->curl->error()) {
            return false;
        }

        $response = $this->curl->json();
        return (bool)$response->result ? $response->intent : false;
    }

}