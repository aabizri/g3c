<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 1:29 AM
 */

namespace Passerelle;


class FrameCouranteReponseCODEC implements CODEC
{
    public function decode(Frame $frame, $stream): void
    {
        $pos_before_read = ftell($stream);
        $ans_raw = fgets($stream, Frame::ANS_SIZE);
        if ($ans_raw === false) {
            throw new \Exception("[FrameCouranteReponseCODEC::decode] Failed read between byte %d and %d in frame", $pos_before_read, Frame::ANS_SIZE);
        }
        $frame->ans = $ans_raw;
    }

    public function encode(Frame $frame, $stream): void
    {
        $pos_before_write = ftell($stream);
        $ok = fputs($stream, $frame->ans);
        if ($ok === false) {
            throw new \Exception("[FrameCouranteReponseCODEC::decode] Failed write between byte %d and %d in frame", $pos_before_write, Frame::ANS_SIZE);
        }
    }
}