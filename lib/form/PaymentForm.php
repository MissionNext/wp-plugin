<?php


namespace MissionNext\lib\form;

use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\UserLib;
use MissionNext\lib\utils\PaymentHelper;

class PaymentForm extends Form {

    private $params;
    private $user;

    /**
     * @param \MissionNext\Api $params
     * card(bool) Whether accept the card payment or not
     * echeck(bool) Whether accept the E-Check payment or not
     * recurring(bool) Whether accept the recurring payment or not
     * period(year|month|any) Payment period
     */
    public function __construct($params){

        $this->api = Context::getInstance()->getApiManager()->getApi();
        $this->name = 'payment';
        $this->user = Context::getInstance()->getUser()->getUser();
        $this->user_id = $this->user['id'];

//        $this->setName('payment');

        $params = array_merge(array(
            'card' => true,
            'echeck' => true,
            'period' => 'any'
        ), $params);

        $this->params = $params;

        if(!$params['card'] && !$params['echeck']){
            return false;
        }

        $base = array(
                'symbol_key'        => 'main_fields',
                'name'              => '',
                'depends_on'        => null,
                'depends_on_option' => null,
                'order'             => 0,
                'fields'            => array()
        );

        if($params['period'] == 'any'){
            $base['fields'][] = array(
                'type' => 'select',
                'symbol_key' => 'period',
                'name' => __('Period', Constants::TEXT_DOMAIN),
                'order' => 0,
                'default_value' => 'year',
                'choices' => array(
                    array(
                        'value' => __('Year', Constants::TEXT_DOMAIN),
                        'default_value' => 'year'
                    ),
                    array(
                        'value' => __('Month', Constants::TEXT_DOMAIN),
                        'default_value' => 'month'
                    )
                )
            );
        }

        if($params['card'] && $params['echeck']){
            $base['fields'][] = array(
                'type' => 'select',
                'symbol_key' => 'type',
                'name' => __('Payment Type', Constants::TEXT_DOMAIN),
                'order' => 1,
                'default_value' => 'cc',
                'choices' => array(
                    array(
                        'value' => __('E-Check', Constants::TEXT_DOMAIN),
                        'default_value' => 'echeck'
                    ),
                    array(
                        'value' => __('Credit Card', Constants::TEXT_DOMAIN),
                        'default_value' => 'cc'
                    )
                )
            );
        }

        $card = array(
            'symbol_key' => 'card_fields',
            'name' => __('Credit Card', Constants::TEXT_DOMAIN),
            'depends_on' => null,
            'depends_on_option' => null,
            'order' => 1,
            'fields' => array(
                array(
                    'type' => 'input',
                    'symbol_key' => 'card_num',
                    'name' => __('Card Number', Constants::TEXT_DOMAIN),
                    'order' => 0,
                    'default_value' => '',
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'exp_date',
                    'name' => __('Card Expiration Date (MM/YYYY)', Constants::TEXT_DOMAIN),
                    'order' => 1,
                    'default_value' => '',
                    'constraints' => 'required'
                ),
            )
        );

        $check = array(
            'symbol_key' => 'echeck_fields',
            'name' => __('E-check', Constants::TEXT_DOMAIN),
            'depends_on' => null,
            'depends_on_option' => null,
            'order' => 2,
            'fields' => array(
                array(
                    'type' => 'input',
                    'symbol_key' => 'aba_number',
                    'name' => __('Routing Number', Constants::TEXT_DOMAIN),
                    'order' => 1,
                    'default_value' => '',
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'acct_number',
                    'name' => __('Bank Account Number', Constants::TEXT_DOMAIN),
                    'order' => 2,
                    'default_value' => '',
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'select',
                    'symbol_key' => 'acct_type',
                    'name' => __('Bank Account Type', Constants::TEXT_DOMAIN),
                    'order' => 1,
                    'default_value' => 'CHECKING',
                    'choices' => array(
                        array(
                            'value' => __('Checking', Constants::TEXT_DOMAIN),
                            'default_value' => 'CHECKING'
                        ),
                        array(
                            'value' => __('Business Checking', Constants::TEXT_DOMAIN),
                            'default_value' => 'BUSINESSCHECKING'
                        ),
                        array(
                            'value' => __('Savings', Constants::TEXT_DOMAIN),
                            'default_value' => 'SAVINGS'
                        )
                    ),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'bank_name',
                    'name' => __('Bank Name', Constants::TEXT_DOMAIN),
                    'order' => 4,
                    'default_value' => '',
                    'constraints' => 'required'
                )
            )
        );

        $user_info = array(
            'symbol_key' => 'user_fields',
            'name' => __('Billing Info', Constants::TEXT_DOMAIN),
            'depends_on' => null,
            'depends_on_option' => null,
            'order' => 3,
            'fields' => array(
                array(
                    'type' => 'input',
                    'symbol_key' => 'ministry_name',
                    'name' => __('Ministry Name', Constants::TEXT_DOMAIN),
                    'order' => 1,
                    'default_value' => ''
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'first_name',
                    'name' => __('First Name', Constants::TEXT_DOMAIN),
                    'order' => 2,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['first_name']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'last_name',
                    'name' => __('Last Name', Constants::TEXT_DOMAIN),
                    'order' => 3,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['last_name']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'position_title',
                    'name' => __('Position Title', Constants::TEXT_DOMAIN),
                    'order' => 4,
                    'default_value' => ''
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'email',
                    'name' => __('E-mail', Constants::TEXT_DOMAIN),
                    'order' => 5,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['email']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'address',
                    'name' => __('Address', Constants::TEXT_DOMAIN),
                    'order' => 6,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['address']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'city',
                    'name' => __('City', Constants::TEXT_DOMAIN),
                    'order' => 7,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['city']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'state',
                    'name' => __('State ', Constants::TEXT_DOMAIN),
                    'order' => 8,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['state']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'zip',
                    'name' => __('Zip', Constants::TEXT_DOMAIN),
                    'order' => 9,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['zip']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'country',
                    'name' => __('Country', Constants::TEXT_DOMAIN),
                    'order' => 10,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['country']),
                    'constraints' => 'required'
                ),
                array(
                    'type' => 'input',
                    'symbol_key' => 'phone',
                    'name' => __('Phone', Constants::TEXT_DOMAIN),
                    'order' => 11,
                    'default_value' => UserLib::getProfileField($this->user, Constants::$predefinedFields[$this->user['role']]['phone']),
                    'constraints' => 'required'
                ),
                /*array(
                    'type' => 'input',
                    'symbol_key' => 'web_address',
                    'name' => __('Web Address', Constants::TEXT_DOMAIN),
                    'order' => 12,
                    'default_value' => ''
                ),*/
                array(
                    'type' => 'text',
                    'symbol_key' => 'note',
                    'name' => __('Optional note: (For exammple, what organization should be credited with the payment?)', Constants::TEXT_DOMAIN),
                    'order' => 13,
                    'default_value' => ''
                )
            )
        );

