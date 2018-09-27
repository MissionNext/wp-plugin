<?php
/**
 * @var $config
 * @var $userRole
 */

if($userRole == \MissionNext\lib\Constants::ROLE_ORGANIZATION){
    renderTemplate("payment/_first_roles", compact('config', 'app_id', 'fees_domain'));
} else {
    renderTemplate("payment/_first_no_roles", compact('config', 'app_id'));
}