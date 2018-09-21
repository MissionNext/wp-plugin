<?php

namespace MissionNext;

class Api {

    public $lastResponse;

    /**
     * @var ClientInterface
     */
    private $client;

    protected $apiBasePath;
    protected $prefix = 'api';
    protected $version = 'v1';

    private $publicKey;
    private $privateKey;
    private $user_id = 0;
    private $lang = 0;

    private $lastError;
    private $lastStatus;

    public function __construct( ClientInterface $client, $publicKey, $privateKey, $basePath){
        $this->client = $client;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->apiBasePath = $basePath;
    }

    public function getUserConfigs()
    {
        return $this->get("uconfigs/current");
    }

    public function getUserConfigsById($user_id)
    {
        return $this->get("uconfigs/$user_id");
    }

    public function getUserConfigsElement($key, $user_id)
    {
        return $this->get("uconfigs/key/$key/$user_id");
    }

    public function saveUserConfig($config)
    {
        return $this->post("uconfigs", $config);
    }

    public function setLang($lang){
        $this->lang = $lang;
    }

    public function getAdministratorEmails(){
        return $this->get("administrator/emails");
    }

    public function getSubscriptionsForUser($user_id){
        return $this->get("subscription/for/$user_id");
    }

    public function saveSubscription($data){
        return $this->post("subscription/manager", $data);
    }

    public function addSubscription($data){
        return $this->post("subscription/manager/add", $data);
    }

    public function getCouponByCode($code){
        return $this->post("coupon/code", compact('code'));
    }

    public function getSubscriptionConfigs(){
        return $this->get("subscription/configs");
    }

    public function getGlobalConfigs(){
        return $this->get('gconfigs');
    }

    public function saveConfig($config){
        return $this->post("configs", $config);
    }

    public function getConfigs(){
        return $this->get("configs");
    }

    public function getConfig($key){
        return $this->get("configs/key/$key");
    }

    public function setUserId($id){
        $this->user_id = $id;
    }

    public function testConnection(){
        return $this->get("test");
    }

    public function getCustomTranslations(){
        return $this->get("custom/trans");
    }

    public function saveCustomTranslations($data){
        return $this->post("custom/trans", array('custom' => $data));
    }

    public function saveFieldsLanguages($role, $languages){
        return $this->post("trans/$role/field", compact('languages'));
    }

    public function getFieldsLanguages($role){
        return $this->get("trans/$role/field");
    }

    public function saveSiteLanguage($languages){
        return $this->post("language/application", compact('languages'));
    }

    public function getSiteLanguages(){
        return $this->get("language/application");
    }

    public function getAllLanguages(){
        return $this->get("language");
    }

    public function getInquiredCandidatesForAgency($agency_id){
        return $this->get("inquire/candidates/for/agency/$agency_id");
    }

    public function getInquiredCandidatesForOrganization($org_id){
        return $this->get("inquire/candidates/for/organization/$org_id");
    }

    public function getInquiredJobs($candidate_id){
        return $this->get("inquire/jobs/for/$candidate_id");
    }

    public function cancelInquire($candidate_id, $job_id){
        return $this->post("inquire/$candidate_id/for/$job_id/cancel");
    }

    public function cancelInquireByAgency($agency_id, $inquire_id){
        return $this->post("inquire/cancel/$inquire_id/by/agency/$agency_id");
    }

    public function cancelInquireByOrganization($org_id, $inquire_id){
        return $this->post("inquire/cancel/$inquire_id/by/organization/$org_id");
    }

    public function inquireJob($candidate_id, $job_id){
        return $this->post("inquire/$candidate_id/for/$job_id");
    }

    public function addFavorite($user_id, $target_type, $target_id){
        return $this->post("favorite", compact('user_id', 'target_type', 'target_id'));
    }

    public function removeFavorite($favorite_id){
        return $this->delete("favorite/$favorite_id");
    }

    public function getFavorites($user_id, $role){
        return $this->get("favorite/$user_id/$role");
    }

    public function getFoldersTranslation($role){
        return $this->get("folder/trans/$role");
    }

    public function saveFolderTranslation($data){
        return $this->post("folder/trans", array('folder' => $data));
    }

    public function updateFolder($id, $title){
        return $this->put("folder/$id", compact('title'));
    }

    public function deleteFolder($id){
        return $this->delete("folder/$id");
    }

    public function addFolder($role, $title, $user_id = null){
        return $this->post("folder", compact('role', 'title', 'user_id'));
    }

    public function getUserFolders($role, $user_id){
        return $this->get("folder/by/$role/$user_id");
    }

    public function getFolders($role){
        return $this->get("folder/by/$role");
    }

