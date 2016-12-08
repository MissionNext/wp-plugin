<?php

if( strpos($_SERVER['REQUEST_URI'], '/wp-signup.php') === 0){
    status_header(301);
    header("Location: /signup/candidate", true, 301);
    exit;
}

//Multisite login fix
if(is_multisite() && isset($current_site)){

    $current_site->domain = $_SERVER['SERVER_NAME'];
    $current_site->cookie_domain = $_SERVER['SERVER_NAME'];
    if($_SERVER['REQUEST_URI'] == '/wp-signup.php'){
        $blog_id = $current_site->blog_id;
    }
}

function mn_process_login( $is_ajax = false ) {

    if(!$_POST || !isset($_POST['log']) || !isset($_POST['pwd'])){
        return;
    }

    $user_login = $_POST['log'];
    $password = $_POST['pwd'];

    if ( isset( $_REQUEST[ 'redirect_to' ] ) && $_REQUEST[ 'redirect_to' ] != '' ) {
        $redirect_to = $_REQUEST[ 'redirect_to' ];
        // Redirect to https if user wants ssl
        if ( isset( $secure_cookie ) && $secure_cookie && false !== strpos( $redirect_to, 'wp-admin') )
            $redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
    }

    $api = \MissionNext\lib\Core\Context::getInstance()->getApiManager()->getApi();

    if(!$api){
        return;
    }

    $user = $api->checkAuth($user_login, mb_strtolower($password));

    if(!$user){
        return;
    }

    if(!\MissionNext\lib\SiteConfig::isAgencyOn() && $user['roles'] == 'agency'){
        wp_redirect(get_permalink(get_page_by_path(\MissionNext\lib\Constants::PAGE_NO_AGENCY_ROLE)));
        return;
    }

    $email = $user['email'];

    if ( ($user_id = email_exists( $email )) || ($user_id = username_exists($user['username'])) ) {
        $user_data  = get_userdata( $user_id );
        $user_login = $user_data->user_login;

        update_user_meta($user_id, \MissionNext\lib\Constants::META_KEY, $user['id']);
        update_user_meta($user_id, \MissionNext\lib\Constants::META_ROLE, $user['roles']);

    } else { // Create new user

        $userdata = array( 'user_login' => $user_login, 'user_email' => $email, 'first_name' => '', 'last_name' => '', 'user_url' => '', 'user_pass' => wp_generate_password() );

        // Create a new user
        $user_id = wp_insert_user( $userdata );

        if(!is_wp_error($user_id)){
            update_user_meta($user_id, \MissionNext\lib\Constants::META_KEY, $user['id']);
            update_user_meta($user_id, \MissionNext\lib\Constants::META_ROLE, $user['roles']);
        } else {
            return;
        }
    }

    wp_set_auth_cookie( $user_id, isset($_POST['rememberme'])?$_POST['rememberme']:false );
    $api->setUserId($user['id']);
    $redirect_to = isset($redirect_to)? $redirect_to : "/dashboard";

    if ( $is_ajax ) {
        echo '{"redirect":"' . $redirect_to . '"}';
    } else {
        wp_safe_redirect( $redirect_to );
    }

    exit();
}
// Hook to 'login_form_' . $action
add_action( 'login_form_login', 'mn_process_login');

//function mn_login_errors($errors){
//    $errors->errors = array();
//    if(!get_option( 'users_can_register')) {
//        $errors->add( 'registration_disabled', __('<strong>ERROR</strong>: Registration is disabled.', Constants::TEXT_DOMAIN) );
//    } else {
//        $errors->add( 'authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.', Constants::TEXT_DOMAIN) );
//    }
//
//    return $errors;
//}

function mn_password_reset(WP_User $user, $new_pass){

    $context = \MissionNext\lib\core\Context::getInstance();
    $api = $context->getInstance()->getApiManager()->getApi();
    $user_id = get_user_meta($user->ID, \MissionNext\lib\Constants::META_KEY, true);

    $api->updateUser($user_id, array('password' => $new_pass));
}
add_action('password_reset', 'mn_password_reset', 10, 2);

function mn_login_url( $login_url, $redirect ) {
    return '/login'. ($redirect?"?redirect_to=".urlencode($redirect):'');
}
add_filter( 'login_url', 'mn_login_url', 10, 2 );

function mn_register_url( $register_url ) {
    return '/signup/candidate';
}
add_filter( 'register_url', 'mn_register_url', 10, 2 );

function mn_lostpwd_page( $lostpassword_url, $redirect ) {
    return home_url() . '/password/request';
}
add_filter( 'lostpassword_url', 'mn_lostpwd_page', 10, 2 );

//Remove admin bar from users
function mn_filter_admin_bar(){
    if (!current_user_can('edit_posts')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('plugins_loaded', 'mn_filter_admin_bar');

//Restrict users to wp-admin
function mn_access_admin_init() {
    if ( !current_user_can('edit_posts') && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_safe_redirect(site_url("/dashboard"));
        exit;
    }
}
add_action( 'admin_init', 'mn_access_admin_init' );

//SHORTCODE

function mn_register_login_widget(){
    register_widget("MissionNext\\LoginWidget");
}

add_action('widgets_init', 'mn_register_login_widget');

function removeMyCustomerAndKillHim(){
    $user_login = $_REQUEST["username"];
    $secret =  md5(DELETE_USER_SECRET);

    if($secret == $_REQUEST["secret"]){
        if ( username_exists($user_login)) {
            $id = username_exists($user_login);
            require_once( ABSPATH . 'wp-admin/includes/ms.php' );
            $result = wpmu_delete_user($id);
            echo $result;
        } else {
            echo "User does not exist";
        }
    } else {
        echo "Token is invalid";
    }
}
add_action("wp_ajax_user_deleting_function", "removeMyCustomerAndKillHim");
add_action("wp_ajax_nopriv_user_deleting_function", "removeMyCustomerAndKillHim");

function checkNewPassword()
{
    if (isset($_POST['pass1']) && !empty($_POST['pass1'])) {
        $api = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi();
        $response = $api->setNewPassword($_POST['nickname'], $_POST['pass1']);
    }
}

add_action('edit_user_profile_update', 'checkNewPassword');