<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 6/22/18
 * Time: 3:27 PM
 */

namespace Passerelle;


use Exceptions\EOFException;
use Exceptions\UnexpectedEOFException;

class FrameCODEC implements CODEC
{
    private static function selectSubCodec(int $tra): CODEC
    {
        switch ($tra) {
            case Frame::TRA_COURANTE:
                return new FrameCouranteCODEC();
            /*case Frame::TRA_RAPIDE:
                return new TrameRapideCODEC();
            case Frame::TRA_SYNCHRO:
                return new TrameSynchroCODEC();*/
            default:
                throw new \Exception(sprintf("Frame Type (FRA = %d | 0x%s) not supported or invalid", $tra, dechex($tra)));
        }
    }

    /**
     * @param $stream
     * @throws EOFException | UnexpectedEOFException | \Exception
     */
    public function decode(Frame $frame, $stream): void
    {
        // Decode frame number
        $tra = fgetc($stream);
        if ($tra === false) { // EOF
            throw new EOFException();
        }
        $frame->tra = ord($tra);

        // Decode inner
        self::selectSubCodec($frame->tra)->decode($frame, $stream);

        // Decode timestamp
        $date_raw = fread($stream, Frame::TIMESTAMP_SIZE);
        if ($date_raw === false) {
            throw new UnexpectedEOFException();
        }

        $date_format = "YmdGis";
        $date_datetime = \DateTime::createFromFormat($date_format, $date_raw);
        if ($date_datetime === false) {
            var_dump($frame);
            throw new \Exception(sprintf("Error while parsing date starting at byte %d: %s", ftell($stream), $date_raw));
        }
        $date_string = $date_datetime->format(\DateTime::ATOM);
        $frame->timestamp = $date_string;
    }

    /**
     * @param Frame $frame
     * @param $stream
     * @throws \Exception
     */
    public function encode(Frame $frame, $stream): void
    {
        $nb = fputs($stream, (string)$frame->tra);
        if ($nb === false) {
            throw new \Exception("Failure to write");
        }

        self::selectSubCodec($frame->tra)->encode($frame, $stream);
    }
}