    public function getAffiliateJobs($agency_id){
        return $this->get("affiliate/$agency_id/jobs");
    }

    /**
     * @param $id int
     * @param $type String (requester, approver, any)
     * @return Array|false
     */
    public function getAffiliates($id, $type){
        return $this->get("affiliate/$id/as/$type");
    }

    public function requestAffiliate($requester_id, $target_id){
        return $this->post("affiliate/$requester_id/to/$target_id");
    }

    public function approveAffiliate($requester_id, $target_id){
        return $this->post("affiliate/$requester_id/to/$target_id/approve");
    }

    public function cancelAffiliate($requester_id, $target_id){
        return $this->post("affiliate/$requester_id/to/$target_id/cancel");
    }

    public function getMatchedJobsForCandidate($candidate_id, $options = array()){
        return $this->get("match/candidate/jobs/$candidate_id", $options);
    }

    public function getMatchedOrganizationsForCandidate($candidate_id, $options = array()){
        return $this->get("match/candidate/organizations/$candidate_id", $options);
    }

    public function getMatchedCandidatesForJob($job_id, $options = array()){
        return $this->get("match/job/candidates/$job_id", $options);
    }

    public function getMatchedCandidatesForOrganization($organization_id, $options = array()){
        return $this->get("match/organization/candidates/$organization_id", $options);
    }

    public function getOneMatchResult($user_for_id, $user_id, $options = array()){
        return $this->get("match/getOneResult/$user_for_id/$user_id", $options);
    }

    public function addSavedSearch($role_from, $role_to, $user_id, $data){
        return $this->put("search/$role_to/for/$role_from/$user_id", $data);
    }

    public function deleteSavedSearch($id, $user_id){
        return $this->delete("search/$id/$user_id");
    }

    public function getSavedSearches($role_from, $role_to, $user_id){
        return $this->get("search/$role_to/for/$role_from/$user_id");
    }

    public function search($role_for, $role_from, $user_id, $params, $page){
        return $this->post("search/$role_for/for/$role_from/$user_id", compact('params', 'page'));
    }

    public function getMetaInfoForAgency($user_id, $role){
        return $this->get("meta/for/$user_id/$role");
    }

    public function changeNote( $user_id, $user_type, $for_user_id, $notes ){
        return $this->post("meta/notes", compact('user_id', 'user_type', 'for_user_id', 'notes'));
    }

    public function changeFolder( $user_id, $user_type, $for_user_id, $folder ){
        return $this->post("meta/folder", compact('user_id', 'user_type', 'for_user_id', 'folder'));
    }

    public function getOrganizationPositions($organization_id, $user_id){
        return $this->get("organization/jobs/$organization_id/for/$user_id");
    }

    public function getOrganizationsNames($organizations) {
        return $this->get("organization/select/names", compact('organizations'));
    }

    /**
     * Job search
     *
     * @param $params
     * @return bool
     */
    public function findJobs($params){
        return $this->post("job/find", $params);
    }

    public function findJobsByOrgId($org_id){
        return $this->post("job/find/$org_id");
    }

    /**
     * Get job by id
     *
     * @param $id
     * @return bool
     */
    public function getJob($id){
        return $this->get("job/$id");
    }

    public function updateJob($id, $symbolKey, $name, $org_id){
        return $this->put("job/$id", array('symbol_key' => $symbolKey, 'name' => $name, 'organization_id' => $org_id));
    }

    public function getJobProfile($id){
        return $this->get("profile/job/$id");
    }

    public function updateJobProfile($id, $profile, $changedData = null){
        return $this->put("profile/job/$id", compact('profile', 'changedData'));
    }

    public function createJob($symbolKey, $name, $org_id, $profile){
        return $this->post('job', array_merge(array('symbol_key' => $symbolKey, 'name' => $name, 'organization_id' => $org_id), $profile));
    }

    public function deleteJob($id, $organization_id, $old_login){
        return $this->delete("job/$id/$organization_id", array( 'old_login' => $old_login ));
    }

    public function getMatchingConfig($role){
        return $this->get($role . '/matching/config');
    }

    public function saveMatchingConfig($role, $data){
        return $this->put($role . '/matching/config', array( 'configs' => $data));
    }

    public function getForm($role, $name){
        return $this->get($role.'/'.$name.'/form');
    }

    public function saveForm($role, $name, $data){
        return $this->put($role.'/'.$name.'/form', array( 'groups' => $data));
    }

    public function getFormTranslations($role, $name){
        return $this->get('form/group/trans/'. $role.'/'.$name);
    }

    public function saveFormGroupTranslations($data){
        return $this->post('form/group/trans', array( 'groups' => $data));
    }

