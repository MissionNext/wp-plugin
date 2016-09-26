<?php

namespace MissionNext\backend\pages\tabs\candidate;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class CandidateProfileBuilderTab extends AbstractFormBuilderTab {

    function getFormName ()
    {
        return "profile";
    }

    function getRole ()
    {
        return "candidate";
    }


} 