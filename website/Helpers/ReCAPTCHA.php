<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/26/17
 * Time: 6:27 PM
 */

namespace Helpers;


class ReCAPTCHA
{
    private const verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    private const js_api_url = 'https://www.google.com/recaptcha/api.js';

    private $site_key;
    private $secret_key;

    private static $themes = ["light", "dark"];
    private $theme = null;

    private static $types = ["image", "audio"];
    private $type = null;

    private $language = null;
    private $size = null;

    public function setLanguage(string $language): bool
    {
        $this->language = $language;
        return true;
    }

    public function __construct(string $site_key = "", string $secret_key = "")
    {
        $this->site_key = $site_key;
        $this->secret_key = $secret_key;
    }

    public function getScript(): string
    {
        $params = [];
        if (!empty($this->language)) {
            $params["hl"] = $this->language;
        }
        return '<script src=\"' . self::js_api_url . '?' . http_build_query($params) . '\"></script>';
    }

    public function getHTML(string $site_key = ""): string
    {
        if (empty($site_key)) {
            $site_key = $this->site_key;
        }
        if (empty($site_key)) {
            throw new \Exception("No site key given");
        }

        $attributes = 'data-sitekey="' . $this->site_key . '"';
        if (!empty($this->theme)) {
            $attributes .= 'data-theme="' . (string)$this->theme . '"';
        }
        if (!empty($this->type)) {
            $attributes .= 'data-type="' . (string)$this->type . '"';
        }
        if (!empty($this->size)) {
            $attributes .= 'data-size="' . (string)$this->size . '"';
        }

        return '<div class="g-recaptcha" ' . $attributes . '></div>';
    }

    public function verify(string $response, string $secret_key = "", string $remote_ip = ""): bool
    {
        if (empty($remote_ip)) {
            $remote_ip = $_SERVER["REMOTE_ADDR"];
        }

        if (empty($response)) {
            return false;
        }

        if (empty($secret_key)) {
            $secret_key = $this->secret_key;
        }
        if (empty($secret_key)) {
            throw new \Exception("Empty or null secret key for ReCAPTCHA");
        }

        $params = [
            'secret' => $secret_key,
            'response' => $response,
            'remoteip' => $remote_ip,
        ];

        $url = self::verify_url . '?' . http_build_query($params);

        $response = null;
        if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
        } else {
            $response = file_get_contents($url);
        }

        if (empty($response)) {
            throw new \Exception("Response empty or null, unexpected");
        }

        $response_data = json_decode($response);
        // TODO: parse error codes
        return $response_data->success;
    }
}