<?php

namespace MissionNext\backend\pages\tabs\candidate;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class CandidateRegistrationBuilderTab extends AbstractFormBuilderTab {

    protected $predefinedFields = array('Username', 'Email', 'Password');

    function getFormName ()
    {
        return "registration";
    }

    function getRole ()
    {
        return "candidate";
    }

} 