<?php


namespace MissionNext\backend\pages\tabs\agency;


use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class AgencySearchBuilderTab extends AbstractFormBuilderTab {

    protected $predefinedFields = array('Username');
    protected $canHaveInnerDependencies = false;
    protected $canHaveOuterDependencies = false;
    protected $canHaveExpandedFields = true;
    protected $canHavePrivateGroups = false;

    function getFormName ()
    {
        return "search";
    }

    function getRole ()
    {
        return "agency";
    }

} 