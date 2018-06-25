<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 1:29 AM
 */

namespace Passerelle;


class FrameCouranteCODEC implements CODEC
{
    private const CHECK_CHECKSUM_DEFAULT = false;
    private $checkChecksum;

    private static function selectSubCodec(int $req): CODEC
    {
        switch ($req) {
            case Frame::REQ_WRITE:
                return new FrameCouranteRequeteCODEC();
            case Frame::REQ_READ:
                return new FrameCouranteReponseCODEC();
            case Frame::REQ_READ_WRITE:
            default:
                throw new \Exception(sprintf("Request Type (REQ = %d) not supported or invalid", $req));
        }
    }

    public function __construct(bool $checkChecksum = self::CHECK_CHECKSUM_DEFAULT)
    {
        $this->checkChecksum = $checkChecksum;
    }

    /**
     * @param Frame $frame
     * @param $stream
     * @throws \Exception
     */
    public function decode(Frame $frame, $stream): void
    {
        // Read the OBJ, REQ, TYP & NUM
        $before_read_pos = ftell($stream);
        $read_size = Frame::OBJ_SIZE + Frame::REQ_SIZE + Frame::TYP_SIZE + Frame::NUM_SIZE;

        $raw = fgets($stream, $read_size);
        if ($raw === false) {
            throw new \Exception("Error reading Frame Courante between byte %d and %d", $before_read_pos, $before_read_pos + $read_size);
        }

        // Split & assign
        $frame->obj = substr($raw, 0, Frame::OBJ_SIZE);
        $frame->req = ord($raw[Frame::OBJ_SIZE]);
        $frame->typ = ord($raw[Frame::OBJ_SIZE + Frame::REQ_SIZE]);
        $frame->num = (int)hexdec(substr($raw, Frame::OBJ_SIZE + Frame::REQ_SIZE + Frame::TYP_SIZE, Frame::NUM_SIZE));

        // Find codec according to $req
        $subCODEC = self::selectSubCodec($frame->req);

        // Decode further down
        $subCODEC->decode($frame, $stream);

        // Parse checksum
        $before_read_pos = ftell($stream);
        $chk = fgets($stream, Frame::CHK_SIZE);
        if ($chk === false) {
            throw new \Exception("Error reading Checksum between byte %d and %d", $before_read_pos, $before_read_pos + Frame::CHK_SIZE);
        }
        $frame->chk = (int)hexdec($chk);
    }

    public function encode(Frame $frame, $stream): void
    {

    }

    public static function calcCHK(string $line)
    {
        $calc_chk = 0;
        foreach (str_split(substr($line, 0, strlen($line) - 2)) as $char) {
            $calc_chk += ord($char);
            var_dump($char, ord($char));
        }

        // Récupération du checksum déclaré
        $decl_chk = (int)hexdec(substr($line, strlen($line) - 2, 2));

        // Vérification
        if ($calc_chk !== $decl_chk) {
            throw new \Exception(sprintf("Invalid cheksum: declared %d, calculated %d", $decl_chk, $calc_chk));
        }
    }
}