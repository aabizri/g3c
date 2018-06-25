<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/2/18
 * Time: 10:52 PM
 */

namespace Passerelle;

require_once "../Helpers/autoloader.php";

class Passerelle
{
    public const DEFAULT_ENDPOINT = "http://projets-tomcat.isep.fr:8080/appService";
    public $endpoint = self::DEFAULT_ENDPOINT;

    /**
     * Passerelle constructor.
     * @param string $endpoint
     */
    public function __construct(string $endpoint = self::DEFAULT_ENDPOINT)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Download the log from the endpoint in raw form
     **
     * @param string $object_id
     * @return resource
     */
    private function downloadLog(string $object_id)
    {
        // Etablissement des paramêtres
        $params = [
            "ACTION" => "GETLOG",
            "TEAM" => $object_id,
        ];

        // Build the URL
        $url = $this->endpoint . "?" . http_build_query($params);

        // Download all
        $stream = fopen($url, 'r');


        // Return it
        return $stream;
    }

    /**
     * Parses the log and extracts it into an array of frames
     *
     * @param string $object_id
     * @return Frame[]
     * @throws \Exception
     */
    private static function decodeFrames($stream): array
    {
        $frames = [];
        for ($i = 0; ; $i++) {
            // Create a new frame to be unmarshalled
            $frame = new Frame();
            $frames[$i] = $frame;

            // Decode from the stream
            try {
                $frame->decode($stream);
            } catch (\Exceptions\EOFException $e) {
                break;
            } catch (\Exception $e) {
                throw new \Exception(sprintf("Error while decoding frame %d, at byte %d of file", $i, ftell($stream)), 0, $e);
            }
        }

        return $frames;
    }
    /**
     * @param string $object_id
     * @return \Passerelle\Frame[]
     */
    public function pullFrames(string $object_id): array
    {
        // First download log
        $stream = $this->downloadLog($object_id);

        // Parse each frame
        $frames = $this->decodeFrames($stream);

        // Return them
        return $frames;
    }

    /**
     * @param Frame $trame
     */
    public static function pushFrame(Frame $trame)
    {

    }

    public static function test()
    {
        $passerelle = new Passerelle();
        $log = $passerelle->downloadLog("3C3C");
        while (true) {
            $res = fgets($log);
            if ($res === false) break;
            echo $res;
        }
        $frames = $passerelle->pullFrames("3C3C");
        var_dump($frames);
    }
}

Passerelle::test();