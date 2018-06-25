<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 1:29 AM
 */

namespace Passerelle;


class FrameCouranteRequeteCODEC implements CODEC
{
    /**
     * @param Frame $frame
     * @param $stream
     * @throws \Exception
     */
    public function decode(Frame $frame, $stream): void
    {
        $pos_before_read = ftell($stream);
        $read_size = Frame::VAL_SIZE + Frame::TIM_SIZE;
        $raw = fread($stream, $read_size);
        if ($raw === false) {
            throw new \Exception(sprintf("[FrameCouranteRequeteCODEC::decode] Failed read between byte %d and byte %d", $pos_before_read, $pos_before_read + $read_size));
        }

        // Extractions des parties
        $frame->val = substr($raw, 0, 4);
        $min = (int)substr($raw, 4, 2);
        $secs = (int)substr($raw, 6, 2);
        $frame->tim = $min * 60 + $secs;

        // Done !
        return;
    }

    public function encode(Frame $frame, $stream): void
    {

    }
}