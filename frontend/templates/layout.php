<?php
/**
 * @var Array $user
 * @var String $userRole
 * @var String $content
 */
get_header();

?>
    <div id="main" role="main" >
        <div class="container">
            <div class="row">
                    <?php renderTemplate("common/_messages", array('messages' => $messages)) ?>
                    <?php echo $content ?>
            </div>
        </div>
    </div>
<?php
renderTemplate('_email_popup');
get_footer();
?>