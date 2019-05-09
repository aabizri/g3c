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
    private static function decodeFrames($stream, $max_amount = 688): array
    {
        $frames = [];
        for ($i = 0; $i < $max_amount; $i++) {
            // Create a new frame to be unmarshalled
            $frame = new Frame();
            $frames[$i] = $frame;

            // Decode from the stream
            try {
                $frame->decode($stream);
            } catch (\Exceptions\EOFException $e) {
                break;
            } catch (\Exception $e) {
                throw new \Exception(sprintf("Error while decoding frame %d (start-at-0), at byte %d of file", $i, ftell($stream)), 0, $e);
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
     * @param string $object_id
     * @param Frame $trame
     * @throws \Exception
     */
    public function pushFrame(string $object_id, Frame $frame)
    {
        // Frame marshalling
        $destination_path = "php://memory";
        $dst = fopen($destination_path, "r+");
        try {
            $frame->encode($dst);
        } catch (\Exception $e) {
            // TODO: wrap
            throw $e;
        }

        // Marshalled
        $str = file_get_contents($destination_path);

        // Etablissement des paramêtres
        $params = [
            "ACTION" => "COMMAND",
            "TEAM" => $object_id,
            "TRAME" => $str,
        ];

        // Build the URL
        $url = $this->endpoint . "?" . http_build_query($params);

        // Download results
        $res_stream = fopen($url, 'r');

        // Check for error
        $res_str = stream_get_contents($res_stream);
        if (strpos($res_str, "ERROR") !== false) {
            throw new \Exception(sprintf("ERROR returned by passerelle in response to trame sent: %s", $res_str));
        }
    }

    public static function test()
    {
        // TEST LOG DOWNLOAD
        echo "<pre>Trames Avant Traitement:
";
        $passerelle = new Passerelle();
        $log = $passerelle->downloadLog("3C3C");
        while (true) {
            $res = fgets($log);
            if ($res === false) break;
            echo $res;
        }

        // TEST FRAME PULLING
        echo "Télechargement & traitement des trames...";
        $frames = $passerelle->pullFrames("3C3C");
        echo "DONE\n";

        echo "Résultats: " . count($frames) . " trames reçues\n";
        echo "</pre>";

        echo "Première trame:\n";
        var_dump($frames[0]);

        // TEST FRAME PUSHING
        $testFrame = new Frame();
        $testFrame->tra = Frame::TRA_COURANTE;
        $testFrame->obj = "3C3C";
        $testFrame->typ = 53aaaa;
        $testFrame->req = Frame::REQ_WRITE;
        $testFrame->num = 2;
        $testFrame->tim = 4132;
        $testFrame->chk = 0;
        $testFrame->timestamp = 0;
        $testFrame->ans = "1111";
        echo "Trame de push:";
        var_dump($testFrame);

        echo "<pre>Push de la trame...";
        $passerelle->pushFrame("3C3C", $testFrame);
        echo "DONE</pre>";
    }
}

Passerelle::test();