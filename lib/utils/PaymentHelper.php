<?php


namespace MissionNext\lib\utils;


use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\GlobalConfig;

class PaymentHelper {

    public $first_payment;

    private $api;
    private $config;
    private $userRole;
    private $defaults;
    private $type;

    private $part_multiplier;

    private $discount_on;
    private $discount_value;
    private $coupon;
    private $fee;
    private $sites;
    private $price = array();

    public function __construct($config, $userRole, $defaults = array(), $type = 't', $coupon = null){

        $this->api = Context::getInstance()->getApiManager()->getApi();
        $this->userRole = $userRole;
        $this->defaults = $this->prepareDefaults($defaults);
        $this->type = $type;
        $this->setCoupon($coupon);

        $this->fee = GlobalConfig::getSubscriptionFee();
        $this->discount_value = GlobalConfig::getSubscriptionDiscount();
        $this->config = $this->prepareConfigs($this->api->getSubscriptionConfigs());

        $this->sites = $this->calcSites($config);

        if(!$this->sites){
            return false;
        }

        $this->discount_on = $this->calcDiscount($this->sites);
        $this->price = $this->calcPrice($this->sites);
    }

    public function getDiscount(){
        return $this->discount_on?$this->discount_value:false;
    }

    public function getCoupon(){
        return $this->coupon;
    }

    public function setCoupon($code){
        $coupon = $this->api->getCouponByCode($code);

        if($coupon && $coupon['is_active']){
            $this->coupon = $coupon;
        }
    }

    public function getSites(){
        return $this->sites;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getFee(){
        return $this->fee;
    }

    public function processPaymentData($data){

        $data['renew_type'] = $this->type;
        $data['subscriptions'] = array();

        foreach($this->sites as $site){
            $data['subscriptions'][] = array(
                'id' => $site['id'],
                'partnership' => $site['subscription']['partnership']
            );
        }

        $data['coupon'] = $this->coupon?$this->coupon:'';

        return $this->api->saveSubscription($data);
    }

    private function calcSites($config){

        $data = array();

        foreach($config as $site_id => $level){

            if($level == 'none'){
                continue;
            }

            if(!isset($this->config[$site_id]) ||
                !isset($this->config[$site_id]['sub_configs'][$level])

            ){
                return false;
            }

            $data[$site_id] = $this->config[$site_id];
            $data[$site_id]['subscription'] = $this->config[$site_id]['sub_configs'][$level];
        }

        return $data;
    }

    private function calcDiscount($sites){
        return count($sites) > 1;
    }

    private function calcPrice($data){

        $new_sites = array_diff_key($data, $this->defaults);
        $removed = array_diff_key($this->defaults, $data);
        $keeped = array_intersect_key($this->defaults, $data);

        $renew_price = 0;
        $new_price = 0;
        $old_price = 0;

        foreach($keeped as $id => $site){

            switch($this->type){
                case 'k' : {
                    break;
                }
                case 't' : {
                    $renew_price += $data[$id]['subscription']['price_year'] - $site['left_amount'];
                    break;
                }
                case 'e' : {
                    $renew_price += $data[$id]['subscription']['price_year'];
                    break;
                }
                case 'm' : {
                    $renew_price += $data[$id]['subscription']['price_month'];
                    $old_price += min($site['left_amount'], $data[$id]['subscription']['price_month']);
                    break;
                }
            }

        }

        foreach($new_sites as $id => $site){

            $part_price = $site['subscription']['price_year'] * $this->part_multiplier;

            switch($this->type){
                case 'k' : {
                    $new_price += $part_price;
                    break;
                }
                case 't' : {
                    $new_price += $data[$id]['subscription']['price_year'];
                    break;
                }
                case 'e' : {
                    $new_price += $data[$id]['subscription']['price_year'] + $part_price;
                    break;
                }
                case 'm' : {
                    $new_price += $data[$id]['subscription']['price_month'];
                    break;
                }
            }

        }

        foreach($removed as $id => $site){

            switch($this->type){
                case 'k' : {
                    $old_price += $site['left_amount'];
                    break;
                }
                case 't' : {
                    $old_price += $site['left_amount'];
                    break;
                }
                case 'e' : {
                    $old_price += $site['left_amount'];
                    break;
                }
                case 'm' : {
                    $old_price += $site['left_amount'];
                    break;
                }
            }

        }

        $total = 0;

        $compensation = $new_price - $old_price;
        $discount_percent = ( 100 - $this->discount_value ) / 100;

        if($this->type == 'm'){

            $compensation = ($this->discount_on? $new_price * $discount_percent : $new_price) - $old_price;

            $total = $new_price + $renew_price;

            if($this->discount_on){
                $total = $total * $discount_percent;
            }

            $total += $this->fee;

            if($old_price > 0){
                $this->first_payment = $compensation + ($this->discount_on ? $renew_price * $discount_percent : $renew_price);

                if($this->coupon){
                    $this->first_payment -= $this->coupon['value'];
                }

                if($this->first_payment < 0){
                    $this->first_payment = 0;
                }

                $this->first_payment = round($this->first_payment);

                $this->first_payment += $this->fee;
            }

        } else {

            if($this->discount_on){
                $total += ($renew_price + $new_price) * ( ( 100 - $this->discount_value ) / 100 );
            } else {
                $total += $renew_price + $new_price;
            }

            if($old_price > 0 && $new_price > 0){
                if($compensation > 0){
                    $total -= $old_price;
                } else {
                    $total -= $old_price + $compensation;
                }
            }

            if($this->coupon){
                $total -= $this->coupon['value'];
            }

            if($total < 0) {
                $total = 0;
            }
        }

        $total = round($total);

        return $total;
    }

    private function prepareConfigs($configs){

        $result = array();

        foreach($configs as $app){

            $result[$app['id']] = $app;
            $result[$app['id']]['configs'] = array();

            foreach($app['configs'] as $config){
                $result[$app['id']]['configs'][$config['key']] = $config['value'];
            }

            $result[$app['id']]['sub_configs'] = array();

            foreach($app['sub_configs'] as $sub_config){

                if($this->userRole == Constants::ROLE_AGENCY &&
                    isset($result[$app['id']]['configs'][Constants::CONFIG_AGENCY_TRIGGER]) &&
                    !$result[$app['id']]['configs'][Constants::CONFIG_AGENCY_TRIGGER]){
                    continue;
                }

                if($sub_config['role'] == $this->userRole ){
                    $result[$app['id']]['sub_configs'][$sub_config['partnership']?$sub_config['partnership']:Constants::PARTNERSHIP_BASIC] = $sub_config;
                }
            }

            if(!$result[$app['id']]['sub_configs']){
                unset($result[$app['id']]);
                continue;
            }
        }

        return $result;

    }

    private function prepareDefaults($_defaults){

        $defaults = array();

        $days_left = 0;
        $total_days = 0;

        foreach($_defaults as $default){

            $default['left_amount'] = round($default['paid'] * (  $default['days_left'] / ( (strtotime($default['end_date']) - strtotime($default['start_date'])) / (24*60*60) ) ));
            $defaults[$default['app_id']] = $default;

            if($default['partnership'] != Constants::PARTNERSHIP_LIMITED && $default['days_left'] > $days_left){
                $days_left = $default['days_left'];
                if(!$default['is_recurrent']){
                    $total_days = round( (strtotime($default['end_date']) - strtotime($default['start_date'])) / (24*60*60));
                }
            }
        }

        if($total_days <= 0){
            $total_days = date('L')?366:365;
        }

        $this->part_multiplier = $total_days > 0 ? $days_left / $total_days : 1;

        return $defaults;
    }

} 