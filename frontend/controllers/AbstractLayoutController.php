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
use MissionNext\lib\core\Logger;

abstract class AbstractLayoutController extends Controller {

    public $userId;
    public $user;
    public $userRole;
    public $flash;
    public $layout = 'layout.php';
    public $secured = true;
    public $profileCompleted = false;
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
        $this->domain = Context::getInstance()->getConfig()->get('domain');
    }

    public function beforeAction(){

        if($this->secured && !get_current_user_id()){
            $this->redirect(wp_login_url(home_url($_SERVER['REQUEST_URI'])));
        }

        $this->userRole = get_user_meta(get_current_user_id(), Constants::META_ROLE, true);
        $this->userId = get_user_meta(get_current_user_id(), Constants::META_KEY, true);
        $this->user = Context::getInstance()->getUser()->getUser();

        $checkProfileCompleted = $this->api->checkCompletedProfile($this->userId);
        $this->profileCompleted = $checkProfileCompleted['profile_completed'];

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

                    foreach($subscriptions as $sub) {
                        if ($sub['public_key'] == $app_name && Constants::ROLE_CANDIDATE == $this->userRole) {
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

                } elseif($this->user['subscription']['status'] == Constants::SUBSCRIPTION_STATUS_EXPIRED && Constants::ROLE_CANDIDATE !== $this->userRole){
                    $this->setMessage('error', __("Your subscription is expired, please renew it", Constants::TEXT_DOMAIN));
                    $this->redirect('/payment/renew');
                } elseif($this->user['subscription']['status'] == Constants::SUBSCRIPTION_STATUS_GRACE && $this->user['role'] != Constants::ROLE_CANDIDATE){
                    $this->setMessage('error', sprintf(__("You are under grace period, please %s renew %s your subscription", Constants::TEXT_DOMAIN), "<a href='/payment/renew'>", "</a>"));
                }
            }

            //Presence
            if ($this->secured &&
                !$this->profileCompleted &&
                !($this->route['controller'] == 'profile' &&
                $this->route['action'] == 'index') &&
                $this->route['controller'] != 'payment'
            ){
                $this->redirect('/profile?requestUri=' . $_SERVER['REQUEST_URI']);
            }
        }

    }

    public function afterAction(){

        if( $this->secured &&
            !$this->profileCompleted &&
            $this->route['controller'] == 'profile' &&
            $this->route['action'] == 'index' &&
            !current_user_can('manage_options')
        ){
            $this->layout = 'layout.php';
            if($_SERVER['REQUEST_METHOD'] == 'GET'){  // message adjusted by Nelson 5 October 2016
                $this->messages['notice'] = sprintf(__("<p style='font-size: 15px; font-weight: bold; color='#ffffff'>Already completed your profile? Click the Submit button to be directed to your Dashboard.</p>", Constants::TEXT_DOMAIN), get_current_site()->site_name);
                if (Constants::ROLE_CANDIDATE == $this->userRole) {
                    $this->subscriptions = $this->api->getSubscriptionsForUser($this->userId);
                    $this->apps = [
                        1   => 'http://new.missionnext.org',
                        2   => 'http://finishersproject.missionnext.org',
                        3   => 'http://explorenext.missionnext.org',
                        4   => 'http://journeydeepens.missionnext.org',
                        5   => 'http://bammatch.missionnext.org',
                        6   => 'http://teachnext.missionnext.org',
                    ];
                }
            }
        }
    }

    protected function getChangedFields($savedGroups, $submitedGroups)
    {
        $changedFields = [];

        foreach ($savedGroups as $group => $groupValue) {
            foreach ($groupValue->fields as $key => $fieldValue) {
                if ('file' == $fieldValue->field['type']) {
                    continue;
                }

                $value = isset($groupValue->data[$key]) ? $groupValue->data[$key] : null;
                if (is_array($value)) {
                    $savedArray = array_values($value);
                    sort($savedArray);

                    $submitArray = [ 0 => null ];
                    if (isset($submitedGroups[$group][$key])) {
                        if (is_array($submitedGroups[$group][$key])) {
                            $submitArray = array_values($submitedGroups[$group][$key]);
                            sort($submitArray);
                        } else {
                            $submitArray = [ $submitedGroups[$group][$key] ];
                        }
                    }

                    if (count($savedArray) != count($submitArray)) {
                        $changedFields[] = $key;
                        continue;
                    }

                    if ($savedArray != $submitArray) {
                        $changedFields[] = $key;
                    }
                } elseif (isset($submitedGroups[$group][$key]) && $value != $submitedGroups[$group][$key]) {
                    $changedFields[] = $key;
                }
            }
        }

        return [
            'status' => 'checked',
            'changedFields' => $changedFields
            ];
    }

    public function get_old_user() {
        $cookie = user_switching_get_olduser_cookie();
        if ( ! empty( $cookie ) ) {
            $old_user_id = wp_validate_auth_cookie( $cookie, 'logged_in' );

            if ( $old_user_id ) {
                return get_userdata( $old_user_id );
            }
        }
        return false;
    }

    protected function logger($log_type, $action, $message){
        $view_log = new Logger();
        $view_log->log('', '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-');
        $view_log->log($log_type, "$action: $message");
        $view_log->log('', '-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-');
    }
}