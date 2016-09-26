<?php


namespace MissionNext\tests;


class ApiMethodsTest extends \PHPUnit_Framework_TestCase {

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

    public function setUp(){

        $this->client = $this->getMock('MissionNext\CurlClient', array());
        $this->api = $this->getMock('MissionNext\Api', array('get', 'post', 'put', 'delete'), array($this->client, $this->publicKey, $this->privateKey));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedJobForCandidate($data){
        $candidate_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/candidate/jobs/$candidate_id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getMatchedJobsForCandidate($candidate_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedOrganizationsForCandidate($data){
        $candidate_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/candidate/organizations/$candidate_id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getMatchedOrganizationsForCandidate($candidate_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedCandidatesForJob($data){
        $job_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/job/candidates/$job_id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getMatchedCandidatesForJob($job_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchedCandidatesForOrganization($data){
        $organization_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("match/organization/candidates/$organization_id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getMatchedCandidatesForOrganization($organization_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAddSavedSearches($data){
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

        $this->assertEquals( $data, $this->api->addSavedSearch($role_from, $role_to, $user_id, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDeleteSavedSearch($data){
        $id = 1;

        $this->api->expects($this->once())
            ->method('delete')
            ->with("search/$id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->deleteSavedSearch($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetSavedSearches($data){
        $role_from = 'candidate';
        $role_to = 'job';
        $user_id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with("search/$role_to/for/$role_from/$user_id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getSavedSearches($role_from, $role_to, $user_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSearch($data){
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

        $this->assertEquals( $data, $this->api->search($role_for, $role_from, $user_id, $params));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testChangeNote( $data ){

        $notes = "Some notes";

        $user_id = 3;
        $user_type = 'candidate';
        $for_user_id = 4;

        $this->api->expects($this->once())
            ->method('post')
            ->with("results/notes", compact('user_id', 'user_type', 'for_user_id', 'notes'))
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->changeNote($user_id, $user_type, $for_user_id, $notes));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testChangeFolder( $data ){

        $folder = "Some notes";

        $user_id = 3;
        $user_type = 'candidate';
        $for_user_id = 4;

        $this->api->expects($this->once())
            ->method('post')
            ->with("results/folder", compact('user_id', 'user_type', 'for_user_id', 'folder'))
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->changeFolder($user_id, $user_type, $for_user_id, $folder));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFindJobs($data){

        $params = array(
            'organization_id' => 1
        );

        $this->api->expects($this->once())
            ->method('post')
            ->with("job/find", $params)
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->findJobs($params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetJob($data){

        $id = 10;

        $this->api->expects($this->once())
            ->method('get')
            ->with("job/$id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getJob($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateJob($data){

        $id = 10;
        $symbol_key = 'job';
        $name = 'Job';
        $org_id = 1;

        $this->api->expects($this->once())
            ->method('put')
            ->with("job/$id", array('symbol_key' => $symbol_key, 'name' => $name, 'organization_id' => $org_id))
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->updateJob($id, $symbol_key, $name, $org_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetJobProfile($data){

        $id = 10;

        $this->api->expects($this->once())
            ->method('get')
            ->with("profile/job/$id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getJobProfile($id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateJobProfile($data){

        $id = 10;
        $profile = array(
            'key' => 'value'
        );

        $this->api->expects($this->once())
            ->method('put')
            ->with("profile/job/$id", $profile)
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->updateJobProfile($id, $profile));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCreateJob($data){

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

        $this->assertEquals( $data, $this->api->createJob($symbol_key, $name, $org_id, $profile));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDeleteJob($data){

        $id = 10;
        $org_id = 1;

        $this->api->expects($this->once())
            ->method('delete')
            ->with("job/$id/$org_id")
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->deleteJob($id, $org_id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetMatchingConfig($data){
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('get')
            ->with($role . '/matching/config')
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getMatchingConfig($role));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveMatchingConfig($data){
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
            ->with($role . '/matching/config', array( 'configs' => $params ))
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->saveMatchingConfig($role, $params));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetForm($data){

        $role = 'candidate';
        $name = 'profile';

        $this->api->expects($this->once())
            ->method('get')
            ->with($role . '/'. $name .'/form')
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->getForm($role, $name));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveForm($data){

        $role = 'candidate';
        $name = 'profile';
        $params = array( 'key' => 'value' );

        $this->api->expects($this->once())
            ->method('put')
            ->with($role . '/'. $name .'/form', array( 'groups' => $params ))
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->saveForm($role, $name, $params));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testSaveModel($data){

        $role = 'candidate';
        $params = array( 'key' => 'value' );

        $this->api->expects($this->once())
            ->method('post')
            ->with($role . '/field/model', array( 'fields' => $params ))
            ->will($this->returnValue($data));

        $this->assertEquals( $data, $this->api->saveModel($role, $params));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetModelFields($data){

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
    public function testGetRoleFields($data){

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
    public function testAddRoleField($data){

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
    public function testEditRoleField($data){

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
    public function testDeleteRoleField($data){

        $role = 'candidate';
        $id = 3;

        $this->api->expects($this->once())
            ->method('delete')
            ->with($role . '/field', array( 'ids' => array($id)))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->deleteRoleField($role, $id));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateUserProfile($data){

        $id = 1;
        $params = array('username' => 'admin', 'password' => 'admin');

        $this->api->expects($this->once())
            ->method('put')
            ->with('profile/'.$id, $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->updateUserProfile($id, $params));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUserProfile($data){

        $id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with('profile/'.$id)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUserProfile($id));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testUpdateUser($data){

        $id = 1;
        $params = array('username' => 'admin', 'password' => 'admin');

        $this->api->expects($this->once())
            ->method('put')
            ->with('user/'.$id, $params)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->updateUser($id, $params));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUser($data){

        $id = 1;

        $this->api->expects($this->once())
            ->method('get')
            ->with('user/'.$id)
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->getUser($id));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetUserBy($data){

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
    public function testCheckAuth($data){

        $username = 'username';
        $password = 'password';

        $this->api->expects($this->once())
            ->method('post')
            ->with('user/check', array( 'username' => $username, 'password' => $password ))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->checkAuth($username, $password));

    }

    /**
     * @dataProvider dataProvider
     */
    public function testRegister($data){

        $username = 'username';
        $password = 'password';
        $email = 'email';
        $role = 'candidate';

        $this->api->expects($this->once())
            ->method('post')
            ->with('user', array_merge(array( 'username' => $username, 'email' =>$email, 'password' => $password, 'role' => $role ), $data?$data:array()))
            ->will($this->returnValue($data));

        $this->assertEquals($data, $this->api->register($username, $email, $password, $role, $data));

    }

    public function dataProvider(){
        return array(
            array( array('key' => 'value') ),
            array( false )
        );
    }

} 