    public function saveModel($role, $data){
        return $this->post($role.'/field/model', array( 'fields' => $data ));
    }

    public function getModelFields($role){
        return $this->get($role.'/field/model');
    }

    public function getRoleFields($role){
        return $this->get($role.'/field');
    }

    public function addRoleField($role, $data){
        return $this->post($role.'/field', array('fields' => array( $data )));
    }

    public function saveRoleField($role, $data){
        return $this->put($role.'/field', array('fields' => array( $data )));
    }

    public function deleteRoleField($role, $id){
        return $this->delete($role.'/field', array( 'ids' => array($id) ));
    }

    public function saveRoleFieldChoices($role, $field_id, $choices)
    {
        return $this->post($role.'/field/choices/'.$field_id, array('choices' => $choices));
    }

    public function checkCompletedProfile($user_id) {
        return $this->get('check/profile/'.$user_id);
    }

    public function deleteProfileCompletnessStatus($role) {
        return $this->delete('completness/profile/'.$role);
    }

    public function updateUserProfile($user_id, $profile, $changedData = null, $saveLater = null){
        return $this->put('profile/'.$user_id, compact('profile', 'changedData', 'saveLater'));
    }

    public function getUserProfile($user_id){
        return $this->get('profile/'.$user_id);
    }

    public function deleteProfileFile($field_name, $user_id){
        return $this->delete('profile/'.$user_id, array( 'field_name' => $field_name));
    }

    public function deleteJobProfileFile($field_name, $job_id){
        return $this->delete('profile/job/'.$job_id, array( 'field_name' => $field_name));
    }

    public function updateUser($id, $params){
        return $this->put("user/$id", $params);
    }

    public function getUser($id){
        return $this->get("user/$id");
    }

    public function getUserBy($params){
        return $this->post('user/find', $params);
    }

    public function deactivateUserApp($user_id){
        return $this->post('user/deactivate/'.$user_id, []);
    }

    public function checkAuth($username, $password){
        return $this->post('user/check', compact('username', 'password'));
    }

    public function setNewPassword($username, $password){
        return $this->post('user/password/reset', compact('username', 'password'));
    }

    public function register($username, $email, $password, $role, $profile = array()){
        return $this->post('user', compact('username', 'email', 'password', 'role', 'profile'));
    }

    public function getLastError(){
        return $this->lastError;
    }

    public function getLastStatus(){
        return $this->lastStatus;
    }

    public function buildUrl($methodName, $data = array()){

        $url = "/$this->prefix/$this->version/$methodName";

        if($data){
            $url .= '?' . http_build_query($data);
        }

        return $url;
    }

    public function get($method, $data = array()){

        $url = $this->buildUrl($method, $data);

        $this->client->setMethod('GET');

        return $this->performRequest($url);
    }

    public function post($method, $data = array()){

        $url = $this->buildUrl($method);

        $this->client->setMethod('POST');

        $this->client->setData($data);

        return $this->performRequest($url);
    }

    public function put($method, $data = array()){

        $url = $this->buildUrl($method);

        $this->client->setMethod('PUT');

        $this->client->setData($data);

        return $this->performRequest($url);
    }

    public function delete($method, $data = array()){

        $url = $this->buildUrl($method, $data);

        $this->client->setMethod('DELETE');

        return $this->performRequest($url);
    }

    protected function performRequest($url){

        $response = $this->getResponse($url);

        $this->lastResponse = $response;

        return $this->parseResponse($response);
    }

    protected function parseResponse($json){
        $response = json_decode($json, true);
        $response = stripslashes_deep($response);
        $this->lastStatus = $response['status'];

        if($response['status']){
            return $response['data'];
        } else {
            $this->lastError = @$response['data']['error'];
            return false;
        }
    }

    protected function addAuthInfo($url){

        $this->client->setHeader('X-Lang', intval($this->lang));
        $this->client->setHeader('X-Auth', $this->publicKey);
        $this->client->setHeader('X-Auth-Hash', $this->buildHashKey($url));
        $this->client->setHeader('X-User', $this->user_id);
    }

    protected function buildHashKey($url){
        return strtr(
            base64_encode(
                hash_hmac('sha1', $url,
                    base64_decode(
                        strtr($this->privateKey, '-_', '+/')), true)), '+/', '-_'
        );
    }

    protected function getResponse($url){

        $url = $this->addTimestamp($url);
        $this->client->setUrl($this->apiBasePath.$url);
        $this->addAuthInfo($url);

        $resp = $this->client->exec();

        return $resp;
    }

    protected function addTimestamp($url){
        return parse_url( $url, PHP_URL_QUERY ) ? $url . '&timestamp='.time(): $url . '?timestamp='.time();
    }
}
