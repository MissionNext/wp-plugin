<?php


namespace MissionNext\frontend\controllers;


use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\form\PaymentForm;
use MissionNext\lib\UserLib;
use MissionNext\lib\utils\PaymentHelper;

class paymentController extends AbstractLayoutController {

    public function first(){

        if($this->user['subscription'] != null){
            $this->redirect('/dashboard');
        }

        $config = $this->api->getSubscriptionConfigs();

        $this->config = $this->prepareConfigsForFirstPayment($config);

        $free = array();
        $currentAppId = $this->getCurrentSiteId($config, Context::getInstance()->getApiManager()->publicKey);

        foreach($config as $app){
            foreach($app['sub_configs'] as $sub_config){
                if($sub_config['role'] == $this->userRole && $sub_config['price_year'] == 0 && $this->userRole != Constants::ROLE_CANDIDATE){
                    $free[$app['id']] = array(
                        'id' => $app['id'],
                        'partnership' => $sub_config['partnership']
                    );
                }
                if($sub_config['role'] == $this->userRole && $this->userRole == Constants::ROLE_CANDIDATE && $currentAppId == $app['id']){
                    $free[$app['id']] = array(
                        'id' => $app['id'],
                        'partnership' => $sub_config['partnership']
                    );
                }
            }
        }

        if($free && !$this->config){
            $response = $this->processFreeSites($free);
            $this->redirect('/dashboard');
        }

        $this->app_id = $currentAppId;

    }

