<?php

wp_enqueue_script( 'jquery' );

/*$site_url = get_bloginfo('url');
$divi_css = $site_url.'/wp-content/themes/Divi/style.css';

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('package_css', $divi_css);*/
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('package_css', 'package.css');

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('package_js', 'package.js');
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/main', 'main.js', array( 'jquery-ui-datepicker' ));

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
