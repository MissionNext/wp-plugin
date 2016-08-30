<?php


namespace MissionNext\backend\pages\tabs\job;


use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class JobSearchBuilderTab extends AbstractFormBuilderTab {

    protected $predefinedFields = array('Name');
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
        return "job";
    }

} 