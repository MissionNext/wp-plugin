<?php


namespace MissionNext\lib;


class AvatarManager {

    private $user_id_being_edited;

    public function __construct(){
        add_filter( 'get_avatar', array( $this, 'get_avatar'), 10, 5 );
    }

    public function avatar_delete( $user_id ) {
        $old_avatars = get_user_meta( $user_id, Constants::AVATAR_TOKEN, true );
        $upload_path = wp_upload_dir();

        if ( is_array( $old_avatars ) ) {
            foreach ( $old_avatars as $old_avatar ) {
                $old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
                @unlink( $old_avatar_path );
            }
        }

        delete_user_meta( $user_id, Constants::AVATAR_TOKEN );
    }

    public function hasAvatar($user_id){
        return (bool) get_user_meta( $user_id, Constants::AVATAR_TOKEN, true );
    }

    public function updateAvatar( $user_id, $file ) {

        if ( ! empty( $file['name'] ) ) {

            // Allowed file extensions/types
            $mimes = array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif'          => 'image/gif',
                'png'          => 'image/png',
            );

            // Front end support - shortcode, bbPress, etc
            if ( ! function_exists( 'wp_handle_upload' ) )
                require_once ABSPATH . 'wp-admin/includes/file.php';

            // Delete old images if successful
            $this->avatar_delete( $user_id );

            // Need to be more secure since low privelege users can upload
            if ( strstr( $file['name'], '.php' ) )
                return __("Unsupported file type.", Constants::TEXT_DOMAIN);

            // Make user_id known to unique_filename_callback function
            $this->user_id_being_edited = $user_id;
            $avatar = wp_handle_upload( $file, array( 'mimes' => $mimes, 'test_form' => false, 'unique_filename_callback' => array( $this, 'unique_filename_callback' ) ) );

            // Handle failures
            if ( empty( $avatar['file'] ) ) {
                return $avatar['error'];
            }

            // Save user information (overwriting previous)
            update_user_meta( $user_id, Constants::AVATAR_TOKEN, array( 'full' => $avatar['url'] ) );
        }
    }

    public function get_avatar( $avatar, $id_or_email, $size = 96, $default = '', $alt = false ) {
        // Determine if we recive an ID or string
        if ( is_numeric( $id_or_email ) )
            $user_id = (int) $id_or_email;
        elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
            $user_id = $user->ID;
        elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
            $user_id = (int) $id_or_email->user_id;

        if ( empty( $user_id ) )
            return $avatar;

        $local_avatars = get_user_meta( $user_id, Constants::AVATAR_TOKEN, true );



        if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) )
            return $avatar;

        $size = (int) $size;

        if ( empty( $alt ) )
            $alt = get_the_author_meta( 'display_name', $user_id );

        // Generate a new size
        if ( empty( $local_avatars[$size] ) ) {

            $upload_path      = wp_upload_dir();
            $avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
            $image            = wp_get_image_editor( $avatar_full_path );

            if ( ! is_wp_error( $image ) ) {
                $image->resize( $size, $size, true );
                $image_sized = $image->save();
            }

            // Deal with original being >= to original image (or lack of sizing ability)
            $local_avatars[$size] = is_wp_error( $image_sized ) ? $local_avatars[$size] = $local_avatars['full'] : str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );

            // Save updated avatar sizes
            update_user_meta( $user_id, Constants::AVATAR_TOKEN, $local_avatars );

        } elseif ( substr( $local_avatars[$size], 0, 4 ) != 'http' ) {
            $local_avatars[$size] = home_url( $local_avatars[$size] );
        }

        $author_class = is_author( $user_id ) ? ' current-author' : '' ;
        $avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars[$size] . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";

        return $avatar;
    }

    public function unique_filename_callback( $dir, $name, $ext ) {
        $user = get_user_by( 'id', (int) $this->user_id_being_edited );
        $random_postfix = substr(time(), -4);
        $name = $base_name = sanitize_file_name( $user->display_name . '_avatar' );
        $number = 1;

        while ( file_exists( $dir . "/$name$ext" ) ) {
            $name = $base_name . '_' . $number;
            $number++;
        }

        return $name . '_' . $random_postfix . $ext;
    }
}