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
    private const views_directory = "/../Views/";

    public static $views_categories = [
        "dashboard" => "Dashboard",
        "header" => "Layout",
        "footer" => "Layout",
    ];

    /**
     * @param string $page_name the name of the page to be included
     * @return string the absolute path to that page
     * @throws \Exception
     */
    private static function resolve(string $page_name): string {
        // Category
        $category = self::$views_categories["$page_name"];
        if (empty($category)) {
            throw new \Exception("Page not listed in internal repository : ".$page_name);
        }

        // Build the path
        $path = __DIR__.self::views_directory.$category."/".$page_name."/".$page_name.".php";
        if (!file_exists($path)) {
            throw new \Exception("Page listed in internal repository but not found on disk : ".$path);
        }

        return $path;
    }

    private static function resolveAndInclude(string $page_name): void {
        try {
            $path = self::resolve($page_name);
            include($path);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    public static function display(string $name,array $data): void {

       // Include the header
       self::resolveAndInclude("header");

       // Include the $name
       self::resolveAndInclude($name);

       // Include the footer
       self::resolveAndInclude("footer");
    }
}