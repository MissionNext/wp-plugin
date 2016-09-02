<?php

wp_enqueue_script( 'jquery' );

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('bootstrap_css', 'bootstrap.min.css');
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('mn/main', 'main.css');
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('mn/jquery-ui', 'jquery-ui-1.10.4.custom.min.css');
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addCSSResource('fonAwesome', 'font-awesome.min.css');

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('bootstrap_js', 'bootstrap.min.js');
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/modernizr', 'modernizr.js');
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/main', 'main.js', array( 'jquery-ui-datepicker' ));
\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/jquery.js.sortable', 'jquery.fn.sortable.js');

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
