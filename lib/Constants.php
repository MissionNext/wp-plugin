<?php


namespace MissionNext\lib;


class Constants {

    const TEXT_DOMAIN = "mission-next";
    const PUBLIC_KEY_TOKEN = "mn-public-key";
    const PRIVATE_KEY_TOKEN = "mn-private-key";
    const AVATAR_TOKEN = "mn-private-key";
    const META_KEY = "mn-id";
    const META_ROLE = "mn-role";
    const NO_PREFERENCE_SYMBOL = "(!)";
    const JOB_TITLE_LIMITER = "!#";
    const EMAIL_TAG = "email";
    const EMAILS_DIR = 'data/emails';

    const ROLE_CANDIDATE = 'candidate';
    const ROLE_CANDIDATE_PLURAL = 'candidate_plural';
    const ROLE_AGENCY = 'agency';
    const ROLE_AGENCY_PLURAL = 'agency_plural';
    const ROLE_ORGANIZATION = 'organization';
    const ROLE_ORGANIZATION_PLURAL = 'organization_plural';
    const ROLE_JOB = 'job';
    const ROLE_JOB_PLURAL = 'job_plural';

    const GLOBAL_CONFIG_DISCOUNT = 'subscriptionDiscount';
    const GLOBAL_CONFIG_FEE = 'conFee';

    const CONFIG_DEFAULT_LANG = 'default_language';
    const CONFIG_AGENCY_TRIGGER = 'agency_trigger';
    const CONFIG_BLOCK_WEBSITE = 'block_website';
    const CONFIG_CANDIDATE_DEFAULT_FOLDER = 'candidate_default_folder';
    const CONFIG_AGENCY_DEFAULT_FOLDER = 'agency_default_folder';
    const CONFIG_ORGANIZATION_DEFAULT_FOLDER = 'organization_default_folder';
    const CONFIG_JOB_DEFAULT_FOLDER = 'job_default_folder';

    const PAGE_BLOCKED = "mn-blocked-page";
    const PAGE_PENDING_APPROVAL = "mn-pending-approval-page";
    const PAGE_NO_AGENCY_ROLE = "mn-no-agency-role";

    const USER_STATUS_OK = 0;
    const USER_STATUS_PENDING = 1;

    const PARTNERSHIP_LIMITED = 'limited';
    const PARTNERSHIP_BASIC = 'basic';
    const PARTNERSHIP_PLUS = 'plus';

    const SUBSCRIPTION_STATUS_ACTIVE = 'active';
    const SUBSCRIPTION_STATUS_GRACE = 'grace';
    const SUBSCRIPTION_STATUS_EXPIRED = 'expired';
    const SUBSCRIPTION_STATUS_CLOSED = 'closed';

    const CONSTRAINT_LESS_THAN = 'ymd_less_than';
    const CONSTRAINT_MORE_THAN = 'ymd_more_than';

    public static $predefinedFields = array(
        self::ROLE_CANDIDATE => array(
            'location'          => 'location',
            'email'             => 'email',
            'first_name'        => 'first_name',
            'last_name'         => 'last_name',
            'birth_date'        => 'birth_year',
            'gender'            => 'gender',
            'country'           => 'country',
            'state'             => 'state',
            'state/province'    => 'state/province',
            'city'              => 'city',
            'zip'               => 'zip',
            'address'           => 'address',
            'phone'             => 'phone',
            'marital_status'    => 'marital_status',
        ),
        self::ROLE_AGENCY => array(
            'location'          => 'location',
            'first_name'        => 'first_name',
            'last_name'         => 'last_name',
            'email'             => 'email',
            'country'           => 'country',
            'state'             => 'state',
            'city'              => 'city',
            'zip'               => 'zip',
            'address'           => 'address',
            'phone'             => 'phone',
            'agency_full_name'  => 'agency_full_name',

        ),
        self::ROLE_ORGANIZATION => array(
            'location'          => 'location',
            'first_name'        => 'first_name',
            'last_name'         => 'last_name',
            'email'             => 'email',
            'country'           => 'country',
            'state'             => 'state',
            'city'              => 'city',
            'zip'               => 'zip',
            'address'           => 'address',
            'phone'             => 'phone',
            'organization_name' => 'organization_name',
        ),
        self::ROLE_JOB => array(
            'world_region'      => 'world_region',
            'job_category'      => 'job_category',
            'time_commitment'   => 'time_commitment',
            'expiration_date'   => 'expiration_date',
            'job_title'         => 'job_title',
            'second_title'      => 'second_title',
            'country'           => 'country',
            'state'             => 'state',
        )
    );

    public static $matchingWeights = array(
        1 => 'Not important',
        2 => 'Low important',
        3 => 'Important',
        4 => 'Very Important',
        5 => 'Must match',
    );

    public static $matchingTypes = array(
        1 => '=',
        2 => '>=',
        3 => '>',
        4 => '<=',
        5 => '<',
        6 => 'LIKE',
    );

    public static $folders = array(
        'candidate' => array(
            'New Listing',
            'Saved-Review Later',
            'High Interest',
            'Some Interest',
            'Short-Term',
            'Contacted',
            'No Interest'
        ),
        'agency' => array(
            'New Listing',
            'Saved-Review Later',
            'High Interest',
            'Some Interest',
            'Short-Term',
            'Contacted',
            'No Interest'
        ),
        'organization' => array(
            'New Listing',
            'Saved-Review Later',
            'High Interest',
            'Some Interest',
            'Short-Term',
            'Contacted',
            'No Interest'
        ),
        'job' => array(
            'New Listing',
            'Saved-Review Later',
            'High Interest',
            'Some Interest',
            'Short-Term',
            'Contacted',
            'No Interest'
        )
    );

    public static $custom_translates = array(
        self::ROLE_CANDIDATE => 'Candidate',
        self::ROLE_CANDIDATE_PLURAL => 'Candidates',
        self::ROLE_ORGANIZATION => 'Receiving Organization',
        self::ROLE_ORGANIZATION_PLURAL => 'Receiving Organizations',
        self::ROLE_JOB => 'Job',
        self::ROLE_JOB_PLURAL => 'Jobs'
    );

    public static $agency_custom_translates = array(
        self::ROLE_AGENCY => 'Service Organization',
        self::ROLE_AGENCY_PLURAL => 'Service Organizations'
    );

} 