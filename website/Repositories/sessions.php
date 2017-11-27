<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 27/11/2017
 * Time: 11:56
 */

namespace Repositories;


class Sessions extends Repository
{
    public static function insert(\Entities\Sessions $s)
    {
        $data = [
            'id' => $s->id,
            'user' => $s->user,
            'started' => $s->started,
            'expiry' => $s->expiry,
            'canceled' =>$s->canceled,

        ];
    }

}