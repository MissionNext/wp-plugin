<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 24.09.15
 * Time: 16:44
 */

namespace MissionNext\frontend\controllers;

use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class customAjaxController extends Controller
{
    private $api;

    public function __construct(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
    }

    /**
     * Функция выбора страны.
     */
    public function selectCountry($params)
    {
        if(is_post())
        {
            $name = element('name', $_POST);
            if(!empty($name))
            {
                $file = MN_ROOT_DIR . '/resources/json/countries.json';
                if(is_file($file))
                {
                    $countries = json_decode(file_get_contents($file), true);
                    if(!empty($countries))
                    {
                        $lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

                        $states  = array();
                        $choices = array();

                        $languages = $this->api->getFieldsLanguages($params[0]);
                        if(!empty($languages))
                        {
                            foreach($languages as $language)
                            {
                                foreach($language['fields'] as $field)
                                {
                                    if($language['lang_id'] == 0)
                                    {
                                        if($field['symbol_key'] == 'state')
                                        {
                                            $states = array_combine($field['dictionary_id'], $field['choices']);
                                        }
                                    }

                                    if($language['lang_id'] == $lang_id)
                                    {
                                        if($field['symbol_key'] == 'state')
                                        {
                                            $choices = array_combine($field['dictionary_id'], $field['choices']);
                                        }
                                    }
                                }
                            }
                        }
                        unset($languages);

                        foreach($countries as $country)
                        {
                            if($country['name'] == $name)
                            {
                                if(!empty($country['states']))
                                {
                                    foreach($country['states'] as $s => $state)
                                    {
                                        if(in_array($state['name'], $states))
                                        {
                                            $value = element(array_search($state['name'], $states), $choices);

                                            if(!empty($value))
                                            {
                                                $country['states'][$s]['name'] = $value;
                                            }

                                            $country['states'][$s]['value'] = $state['name'];
                                        }
                                    }

                                    array_unshift($country['states'], [ 'name' => '', 'abbr' => '', 'value' => '' ]);

                                    echo json_encode($country['states'], TRUE);
                                }
                            }
                        }
                    }
                    unset($countries);
                }
                unset($file);
            }
            unset($name);
        }

        return false;
    }

    public function deleteFile()
    {
        $result = '';
        if (is_post()) {
            $fieldname = element('fieldname', $_POST);
            $user_id = element('userid', $_POST);

            $result = $this->api->deleteProfileFile($fieldname, $user_id);
        }

        echo json_encode($result);

        return false;
    }

    public function deleteJobFile()
    {
        $result = '';
        if (is_post()) {
            $fieldname = element('fieldname', $_POST);
            $job_id = element('jobid', $_POST);

            $result = $this->api->deleteJobProfileFile($fieldname, $job_id);
        }

        echo json_encode($result);

        return false;
    }
}