        $groups = array();

        if($base['fields']){
            $groups[] = $base;
        }

        if($params['card']){
            $groups[] = $card;
        }

        if($params['echeck']){
            $groups[] = $check;
        }

        $groups[] = $user_info;

        $this->setGroups($groups);

    }

    public function bind($data, $files = null){

        if( $this->params['period']  == 'any' ){
            if(!$data['main_fields']['period'] || !in_array($data['main_fields']['period'], array('year', 'month'))){
                $this->addError('period', __("Wrong period", Constants::TEXT_DOMAIN));
            }
        }

        if($this->params['card']){
            if(!$data['card_fields']['card_num']){
                $this->addError('card_num', __("Enter card number", Constants::TEXT_DOMAIN));
            } elseif(!is_numeric($data['card_fields']['card_num']) ||
                strlen($data['card_fields']['card_num']) < 13 ||
                strlen($data['card_fields']['card_num']) > 16){
                $this->addError('card_num', __("Wrong card number", Constants::TEXT_DOMAIN));
            }

            if(!$data['card_fields']['exp_date']){
                $this->addError('exp_date', __("Enter card expiration date", Constants::TEXT_DOMAIN));
            } elseif(!preg_match('#^(?:0[1-9]|1[012])/(?:19|20)[0-9]{2}$#', $data['card_fields']['exp_date'], $matches) ||
                empty($matches)
            ){
                $this->addError('exp_date', __("Wrong card expiration date", Constants::TEXT_DOMAIN));
            }
        }

        if($this->params['echeck']){

            if(!$data['echeck_fields']['aba_number']){
                $this->addError('aba_number', __("Enter routing number", Constants::TEXT_DOMAIN));
            } elseif(!preg_match("#^[0-9]{9}$#", $data['echeck_fields']['aba_number'], $matches) || empty($matches) ){
                $this->addError('aba_number', __("Wrong routing number", Constants::TEXT_DOMAIN));
            }

            if(!$data['echeck_fields']['acct_number']){
                $this->addError('acct_number', __("Enter account number", Constants::TEXT_DOMAIN));
            } elseif(!is_numeric($data['echeck_fields']['acct_number']) || strlen($data['echeck_fields']['acct_number']) > 20) {
                $this->addError('acct_number', __("Wrong account number", Constants::TEXT_DOMAIN));
            }

            if(!$data['echeck_fields']['acct_type']){
                $this->addError('acct_type', __("Enter account type", Constants::TEXT_DOMAIN));
            } elseif(!in_array($data['echeck_fields']['acct_type'], array('CHECKING', 'BUSINESSCHECKING','SAVINGS'))) {
                $this->addError('acct_type', __("Wrong account type", Constants::TEXT_DOMAIN));
            }

            if(!$data['echeck_fields']['bank_name']){
                $this->addError('bank_name', __("Enter Bank name", Constants::TEXT_DOMAIN));
            } elseif(strlen($data['echeck_fields']['bank_name']) > 50) {
                $this->addError('bank_name', __("Too long Bank name", Constants::TEXT_DOMAIN));
            }

        }

        if(!$data['user_fields']['first_name']){
            $this->addError('first_name', __("Enter First name", Constants::TEXT_DOMAIN));
        } elseif(strlen($data['user_fields']['first_name']) > 50) {
            $this->addError('first_name', __("Too long First name", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['last_name']){
            $this->addError('last_name', __("Enter Last name", Constants::TEXT_DOMAIN));
        } elseif(strlen($data['user_fields']['last_name']) > 50) {
            $this->addError('last_name', __("Too long Last name", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['email']){
            $this->addError('email', __("Enter E-mail", Constants::TEXT_DOMAIN));
        } elseif(!filter_var($data['user_fields']['email'], FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', __("Wrong E-mail", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['address']){
            $this->addError('address', __("Enter Address", Constants::TEXT_DOMAIN));
        } elseif(strlen($data['user_fields']['address']) > 50) {
            $this->addError('address', __("Too long Address", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['city']){
            $this->addError('city', __("Enter City", Constants::TEXT_DOMAIN));
        } elseif(strlen($data['user_fields']['city']) > 50) {
            $this->addError('city', __("Too long City", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['state']){
            $this->addError('state', __("Enter State", Constants::TEXT_DOMAIN));
        } elseif(strlen($data['user_fields']['state']) > 40) {
            $this->addError('state', __("Too long State", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['zip']){
            $this->addError('zip', __("Enter Zip", Constants::TEXT_DOMAIN));
        } elseif(strlen($data['user_fields']['zip']) > 20) {
            $this->addError('zip', __("Too long Zip", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['country']){
            $this->addError('country', __("Enter Country", Constants::TEXT_DOMAIN));
        } elseif(strlen($data['user_fields']['country']) > 60) {
            $this->addError('country', __("Too long Country", Constants::TEXT_DOMAIN));
        }

        if(!$data['user_fields']['phone']){
            $this->addError('phone', __("Enter Phone", Constants::TEXT_DOMAIN));
        } elseif(!preg_match('#^[0-9\ -\.\(\)]{5,25}$#', $data['user_fields']['phone'], $matches) ||
                empty($matches)
        ) {
            $this->addError('phone', __("Not a valid phone", Constants::TEXT_DOMAIN));
        }

        parent::bind($data, $files);

    }

    public function process(PaymentHelper $helper){

        $data = $this->getData();

        $this->updateProfile($data['user_fields']);

        $payment_type = null;

        if($this->params['card'] && $this->params['echeck']){
            $payment_type = $data['main_fields']['type'];
        } elseif($this->params['card']){
            $payment_type = 'cc';
        } elseif($this->params['echeck']){
            $payment_type = 'echeck';
        }

        switch ($payment_type) {
            case 'cc' :
                $payment_data = $data['card_fields'];
                break;
            case 'echeck' :
                $payment_data = $data['echeck_fields'];
                break;
            default :
                $payment_data = array();
        }

        $period = ($this->params['period'] == 'any')?$data['main_fields']['period']:$this->params['period'];

        $is_recurring = $this->params['period'] == 'month';

        if ($this->user->userRole == Constants::ROLE_CANDIDATE) {
            $is_recurring = true;
        }

        $required_data = $data['user_fields'];
        $required_data['cust_id'] = $this->user_id;
        unset(
            $required_data['ministry_name'],
            $required_data['position_title'],
            $required_data['web_address'],
            $required_data['note']
        );

        $additional_data = array(
            'ministry_name' => $data['user_fields']['ministry_name'],
            'position_title' => $data['user_fields']['position_title'],
            'web_address' => $data['user_fields']['web_address'],
            'note' => $data['user_fields']['note']
        );

        $response = $helper->processPaymentData(array(
            'user_id' => $this->user_id,
            'recurring' => $is_recurring,
            'period' => $period,
            'type' => $payment_type,
            'payment_data' => $payment_data,
            'required_data' => $required_data,
            'additional_data' => $additional_data
        ));

        $first_field = isset($data['main_fields'])?key($data['main_fields']):($payment_type == 'cc'? key($data['card_fields']):key($data['echeck_fields']));

        if($this->api->getLastStatus() == 1){

            $this->updateProfile($data['user_fields']);

            return $response;

        } elseif ($this->api->getLastStatus() == 2){

            $this->addError($first_field, $response);

            return false;

        } else {
            $this->addError($first_field, __("Something went wrong."));

            return false;
        }
    }

    private function updateProfile($data){

        $to_update = array();

        unset($data['note']);

        $model = $this->api->getModelFields($this->user['role']);

        foreach($model as $field){
            $symbol_key = $field['symbol_key'];

            if(isset($data[$symbol_key])){
                $to_update[$symbol_key] = array(
                    'type' => $field['type'],
                    'value' => $data[$symbol_key],
                    'dictionary_id' => ''
                );
            }
        }

        if($to_update){
            $this->api->updateUserProfile($this->user['id'], $to_update, $this->changedFields);
        }

    }

} 