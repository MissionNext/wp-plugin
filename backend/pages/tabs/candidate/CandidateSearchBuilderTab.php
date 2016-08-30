<?php

namespace MissionNext\backend\pages\tabs\candidate;

use MissionNext\backend\pages\tabs\AbstractFormBuilderTab;

class CandidateSearchBuilderTab extends AbstractFormBuilderTab {

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
        return "candidate";
    }


} 