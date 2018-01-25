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
    private const VIEWS_DIR = "Views";
    private const DEFAULT_SUBROOT = "/";

    public static $views_categories = [
        "dashboard" => "Dashboard",
        "header" => "Layout",
        "footer" => "Layout",
        "head" => "Layout",
        "connexion" => "Users",
        "inscription" => "Users",
        "moncompte" => "Users",
        "mespieces" => "Rooms",
        "mesperipheriques"=> "Peripherals",
        "mysessions" => "Users",
        "store" => "Store",
        "majmdpreussie" => "Users",
    ];

    /**
     * @return string
     * @throws \Exception
     */
    private static function subroot(): string {
        $subroot = (new \Helpers\Config)->getSubroot() ?? self::DEFAULT_SUBROOT;
        return $subroot;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function websiteRootFS(string $dir = ""): string{
        return str_replace("/", DIRECTORY_SEPARATOR,$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.self::subroot().DIRECTORY_SEPARATOR.$dir);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function websiteRootURL(string $dir = ""): string{
        return "/".str_replace("\\","/", self::subroot())."/".$dir;
    }

    /**
     * @param string $path
     * @return string
     * @throws \Exception
     */
    public static function absolutifyFS(string $path, string $origin = ""): string{
        return self::websiteRootFS($origin).$path;
    }

    /**
     * @param string $path
     * @return string
     * @throws \Exception
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
        $base_path = self::VIEWS_DIR . "/" . $category . "/" . $page_name . "/" . $page_name;
        $res["php"] = str_replace("/",DIRECTORY_SEPARATOR,$base_path.".php");
        if (!file_exists(self::absolutifyFS($res["php"]))) {
            throw new \Exception("Page listed in internal repository but not found on disk : ".self::absolutifyFS($res["php"]));
        }
        if (file_exists(self::absolutifyFS($base_path.".css"))) {
            $res["css"] = $base_path.".css";
        }
        if (file_exists(self::absolutifyFS($base_path . ".js"))) {
            $res["js"] = $base_path . ".js";
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
    public static function display(string $name, array $data = []): void {
        // Resolve components
        $components = self::resolveMultipleComponents(["head","header",$name,"footer"]);

        // For each, extract the css & php
        $php = [];
        $css = [];
        $js = [];
        foreach ($components as $comp) {
            $php[] = $comp["php"];
            if (!empty($comp["css"])) {
                $css[] = self::absolutifyURL($comp["css"]);
            }
            if (!empty($comp["js"])) {
                $js[] = self::absolutifyURL($comp["js"]);
            }
        }

        // Add JS
        $js[] = "https://www.google.com/recaptcha/api.js";

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

    /**
     * Redirects to destination with 302 (temporary redirect)
     *
     * @param string $destination
     */
    public static function redirectToController(string $category, string $action): void
    {
        self::redirectToPath("index.php?c=$category&a=$action");
    }

    /**
     * Redirects to destination with 302 (temporary redirect)
     *
     * @param string $destination
     * @throws \Exception
     */
    public static function redirectToPath(string $destination): void
    {
        header("Location: " . \Helpers\DisplayManager::absolutifyURL($destination));
    }
}