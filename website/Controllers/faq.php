<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class QuestionAnswer
{


    public static function getFAQ (\Entities\Request $req):void
    {
        $req = (new \Queries\faq) ->retrieve(id);

        










}