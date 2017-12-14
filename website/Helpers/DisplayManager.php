<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/10/17
 * Time: 1:47 PM
 */

namespace Helpers;


$sql = "SELECT id FROM sensors WHERE peripheral_uuid IN (SELECT uuid FROM peripherals WHERE room_id = :room_id)";

/**
 * Class DisplayManager
 * @package Helpers
 */
class DisplayManager
{
    private const views_directory = "Views".DIRECTORY_SEPARATOR;

    public static $views_categories = [
        "dashboard" => "Dashboard",
        "header" => "Layout",
        "footer" => "Layout",
        "head" => "Layout",
        "connexion" => "Users",
        "inscription" => "Users",
    ];

    /**
     * @return string
     */
    public static function websiteRootFS(string $dir = ""): string{
        return str_replace("/", DIRECTORY_SEPARATOR,$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."g3c".DIRECTORY_SEPARATOR.$dir);
    }

    /**
     * @return string
     */
    public static function websiteRootURL(string $dir = ""): string{
        return "http://localhost/g3c/".$dir;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function absolutifyFS(string $path, string $origin = ""): string{
        return self::websiteRootFS($origin).$path;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function absolutifyURL(string $path, string $origin = ""): string{
        return self::websiteRootURL($origin).$path;
    }

    /**
     * @param string $page_name the name of the page to be included
     * @return string[] the absolute paths to that page in "php" and "css"
     * @throws \Exception
     */
    private static function resolveSingleComponent(string $page_name): array {
        // Category
        if (!array_key_exists($page_name,self::$views_categories)) {
            throw new \Exception("Page not listed in internal repository : ".$page_name);
        }
        $category = self::$views_categories[$page_name];

        // Build the path
        $base_path = self::views_directory.$category.DIRECTORY_SEPARATOR.$page_name.DIRECTORY_SEPARATOR.$page_name;
        $res["php"] = $base_path.".php";
        if (!file_exists(self::absolutifyFS($res["php"]))) {
            throw new \Exception("Page listed in internal repository but not found on disk : ".self::absolutifyFS($res["php"]));
        }
        if (file_exists(self::absolutifyFS($base_path.".css"))) {
            $res["css"] = $base_path.".css";
        }

        return $res;
    }

    /**
     * @param array $page_names
     * @return array
     * @throws \Exception
     */
    public static function resolveMultipleComponents(array $page_names): array {
        $out = array();
        foreach ($page_names as $name) {
            $out[] = self::resolveSingleComponent($name);
        }
        return $out;
    }

    /**
     * @param string $name
     * @param array $data
     * @throws \Exception
     */
    public static function display(string $name, array $data): void {
        // Resolve components
        $components = self::resolveMultipleComponents(["head","header",$name,"footer"]);
        
        // For each, extract the css & php
        $php = array();
        $css = array();
        foreach ($components as $comp) {
            $php[] = $comp["php"];
            if (!empty($comp["css"])) {
                $css[] = self::absolutifyURL($comp["css"]);
            }
        }

        // Meta tags
        $meta = [
            "page_title" => $name,
            "base" => self::websiteRootURL(),
        ];

        // Header tags
        $header = [
            "website_name" => "LiveWell",
            "page_name" => $name,
            "tagline" => "Votre sécurité est notre priorité",
        ];

        // Foooter tag
        $footer = [
            "rights" => "2017 LiveWell, all rights reserved",
        ];

        // Include the php files
        foreach ($php as $toinc) {
            include($toinc);
        }
    }
}