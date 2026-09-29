<?php

class CommunityService
{
    protected $curl;
    protected $host;

    public function __construct($host, $curl)
    {
        $this->curl = $curl;
        $this->host = $host;
    }

    public function getCommunity($communityId)
    {
        $community = $this->curl->get(
            "{$this->host}/api/?communityId={$communityId}&action=getCommunity",
            true
        );

        return $community;
    }

    public function getConfig($communityId)
    {
        $config = $this->curl->get(
            "{$this->host}/api/?communityId={$communityId}&action=getCommunityConfig",
            true
        );

        return $config;
    }
}