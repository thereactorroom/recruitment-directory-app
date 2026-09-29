<?php

$config = require 'bootstrap.php';

require_once 'fmini/services/ViewsService.php';
require_once 'fmini/services/CommunityService.php';
$service = new CommunityService($config['envHost'], new Curl());

$get = (object) $_GET;
$response = array_merge([
    'name' => $get->name ?? '',
    'title' => $get->title ?? 'Recruitment',
    'icon' => '',
    'communityId' => $get->communityId,
    'contentId' => $get->contentId,
    'uniqueKey' => "{$get->communityId}{$get->contentId}",
], $config);

$community = $service->getCommunity($get->communityId);
if ((bool)$community->result) {
    $response['name'] = $community->community->name;
    $response['icon'] = $community->community->icon ?? '';
    $response['category'] = $community->community->category ?? '';
    $response['type'] = $community->community->type ?? '';
    $response['status'] = $community->community->status ?? '';
    $response['perm'] = $community->community->perm ?? '';
}

$communityConfig = $service->getConfig($get->communityId);
if ((bool)$communityConfig->result) {
    $response['primaryColor'] = $communityConfig->config->primaryColor ?? '#ed494b';
    $response['secondaryColor'] = $communityConfig->config->secondaryColor ?? '#9ccc65';
}

$viewsService = new ViewsService(
    $config['modulePath'] . "/assets/css/main.css", 
    $response['primaryColor'], 
    $response['secondaryColor'], 
    $response['uniqueKey']
);
$response['cssContent'] = $viewsService->css();

return $response;

