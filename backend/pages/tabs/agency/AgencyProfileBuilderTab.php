<?php

namespace MissionNext\backend\pages\tabs\agency;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class AgencyProfileBuilderTab extends AbstractFormBuilderTab {

    function getFormName ()
    {
        return "profile";
    }

    function getRole ()
    {
        return "agency";
    }

} 