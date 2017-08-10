<?php

wp_enqueue_script( 'jquery' );

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('package_css', 'package.css');

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('package_js', 'package.js');

wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script( 'jquery-ui-dialog' );
wp_enqueue_script( 'jquery-ui-tabs' );
wp_enqueue_script( 'jquery-ui-tooltip' );

function mn_include_custom_css()
{
    $href = getResourceUrl('/resources/css/custom.css');

    echo <<<HTML
    <link rel="stylesheet" media="all" href="$href" />
HTML;

}

add_action('wp_head', 'mn_include_custom_css', 9999);
