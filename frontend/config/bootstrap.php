<?php

/*$site_url = get_bloginfo('url');
$divi_css = $site_url.'/wp-content/themes/Divi/style.css';

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('package_css', $divi_css);*/
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('package_css', 'package.css');

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('package_js', 'package.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-tooltip' ));
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/main', 'main.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-tooltip' ));

function mn_include_custom_css()
{
    $href = getResourceUrl('/resources/css/custom.css');

    echo <<<HTML
    <link rel="stylesheet" media="all" href="$href" />
HTML;

}

add_action('wp_head', 'mn_include_custom_css', 9999);
