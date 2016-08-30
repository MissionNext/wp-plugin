<?php
/**
 * Created by PhpStorm.
 * User: angelys
 * Date: 5/19/14
 * Time: 5:06 PM
 */

namespace MissionNext\frontend\controllers;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

abstract class AbstractLayoutController extends Controller {

    public $userId;
    public $user;
    public $userRole;
    public $flash;
    public $layout = 'layout.php';
    public $secured = true;
    /**
     * @var Context
     */
    public $context;
    public $route;

    /**
     * @var Api
     */
    protected $api;

    public function __construct(){
        parent::__construct();
        $this->api = Context::getInstance()->getApiManager()->getApi();
    }

    public function beforeAction(){

        if($this->secured && !get_current_user_id()){
            $this->redirect(wp_login_url(home_url($_SERVER['REQUEST_URI'])));
        }

        $this->userRole = get_user_meta(get_current_user_id(), Constants::META_ROLE, true);
        $this->userId = get_user_meta(get_current_user_id(), Constants::META_KEY, true);
        $this->user = Context::getInstance()->getUser()->getUser();

        $this->context = Context::getInstance();

        $this->route = $this->context->getRoutineManager()->getRouting()->route;
        $app_name = $this->context->getApiManager()->publicKey;

        if($this->secured && (!$this->user || !$this->userRole)){
            $this->redirect(wp_login_url(home_url($_SERVER['REQUEST_URI'])));
        }

        if($this->user && !current_user_can('manage_options')){
            //Activity
            if(!$this->user['is_active'] || !$this->user['is_active_app'] ){

                if($this->user['status'] == Constants::USER_STATUS_PENDING){
                    $this->redirect(get_permalink(get_page_by_path(Constants::PAGE_PENDING_APPROVAL)));
                }
            }

            //Payment
            if($this->route['controller'] != 'payment'){
                if(!$this->user['subscription']){
                    $all_subs = $this->api->getSubscriptionsForUser($this->userId);
                    $subscriptions = $this->api->getSubscriptionConfigs();

                    $current_sub_free = false;
                    $membership_level = null;

                    if (isset($this->user['profileData']['membership_level']) && !empty($this->user['profileData']['membership_level'])) {
                        $membership_level = strtolower(current($this->user['profileData']['membership_level']));
                    }

                    foreach($subscriptions as $sub) {
                        if ($sub['public_key'] == $app_name && Constants::ROLE_ORGANIZATION == $this->userRole) {
                            if (count($sub['sub_configs']) == 0) {
                                $current_sub_free = true;
                            } else {
                                $subscriptions_prices = false;
                                foreach($sub['sub_configs'] as $item) {
                                    if ($item['role'] == Constants::ROLE_ORGANIZATION && $item['partnership'] == $membership_level && ($item['price_month'] !=0 || $item['price_year'] != 0)) {
                                        $subscriptions_prices = true;
                                    }
                                }
                            }

                            if (!$subscriptions_prices) {
                                $current_sub_free = true;
                            }
                        } elseif ($sub['public_key'] == $app_name && Constants::ROLE_CANDIDATE == $this->userRole) {
                            foreach ($sub['sub_configs'] as $configItem) {
                                if ($configItem['role'] == Constants::ROLE_CANDIDATE && 0 == $configItem['price_month'] && 0 == $configItem['price_year']) {
                                    $current_sub_free = true;
                                    continue(2);
                                }
                            }
                        }
                    }

                    if (!$current_sub_free) {
                        if(!$all_subs){
                            $this->setMessage('notice', __("Please choose your subscription to proceed", Constants::TEXT_DOMAIN));
                            $this->redirect('/payment/first');
                        } else {
                            $this->setMessage('error', __("Choose your subscription", Constants::TEXT_DOMAIN));
                            $this->redirect('/payment/renew');
                        }
                    }

                } elseif($this->user['subscription']['status'] == Constants::SUBSCRIPTION_STATUS_EXPIRED){
                    $this->setMessage('error', __("Your subscription is expired, please renew it", Constants::TEXT_DOMAIN));
                    $this->redirect('/payment/renew');
                } elseif($this->user['subscription']['status'] == Constants::SUBSCRIPTION_STATUS_GRACE && $this->user['role'] != Constants::ROLE_CANDIDATE){
                    $this->setMessage('error', sprintf(__("You are under grace period, please %s renew %s your subscription", Constants::TEXT_DOMAIN), "<a href='/payment/renew'>", "</a>"));
                }
            }

            //Presence
            if($this->secured && !in_array($app_name, $this->user['app_names']) &&
                !($this->route['controller'] == 'profile' && $this->route['action'] == 'index') &&
                $this->route['controller'] != 'payment'
            ){
                $this->redirect('/profile');
            }
        }

    }

    public function afterAction(){

        if( $this->secured && !in_array($this->context->getApiManager()->publicKey, $this->user['app_names']) &&
            $this->route['controller'] == 'profile' && $this->route['action'] == 'index' && !current_user_can('manage_options')
        ){
            $this->layout = 'layout.php';
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $this->messages['notice'] = sprintf(__("Please complete your profile to reveal the majesty and power of %s", Constants::TEXT_DOMAIN), get_current_site()->site_name);
            }
        }
    }

    protected function getChangedFields($savedGroups, $submitedGroups)
    {
        $changedFields = [];

        foreach ($savedGroups as $group => $groupValue) {
            foreach ($groupValue->data as $key => $value) {
                if ('file' == $groupValue->fields[$key]->field['type']) {
                    continue;
                }

                if (is_array($value)) {
                    $savedArray = array_values($value);
                    sort($savedArray);

                    if (is_array($submitedGroups[$group][$key])) {
                        $submitArray = array_values($submitedGroups[$group][$key]);
                        sort($submitArray);
                    } else {
                        $submitArray = [ $submitedGroups[$group][$key] ];
                    }

                    if (count($savedArray) != count($submitArray)) {
                        $changedFields[] = $key;
                        continue;
                    }

                    if ($savedArray != $submitArray) {
                        $changedFields[] = $key;
                    }
                } elseif ($value != $submitedGroups[$group][$key]) {
                    $changedFields[] = $key;
                }
            }
        }

        return [
            'status' => 'checked',
            'changedFields' => $changedFields
            ];
    }
} 