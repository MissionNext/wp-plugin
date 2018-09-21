<?php


namespace MissionNext\lib;


class UserLib extends ProfileLib {

    public static function getUserFullName($user){
        if(isset($user['profileData'][Constants::$predefinedFields[$user['role']]['first_name']])
            || isset($user['profileData'][Constants::$predefinedFields[$user['role']]['last_name']])
        ){
            $userFullName = [];
            $userFullName[] = !empty($user['profileData'][Constants::$predefinedFields[$user['role']]['first_name']]) ? $user['profileData'][Constants::$predefinedFields[$user['role']]['first_name']] : '';
            $userFullName[] = !empty($user['profileData'][Constants::$predefinedFields[$user['role']]['last_name']]) ? $user['profileData'][Constants::$predefinedFields[$user['role']]['last_name']] : '';

            return implode(' ', $userFullName);
        } else {
            return $user['username'];
        }
    }

    public static function getAgencyFullName($user){
        if(isset($user['profileData'][Constants::$predefinedFields[$user['role']]['agency_full_name']])) {
            return $user['profileData'][Constants::$predefinedFields[$user['role']]['agency_full_name']];
        } else {
            return $user['profileData'][Constants::$predefinedFields[$user['role']]['first_name']] . ' ' . $user['profileData'][Constants::$predefinedFields[$user['role']]['last_name']];
        }
    }

    public static function getUserOrganizationName($user){
        if(isset($user['profileData'][Constants::$predefinedFields[$user['role']]['organization_name']])
        ){
            return $user['profileData'][Constants::$predefinedFields[$user['role']]['organization_name']];
        } else {
            return $user['profileData'][Constants::$predefinedFields[$user['role']]['first_name']] . ' ' . $user['profileData'][Constants::$predefinedFields[$user['role']]['last_name']];
        }
    }

    public static function replaceTokens($string, $user_from, $user_to, $domain, $job = null){

        $from = array();
        $to = array();

        $profile = array();
        $profile['username'] = $user_from['username'];
        $profile['email'] = $user_from['email'];
        $profile['full_name'] = self::getUserFullName($user_from);
        $profile = array_merge($profile, $user_from['profileData']);

        foreach($profile as $key => $value){
            if(!is_array($value)){
                $from[] = "%from_$key%";
                $to[] = $value;
            } else {
                $from[] = "%from_$key%";
                $imploded_value = implode(", ", $value);
                $new_value = str_replace("(!)", "", $imploded_value);
                $to[] = $new_value;
            }
        }

        $profile = array();
        $profile['username'] = $user_to['username'];
        $profile['email'] = $user_to['email'];
        $profile['full_name'] = self::getUserFullName($user_to);
        $profile = array_merge($profile, $user_to['profileData']);

        foreach($profile as $key => $value){
            if(!is_array($value)){
                $from[] = "%to_$key%";
                $to[] = $value;
            } else {
                $from[] = "%to_$key%";
                $imploded_value = implode(", ", $value);
                $new_value = str_replace("(!)", "", $imploded_value);
                $to[] = $new_value;
            }
        }

        $from[] = "%domain%";
        $to[] = $domain;

        if($job){

            $profile = array();
            $profile['name'] = $job['name'];
            $profile = array_merge($profile, $job['profileData']);

            foreach($profile as $key => $value){
                if(!is_array($value)){
                    $from[] = "%job_$key%";
                    $to[] = $value;
                } else {
                    $from[] = "%job_$key%";
                    $imploded_value = implode(", ", $value);
                    $new_value = str_replace("(!)", "", $imploded_value);
                    $to[] = $new_value;
                }
            }
        }

        return str_replace($from, $to, $string);
    }

} 