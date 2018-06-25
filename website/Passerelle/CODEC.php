<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 6/22/18
 * Time: 3:32 PM
 */

namespace Passerelle;


interface CODEC
{
    public function decode(Frame $frame, $stream): void;

    public function encode(Frame $frame, $stream): void;
}