<?php

namespace MissionNext\backend\pages\tabs\agency;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class AgencyRegistrationBuilderTab extends AbstractFormBuilderTab {

    protected $predefinedFields = array('Username', 'Email', 'Password');

    function getFormName ()
    {
        return "registration";
    }

    function getRole ()
    {
        return "agency";
    }

} 