    public function firstProcess(){

        if($this->user['subscription'] != null){
            $this->redirect('/dashboard');
        }

        if(!isset($_GET['a']) || !$_GET['a']){
            $this->setMessage('notice', __("You must choose at least one site!", Constants::TEXT_DOMAIN));
            $this->redirect("/payment/first");
        }

        $helper = new PaymentHelper($_GET['a'], $this->userRole, $defaults = array(), $type = 't', isset($_GET['c'])?$_GET['c']:null);

        $data = $helper->getSites();

        if(!$data){
            $this->setMessage('notice', __("You must choose at least one site!", Constants::TEXT_DOMAIN));
            $this->redirect('/payment/first');
        }

        $total_price = $helper->getPrice();

        if($total_price <= 0){
            $recurring = false;

            if ($this->userRole == Constants::ROLE_CANDIDATE) {
                $recurring = true;
            }

            $helper->processPaymentData(array(
                'user_id' => $this->userId,
                'recurring' => $recurring,
                'period' => 'year',
                'type' => $_GET['payment_type'],
                'coupon' => $helper->getCoupon()
            ));

            $this->redirect("/dashboard");
        }

        $this->coupon = $helper->getCoupon();
        $this->data = $data;
        $this->total = $total_price;
        $this->fee = $helper->getFee();
        $this->discount = $helper->getDiscount();

        $this->form = new PaymentForm(array(
            'period' => 'year',
            'echeck' => $_GET['payment_type'] == 'echeck',
            'card'      => $_GET['payment_type'] == 'cc'
        ));

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->form->changedFields = $this->getChangedFields($this->form->groups, @$_POST[$this->form->getName()]);

            $this->form->bind($_POST[$this->form->getName()]);

            if($this->form->isValid()){
                if($this->form->process($helper)){
                    $this->setMessage('notice', __("Payment successful. Now your profile must be completed in order to post jobs and receive candidate matches. ", Constants::TEXT_DOMAIN), 1);
                    $this->redirect('/profile');
                }
            }
        }
    }

    public function renew(){

        $config = $this->api->getSubscriptionConfigs();

        $this->config = $this->prepareConfigsForRenewPayment($config);

        $_defaults = $this->api->getSubscriptionsForUser($this->userId);

        $days_left = 0;
        $total_days = 0;

        $defaults = array();

        foreach($_defaults as $default){

            $default['left_amount'] = round($default['paid'] * (  $default['days_left'] / ( (strtotime($default['end_date']) - strtotime($default['start_date'])) / (24*60*60) ) ));
            $defaults[$default['app_id']] = $default;

            if( $default['app']['public_key'] == Context::getInstance()->getApiManager()->publicKey && $default['days_left'] > $days_left){
                $days_left = $default['days_left'];
                if(!$default['is_recurrent']){
                    $total_days = round( (strtotime($default['end_date']) - strtotime($default['start_date'])) / (24*60*60));
                }
            }
        }

        if($total_days <= 0){
            $total_days = date('L')?366:365;
        }

        foreach($this->config as $key => $c){

            $this->config[$key]['new_price'] = $days_left;

        }

        $this->defaults = $defaults;
        $this->days_left = $days_left;
        $this->total_days = $total_days;
    }

    public function processRenew(){

        if(!isset($_GET['a']) || !$_GET['a'] ||
            !isset($_GET['p']) || !in_array($_GET['p'], array('year', 'month')) ||
            !isset($_GET['rt']) || !in_array($_GET['rt'], array('k', 't', 'e', 'm'))){
            $this->redirect("/payment/renew");
        }

        $helper = new PaymentHelper($_GET['a'], $this->userRole, $this->api->getSubscriptionsForUser($this->userId), $_GET['rt'], isset($_GET['c'])?$_GET['c']:null );

        $data = $helper->getSites();

        if(!$data){
            $this->setMessage('notice', __("You must choose at least one site!", Constants::TEXT_DOMAIN));
            $this->redirect('/payment/renew');
        }

        $total_price = $helper->getPrice();

        if($total_price <= 0){

            $recurring = $_GET['p'] == 'month';

            if ($this->userRole == Constants::ROLE_CANDIDATE) {
                $recurring = true;
            }

            $helper->processPaymentData(array(
                'user_id' => $this->userId,
                'recurring' => $recurring,
                'period' => $_GET['p'],
                'type' => $_GET['payment_type'],
                'coupon' => $helper->getCoupon()
            ));

            $this->redirect("/dashboard");
        }

        $this->coupon = $helper->getCoupon();
        $this->data = $data;
        $this->total = $total_price;
        $this->fee = $_GET['p'] == 'month' ? $helper->getFee() : 0;
        $this->discount = $helper->getDiscount();
        $this->first_payment = $helper->first_payment;
        $this->type = $_GET['rt'];

        if($this->first_payment !== null && $this->coupon){
            $this->first_payment -= $this->coupon['value'];
            $this->first_payment = $this->first_payment > 0 ? $this->first_payment : 0;
        }

        $this->form = new PaymentForm(array(
            'echeck' => $_GET['payment_type'] == 'echeck',
            'card' => $_GET['payment_type'] == 'cc',
            'period' => $_GET['p']
        ));

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $this->form->changedFields = $this->getChangedFields($this->form->groups, @$_POST[$this->form->getName()]);

            $this->form->bind($_POST[$this->form->getName()]);

            if($this->form->isValid()){
                if($this->form->process($helper)){
                    $params = [];
                    $params['%organization_name%'] = ('organization' == $this->userRole) ? UserLib::getUserOrganizationName($this->user) : UserLib::getAgencyFullName($this->user);
                    $params['%to_email%'] = $this->user['email'];
                    if (in_array(Context::getInstance()->getApiManager()->publicKey, ['canada', 'explorenext'])) {
                        $params['%subject%'] = 'Thank You for Your ExploreNext Partnership Renewal';
                    } elseif ("teachnext" == Context::getInstance()->getApiManager()->publicKey) {
                        $params['%subject%'] = 'Thank You for Your TeachNext Partnership Renewal';
                    }

                    $this->sendRenewEmail($params);

                    $params['%to_email%'] = 'headquarters@missionnext.org';
                    $this->sendRenewEmail($params);

                    $this->setMessage('notice', __("Payment successful. Thank you for renewing your subscription. Be sure the profile and job entries are up-to-date.", Constants::TEXT_DOMAIN), 1);
                    $this->redirect('/profile');
                } else {
                    $params['%organization_name%'] = ('organization' == $this->userRole) ? UserLib::getUserOrganizationName($this->user) : UserLib::getAgencyFullName($this->user);
                    $subject = ('organization' == $this->userRole) ? $_SERVER['SERVER_NAME']." Subscription Renewal Attempt" : $_SERVER['SERVER_NAME']." Agency Rep Renewal Attempt";

                    $this->sendFailRenewEmail($params, $this->userRole, $subject, $this->user['email']);
                }
            }
        }
    }

    public function checkCoupon(){

        if(!isset($_POST['code'])){
            $this->forward404();
        }

        $coupon = $this->api->getCouponByCode($_POST['code']);

        echo json_encode($coupon);

        return false;
    }

    private function processFreeSites($apps, $coupon = ''){
        $recurring = false;

        if ($this->userRole == Constants::ROLE_CANDIDATE) {
            $recurring = true;
        }

        $data = array(
            'user_id' => $this->userId,
            'recurring' => $recurring,
            'period' => 'year',
            'coupon' => $coupon,
            'subscriptions' => $apps,
            'type' => 'cc',
            'renew_type' => 't'

        );

        return $this->api->saveSubscription($data);
    }

    private function prepareConfigsForFirstPayment($apps){

        $result = array();

        foreach($apps as $app){

            $result[$app['id']] = $app;
            $result[$app['id']]['configs'] = array();

            foreach($app['configs'] as $config){
                $result[$app['id']]['configs'][$config['key']] = $config['value'];
            }

            if($this->userRole == 'agency' && ( !isset($result[$app['id']]['configs'][Constants::CONFIG_AGENCY_TRIGGER]) || !$result[$app['id']]['configs'][Constants::CONFIG_AGENCY_TRIGGER])){
                unset($result[$app['id']]);
                continue;
            }

            $result[$app['id']]['sub_configs'] = array();

            foreach($app['sub_configs'] as $sub_key => $sub_config){
                if($sub_config['role'] == $this->userRole && $sub_config['price_year'] != 0 ){
                   $result[$app['id']]['sub_configs'][$sub_config['partnership']?$sub_config['partnership']:$sub_key] = $sub_config;
                }
            }

            if(!$result[$app['id']]['sub_configs']){
                unset($result[$app['id']]);
                continue;
            }
        }

        return $result;
    }

    private function prepareConfigsForRenewPayment($apps){

        $result = array();

        foreach($apps as $app){

            $result[$app['id']] = $app;
            $result[$app['id']]['configs'] = array();

            foreach($app['configs'] as $config){
                $result[$app['id']]['configs'][$config['key']] = $config['value'];
            }

            if($this->userRole == 'agency' && ( !isset($result[$app['id']]['configs'][Constants::CONFIG_AGENCY_TRIGGER]) || !$result[$app['id']]['configs'][Constants::CONFIG_AGENCY_TRIGGER])){
                unset($result[$app['id']]);
                continue;
            }

            $result[$app['id']]['sub_configs'] = array();

            foreach($app['sub_configs'] as $sub_key => $sub_config){
                if($sub_config['role'] == $this->userRole && $sub_config['price_year'] != 0 && $sub_config['price_month'] != 0 && ($this->userRole == Constants::ROLE_ORGANIZATION && $sub_config['partnership_status'] || $this->userRole != Constants::ROLE_ORGANIZATION)){
                    $result[$app['id']]['sub_configs'][$sub_config['partnership']?$sub_config['partnership']:$sub_key] = $sub_config;
                }
            }

            if(!$result[$app['id']]['sub_configs']){
                unset($result[$app['id']]);
                continue;
            }
        }

        return $result;
    }

    private function getCurrentSiteId($configs, $public_key){

        $site_id = 0;

        foreach($configs as $config){

            if($config['public_key'] == $public_key){
                $site_id = $config['id'];
            }

        }

        return $site_id;
    }

    public function addSubscription($params){
        $free[$params[0]] = [
            'id'            => $params[0],
            'partnership'   => ''
        ];

        $recurring = false;

        if ($this->userRole == Constants::ROLE_CANDIDATE) {
            $recurring = true;
        }

        $data = array(
            'user_id' => $this->userId,
            'recurring' => $recurring,
            'period' => 'year',
            'coupon' => '',
            'subscriptions' => $free,
            'type' => 'cc',
            'renew_type' => 't'

        );

        $this->api->addSubscription($data);

        $this->redirect('/dashboard');
    }

    private function sendRenewEmail($params){
        $mail_service = Context::getInstance()->getMailService();

        $messageText = Context::getInstance()->getLocalizationManager()->getLocalizedEmail('subscription_renew.txt');
        foreach ($params as $item => $value) {
            $messageText = str_replace($item, $value, $messageText);
        }

        $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];
        $mail_service->send($params['%to_email%'], $params['%subject%'], $messageText);
        $this->logger('email', 'sent', "Send message about subscription renewal of user ".$params['%to_email%']);
    }

    private function sendFailRenewEmail($params, $role, $subject, $user) {
        $mail_service = Context::getInstance()->getMailService();

        $messageText = Context::getInstance()->getLocalizationManager()->getLocalizedEmail($role."_renew_fail.txt");
        foreach ($params as $item => $value) {
            $messageText = str_replace($item, $value, $messageText);
        }

        $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];
        $mail_service->send('headquarters@missionnext.org', $subject, $messageText);
        $this->logger('email', 'sent', "Send message about failed renewal of user $user.");
    }
} 