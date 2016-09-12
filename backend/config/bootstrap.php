<?php

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script( 'jquery-ui-dialog' );
wp_enqueue_script( 'jquery-ui-tooltip' );
wp_enqueue_script( 'jquery-ui-sortable' );
wp_enqueue_script( 'jquery-ui-tabs' );
wp_enqueue_script( 'jquery-form' );
wp_enqueue_script('jquery-ui-datepicker');

wp_enqueue_style ('wp-jquery-ui-dialog');

\MissionNext\lib\core\Context::getInstance()->getResourceManager()->addJSResource('mn/modernizr', 'modernizr.js');