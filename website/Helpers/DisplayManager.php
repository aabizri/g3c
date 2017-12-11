<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/10/17
 * Time: 1:47 PM
 */

namespace Helpers;

/**
 * Class DisplayManager
 * @package Helpers
 */
class DisplayManager
{
    private const views_directory = "Views/";

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
    public static function websiteRootFS(): string{
        return $_SERVER["DOCUMENT_ROOT"]."/g3c/";
    }

    /**
     * @return string
     */
    public static function websiteRootURL(): string{
        return "http://localhost/g3c/";
    }

    /**
     * @param string $path
     * @return string
     */
    public static function absolutifyFS(string $path): string{
        return self::websiteRootFS().$path;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function absolutifyURL(string $path): string{
        return self::websiteRootURL().$path;
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
        $base_path = self::views_directory.$category."/".$page_name."/".$page_name;
        $res["php"] = $base_path.".php";
        if (!file_exists(self::absolutifyFS($res["php"]))) {
            throw new \Exception("Page listed in internal repository but not found on disk : ".$_SERVER["DOCUMENT_ROOT"].$res["php"]);
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