<?php


namespace MissionNext\lib;


class ProfileLib {

    public static function getProfileField($item, $symbol_key){
        return isset($item['profileData'][Constants::$predefinedFields[$item['role']][$symbol_key]])?
            $item['profileData'][Constants::$predefinedFields[$item['role']][$symbol_key]]:'';
    }

    public static function getAge($item){

        $birth_date = self::getProfileField($item, 'birth_date');

        if($birth_date && is_numeric($birth_date)){
            $now = date('Y');
            $diff = $now - $birth_date;
            return $diff;
        }

        return '';
    }

    public static function getLocation($item){
        $country = self::getProfileField($item, 'country');

        if($country) {
            if (array_key_exists('United States', $country)) {
                $state = self::getProfileField($item, 'state');
                if ($state)
                    return is_array($state) ? reset($state) : $state;
            }
            return is_array($country) ? reset($country) : $country;
        }
        return '';
    }

    public static function prepareDataToShow($profile, $groups){
        $result = array();

        uasort($groups, array('MissionNext\lib\ProfileLib', 'sortGroups'));

//        echo 123;
//        echo "<pre>";
//        print_r($profile);
//        echo "</pre>";

        foreach($groups as $group){

            uasort($group['fields'], array('MissionNext\lib\ProfileLib', 'sortFields'));

            $fields = $group['fields'];
            $group['fields'] = array();

            foreach($fields as $field){

                $value = isset($profile[$field['symbol_key']])?$profile[$field['symbol_key']]:null;

                if($value){
                    if(is_array($value)){
                        foreach($value as $key => $item){
                            if(strpos($item, Constants::NO_PREFERENCE_SYMBOL) === 0){
                                $value[$key] = substr($item, 3);
                            }
                        }
                    } else {
                        if(strpos($value, Constants::NO_PREFERENCE_SYMBOL) === 0){
                            $value = substr($value, 3);
                        }
                    }
                }

                if(is_array($value)){
                    $val = array();
                    foreach($value as $key => $choice){
                        $val[] = $choice?$choice:$key;
                    }
                } else {
                    $val = $value;
                }

                $val = str_replace('&quot;', '"', $val);

                $specChars = strpos($field['default_name'], Constants::JOB_TITLE_LIMITER);
                if ($specChars !== false) {
                    $field['default_name'] = substr($field['default_name'], 0, $specChars);
                }
                
                $group['fields'][$field['symbol_key']] = array(
                    'value' => $val,
                    'symbol_key' => $field['symbol_key'],
                    'label' => $field['name']?$field['name']:$field['default_name']
                );
            }

            $result[$group['symbol_key']] = $group;

        }



        return $result;
    }

    private static function sortGroups($a, $b){
        return $a['order'] < $b['order'] ? -1 : 1;
    }

    private static function sortFields($a, $b){
        return $a['order'] < $b['order'] ? -1 : 1;
    }
} 