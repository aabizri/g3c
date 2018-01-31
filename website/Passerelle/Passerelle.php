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
     *
     * @param string $object_id
     * @return string
     */
    private function downloadLog(string $object_id): string
    {
        // Etablissement des paramêtres
        $params = [
            "ACTION" => "GETLOG",
            "TEAM" => $object_id,
        ];

        // Build the URL
        $url = self::DEFAULT_ENDPOINT . "?" . http_build_query($params);

        // Creare the Endpoint cURL ressoucrce
        $ch = curl_init($url);

        // Set up immediate return
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute & get results
        $data = curl_exec($ch);

        // Close
        curl_close($ch);

        // Return
        return $data;
    }

    /**
     * Parses the log extracts it into an array of raw frames
     *
     * @param string $object_id
     * @return string[]
     */
    private static function splitLogIntoRawFrames(string $object_id): array
    {
        $set = str_split($object_id, 33);
        if ($set[count($set) - 1] === "\n") {
            unset($set[count($set) - 1]);
        }
        return $set;
    }

    /**
     * Parses each frame and return them
     *
     * @param string[] $raw_frames
     * @return \Passerelle\Trame[]
     */
    private static function parseFrames(array $raw_frames): array
    {
        $frames = [];
        foreach ($raw_frames as $raw_frame) {
            $frame = new TrameCouranteRequete;
            $frame->parse($raw_frame);
            $frames[] = $frame;
        }
        return $frames;
    }

    /**
     * @param string $object_id
     * @return \Passerelle\Trame[]
     */
    public function pullFrames(string $object_id): array
    {
        // First download log
        $log = $this->downloadLog($object_id);

        // Then split into frames
        $raw_frames = $this->splitLogIntoRawFrames($log);

        // Parse each frame
        $frames = $this->parseFrames($raw_frames);

        // Return them
        return $frames;
    }

    /**
     * @param Trame $trame
     */
    public static function pushFrame(Trame $trame)
    {

    }

    public static function test()
    {
        $passerelle = new Passerelle();
        $data = $passerelle->pullFrames("0002");
        var_dump($data);
    }
}

Passerelle::test();