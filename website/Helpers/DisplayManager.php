<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/10/17
 * Time: 1:47 PM
 */

namespace Helpers;


class DisplayManager
{
    private const views_directory = "Views/";
    private const root = "http://localhost/g3c/";

    public static $views_categories = [
        "dashboard" => "Dashboard",
        "header" => "Layout",
        "footer" => "Layout",
        "head" => "Layout",
    ];

    public static function documentRoot(): string{
        return $_SERVER["DOCUMENT_ROOT"]."/g3c/";
    }

    public static function statify(string $path): string{
        return self::documentRoot().$path;
    }

    /**
     * @param string $page_name the name of the page to be included
     * @return string[] the absolute paths to that page in "php" and "css"
     * @throws \Exception
     */
    private static function resolve(string $page_name): array {
        // Category
        $category = self::$views_categories[$page_name];
        if (empty($category)) {
            throw new \Exception("Page not listed in internal repository : ".$page_name);
        }

        // Build the path
        $base_path = self::views_directory.$category."/".$page_name."/".$page_name;
        $res["php"] = $base_path.".php";
        if (!file_exists(self::statify($res["php"]))) {
            throw new \Exception("Page listed in internal repository but not found on disk : ".$_SERVER["DOCUMENT_ROOT"].$res["php"]);
        }
        if (file_exists(self::statify($base_path.".css"))) {
            $res["css"] = $base_path.".css";
        }

        return $res;
    }

    private static function resolveAndInclude(string $page_name): void {
        try {
            $path = self::resolve($page_name);
            include($path);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * @param string $name
     * @param array $data
     * @throws \Exception
     */
    public static function display(string $name, array $data): void {
        // Resolve components
        $head = self::resolve("head");
        $header = self::resolve("header");
        $page = self::resolve($name);
        $footer = self::resolve("footer");
        
        // For each, extract the css
        $components = [$head,$header,$page,$footer];
        $php = array();
        $css = array();
        foreach ($components as $comp) {
            $php[] = $comp["php"];
            if (!empty($comp["css"])) {
                $css[] = self::root.$comp["css"];
            }
        }

        // Incorporate the values
        foreach ($php as $toinc) {
            include($toinc);
        }
    }
}