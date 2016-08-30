<?php

use MissionNext\Api;
use MissionNext\CurlClient;

class ApiTest extends PHPUnit_Framework_TestCase
{
    protected $publicKey = '123456';
    protected $privateKey = '654321';
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $client;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $api;

    public function setUp()
    {
        $this->client = $this->getMock('MissionNext\CurlClient', array());
        $this->api = $this->getMock('MissionNext\Api', array('get', 'post', 'put', 'delete'), array($this->client, $this->publicKey, $this->privateKey));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUserConfigs($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('uconfigs/current')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUserConfigs());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUserConfigsById($data)
    {
        $user_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("uconfigs/$user_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUserConfigsById($user_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUserConfigsElement($data)
    {
        $key = 'key';
        $user_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("uconfigs/key/$key/$user_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUserConfigsElement($key, $user_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveUserConfig($data)
    {
        $config = array();

        $this->api->expects($this->once())
            ->method('post')
            ->with('uconfigs', $config)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveUserConfig($config));
    }

    public function testSetLang()
    {

    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetAdministratorEmails($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('administrator/emails')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getAdministratorEmails());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetSubscriptionsForUser($data)
    {
        $user_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("subscription/for/$user_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getSubscriptionsForUser($user_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveSubscription($data)
    {
        $this->api->expects($this->once())
            ->method('post')
            ->with('subscription/manager', array())
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveSubscription(array()));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetCouponByCode($data)
    {
        $code = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with('coupon/code', compact('code'))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getCouponByCode($code));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetSubscriptionConfigs($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('subscription/configs')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getSubscriptionConfigs());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetGlobalConfigs($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('gconfigs')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getGlobalConfigs());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveConfig($data)
    {
        $config = array();

        $this->api->expects($this->once())
            ->method('post')
            ->with('configs', $config)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveConfig($config));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetConfigs($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('configs')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getConfigs());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetConfig($data)
    {
        $key = 'key';

        $this->api->expects($this->once())
            ->method('get')
            ->with("configs/key/$key")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getConfig($key));
    }

    public function testSetUserId()
    {

    }

    /**
     * @dataProvider dataProvider
     */
    public function testTestConnection($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('test')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->testConnection());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetCustomTranslations($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('custom/trans')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getCustomTranslations());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveCustomTranslations($data)
    {
        $custom = 'foo';

        $this->api->expects($this->once())
            ->method('post')
            ->with('custom/trans', array('custom' => $custom))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveCustomTranslations($custom));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveFieldsLanguages($data)
    {
        $role = 'candidate';
        $languages = array();

        $this->api->expects($this->once())
            ->method('post')
            ->with("trans/$role/field", array('languages' => $languages))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveFieldsLanguages($role, $languages));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetFieldsLanguages($data)
    {
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with("trans/$role/field")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getFieldsLanguages($role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveSiteLanguage($data)
    {
        $languages = array();

        $this->api->expects($this->once())
            ->method('post')
            ->with('language/application', compact('languages'))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveSiteLanguage($languages));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetSiteLanguages($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('language/application')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getSiteLanguages());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetAllLanguages($data)
    {
        $this->api->expects($this->once())
            ->method('get')
            ->with('language')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getAllLanguages());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetInquiredCandidatesForAgency($data)
    {
        $agency_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("inquire/candidates/for/agency/$agency_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getInquiredCandidatesForAgency($agency_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetInquiredCandidatesForOrganization($data)
    {
        $org_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("inquire/candidates/for/organization/$org_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getInquiredCandidatesForOrganization($org_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetInquiredJobs($data)
    {
        $candidate_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("inquire/jobs/for/$candidate_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getInquiredJobs($candidate_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCancelInquire($data)
    {
        $candidate_id = 1;
        $job_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with("inquire/$candidate_id/for/$job_id/cancel")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->cancelInquire($candidate_id, $job_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCancelInquireByAgency($data)
    {
        $agency_id = 1;
        $inquire_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with("inquire/cancel/$inquire_id/by/agency/$agency_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->cancelInquireByAgency($agency_id, $inquire_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCancelInquireByOrganization($data)
    {
        $org_id = 1;
        $inquire_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with("inquire/cancel/$inquire_id/by/organization/$org_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->cancelInquireByOrganization($org_id, $inquire_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testInquireJob($data)
    {
        $candidate_id = 1;
        $job_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with("inquire/$candidate_id/for/$job_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->inquireJob($candidate_id, $job_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAddFavorite($data)
    {
        $user_id = 1;
        $target_type = 'foo';
        $target_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with('favorite', compact('user_id', 'target_type', 'target_id'))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->addFavorite($user_id, $target_type, $target_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRemoveFavorite($data)
    {
        $favorite_id = 1;

        $this->api->expects($this->once())
            ->method('delete')
            ->with("favorite/$favorite_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->removeFavorite($favorite_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetFavorites($data)
    {
        $user_id = 1;
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with("favorite/$user_id/$role")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getFavorites($user_id, $role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetFoldersTranslation($data)
    {
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with("folder/trans/$role")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getFoldersTranslation($role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveFolderTranslation($data)
    {
        $folder = 'foo';

        $this->api->expects($this->once())
            ->method('post')
            ->with("folder/trans", array('folder' => $folder))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveFolderTranslation($folder));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateFolder($data)
    {
        $id = 'foo';
        $title = 'foo';

        $this->api->expects($this->once())
            ->method('put')
            ->with("folder/$id", compact('title'))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->updateFolder($id, $title));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDeleteFolder($data)
    {
        $id = 'foo';

        $this->api->expects($this->once())
            ->method('delete')
            ->with("folder/$id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->deleteFolder($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAddFolder($data)
    {
        $role = 'candidate';
        $title = 'foo';

        $this->api->expects($this->once())
            ->method('post')
            ->with('folder', compact('role', 'title'))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->addFolder($role, $title));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetFolders($data)
    {
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with("folder/by/$role")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getFolders($role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetAffiliateJobs($data)
    {
        $agency_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("affiliate/$agency_id/jobs")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getAffiliateJobs($agency_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetAffiliates($data)
    {
        $id = 1;
        $type = 'foo';

        $this->api->expects($this->once())
            ->method('get')
            ->with("affiliate/$id/as/$type")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getAffiliates($id, $type));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRequestAffiliate($data)
    {
        $requester_id = 1;
        $target_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with("affiliate/$requester_id/to/$target_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->requestAffiliate($requester_id, $target_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testApproveAffiliate($data)
    {
        $requester_id = 1;
        $target_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with("affiliate/$requester_id/to/$target_id/approve")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->approveAffiliate($requester_id, $target_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCancelAffiliate($data)
    {
        $requester_id = 1;
        $target_id = 1;

        $this->api->expects($this->once())
            ->method('post')
            ->with("affiliate/$requester_id/to/$target_id/cancel")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->cancelAffiliate($requester_id, $target_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedJobForCandidate($data)
    {
        $candidate_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/candidate/jobs/$candidate_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getMatchedJobsForCandidate($candidate_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedOrganizationsForCandidate($data)
    {
        $candidate_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/candidate/organizations/$candidate_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getMatchedOrganizationsForCandidate($candidate_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedCandidatesForJob($data)
    {
        $job_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/job/candidates/$job_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getMatchedCandidatesForJob($job_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedCandidatesForOrganization($data)
    {
        $organization_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/organization/candidates/$organization_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getMatchedCandidatesForOrganization($organization_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAddSavedSearches($data)
    {
        $role_from = 'candidate';
        $role_to = 'job';
        $user_id = 1;
        $params = array(
            'name' => 'First job',
            'profileData' => array(
                'key' => 'value'
            )
        );

        $this->api->expects($this->once())
            ->method('put')
            ->with("search/$role_to/for/$role_from/$user_id", $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->addSavedSearch($role_from, $role_to, $user_id, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDeleteSavedSearch($data)
    {
        $id = 1;
        $user_id = 1;

        $this->api->expects($this->once())
            ->method('delete')
            ->with("search/$id/$user_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->deleteSavedSearch($id, $user_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetSavedSearches($data)
    {
        $role_from = 'candidate';
        $role_to = 'job';
        $user_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("search/$role_to/for/$role_from/$user_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getSavedSearches($role_from, $role_to, $user_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSearch($data)
    {
        $params = array(
            'name' => 'name',
            'profileData' => array(
                'key' => 'value'
            )
        );

        $role_from = 'candidate';
        $role_for = 'job';
        $user_id = 3;

        $this->api->expects($this->once())
            ->method('post')
            ->with("search/$role_for/for/$role_from/$user_id", $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->search($role_for, $role_from, $user_id, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChangeNote($data)
    {
        $notes = "Some notes";

        $user_id = 3;
        $user_type = 'candidate';
        $for_user_id = 4;

        $this->api->expects($this->once())
            ->method('post')
            ->with("meta/notes", compact('user_id', 'user_type', 'for_user_id', 'notes'))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->changeNote($user_id, $user_type, $for_user_id, $notes));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChangeFolder($data)
    {
        $folder = "Some notes";

        $user_id = 3;
        $user_type = 'candidate';
        $for_user_id = 4;

        $this->api->expects($this->once())
            ->method('post')
            ->with("meta/folder", compact('user_id', 'user_type', 'for_user_id', 'folder'))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->changeFolder($user_id, $user_type, $for_user_id, $folder));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetOrganizationPositions($data)
    {
        $organization_id = 1;
        $user_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("organization/jobs/$organization_id/for/$user_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getOrganizationPositions($organization_id, $user_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFindJobs($data)
    {
        $params = array(
            'organization_id' => 1
        );

        $this->api->expects($this->once())
            ->method('post')
            ->with("job/find", $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->findJobs($params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetJob($data)
    {
        $id = 10;

        $this->api->expects($this->once())
            ->method('get')
            ->with("job/$id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getJob($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateJob($data)
    {
        $id = 10;
        $symbol_key = 'job';
        $name = 'Job';
        $org_id = 1;

        $this->api->expects($this->once())
            ->method('put')
            ->with("job/$id", array('symbol_key' => $symbol_key, 'name' => $name, 'organization_id' => $org_id))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->updateJob($id, $symbol_key, $name, $org_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetJobProfile($data)
    {
        $id = 10;

        $this->api->expects($this->once())
            ->method('get')
            ->with("profile/job/$id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getJobProfile($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateJobProfile($data)
    {
        $id = 10;
        $profile = array(
            'key' => 'value'
        );

        $this->api->expects($this->once())
            ->method('put')
            ->with("profile/job/$id", $profile)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->updateJobProfile($id, $profile));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCreateJob($data)
    {
        $symbol_key = 'job';
        $name = 'Job';
        $org_id = 1;
        $profile = array(
            'key' => 'value'
        );

        $this->api->expects($this->once())
            ->method('post')
            ->with("job", array_merge(array('symbol_key' => $symbol_key, 'name' => $name, 'organization_id' => $org_id), $profile))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->createJob($symbol_key, $name, $org_id, $profile));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDeleteJob($data)
    {
        $id = 10;
        $org_id = 1;

        $this->api->expects($this->once())
            ->method('delete')
            ->with("job/$id/$org_id")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->deleteJob($id, $org_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchingConfig($data)
    {
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with($role . '/matching/config')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getMatchingConfig($role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveMatchingConfig($data)
    {
        $role = 'candidate';
        $params = array(
            array(
                'main_field_id' => 1,
                'matching_field_id' => 2,
                'weight' => 4,
                'matching_type' => 3
            )
        );

        $this->api->expects($this->once())
            ->method('put')
            ->with($role . '/matching/config', array('configs' => $params))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveMatchingConfig($role, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetForm($data)
    {
        $role = 'candidate';
        $name = 'profile';

        $this->api->expects($this->once())
            ->method('get')
            ->with($role . '/' . $name . '/form')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getForm($role, $name));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveForm($data)
    {
        $role = 'candidate';
        $name = 'profile';
        $params = array('key' => 'value');

        $this->api->expects($this->once())
            ->method('put')
            ->with($role . '/' . $name . '/form', array('groups' => $params))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveForm($role, $name, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetFormTranslations($data)
    {
        $role = 'candidate';
        $name = 'profile';

        $this->api->expects($this->once())
            ->method('get')
            ->with("form/group/trans/$role/$name")
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getFormTranslations($role, $name));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveFormGroupTranslations($data)
    {
        $groups = array();

        $this->api->expects($this->once())
            ->method('post')
            ->with('form/group/trans', array('groups' => $groups))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveFormGroupTranslations($groups));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveModel($data)
    {
        $role = 'candidate';
        $params = array('key' => 'value');

        $this->api->expects($this->once())
            ->method('post')
            ->with($role . '/field/model', array('fields' => $params))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveModel($role, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetModelFields($data)
    {
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with($role . '/field/model')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getModelFields($role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetRoleFields($data)
    {
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with($role . '/field')
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getRoleFields($role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAddRoleField($data)
    {
        $role = 'candidate';
        $options = array(
            'symbol_key' => 'key',
            'name' => 'name',
            'type' => 1,
            'default_value' => '',
            'choices' => ''
        );

        $this->api->expects($this->once())
            ->method('post')
            ->with($role . '/field', array('fields' => array($options)))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->addRoleField($role, $options));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testEditRoleField($data)
    {
        $role = 'candidate';
        $options = array(
            'id' => 3,
            'symbol_key' => 'key',
            'name' => 'name',
            'type' => 1,
            'default_value' => '',
            'choices' => ''
        );

        $this->api->expects($this->once())
            ->method('put')
            ->with($role . '/field', array('fields' => array($options)))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->saveRoleField($role, $options));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDeleteRoleField($data)
    {
        $role = 'candidate';
        $id = 3;

        $this->api->expects($this->once())
            ->method('delete')
            ->with($role . '/field', array('ids' => array($id)))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->deleteRoleField($role, $id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveRoleFieldChoices($data)
    {
        $role = 'candidate';
        $field_id = 3;
        $choices = array();

        $this->api->expects($this->once())
            ->method('post')
            ->with($role . '/field/choices/' . $field_id, array('choices' => $choices))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->SaveRoleFieldChoices($role, $field_id, $choices));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateUserProfile($data)
    {
        $id = 1;
        $params = array('username' => 'admin', 'password' => 'admin');

        $this->api->expects($this->once())
            ->method('put')
            ->with('profile/' . $id, $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->updateUserProfile($id, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUserProfile($data)
    {
        $id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with('profile/' . $id)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUserProfile($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateUser($data)
    {
        $id = 1;
        $params = array('username' => 'admin', 'password' => 'admin');

        $this->api->expects($this->once())
            ->method('put')
            ->with('user/' . $id, $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->updateUser($id, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUser($data)
    {
        $id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with('user/' . $id)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUser($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUserBy($data)
    {
        $params = array('username' => 'admin', 'password' => 'admin');

        $this->api->expects($this->once())
            ->method('post')
            ->with('user/find', $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUserBy($params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCheckAuth($data)
    {
        $username = 'username';
        $password = 'password';

        $this->api->expects($this->once())
            ->method('post')
            ->with('user/check', array('username' => $username, 'password' => $password))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->checkAuth($username, $password));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRegister($data)
    {
        $username = 'username';
        $password = 'password';
        $email = 'email';
        $role = 'candidate';
        $profile = array();

        $this->api->expects($this->once())
            ->method('post')
            ->with('user', array('username' => $username, 'email' => $email, 'password' => $password, 'role' => $role, 'profile' => $profile))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->register($username, $email, $password, $role, $profile));
    }

    public function dataProvider()
    {
        return array(
            array(array('key' => 'value')),
            array(false)
        );
    }
}
