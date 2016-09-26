<?php

namespace MissionNext\backend\pages\tabs\organization;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class OrganizationProfileBuilderTab extends AbstractFormBuilderTab {

    function getFormName ()
    {
        return "profile";
    }

    function getRole ()
    {
        return "organization";
    }

} 