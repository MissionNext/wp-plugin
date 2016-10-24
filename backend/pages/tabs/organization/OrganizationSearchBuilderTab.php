<?php

namespace MissionNext\backend\pages\tabs\organization;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class OrganizationSearchBuilderTab extends AbstractFormBuilderTab {

    protected $predefinedFields = array();
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
        return "organization";
    }

} 