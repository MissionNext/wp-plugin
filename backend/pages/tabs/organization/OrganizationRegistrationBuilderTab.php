<?php

namespace MissionNext\backend\pages\tabs\organization;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class OrganizationRegistrationBuilderTab extends AbstractFormBuilderTab {

    protected $predefinedFields = array('Username', 'Email', 'Password');

    function getFormName ()
    {
        return "registration";
    }

    function getRole ()
    {
        return "organization";
    }

} 