<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 12/01/2018
 * Time: 14:22
 */

namespace Controllers;


use Queries\Query;

class Subscription
{
    public static function getSubscriptionState(\Entities\Request $req): void
    {
        $subscription_id = $req->getGET("subscription_id");
        $subscription = (new \Queries\Subscriptions) -> retrieve($subscription_id);

        // Publish view
        $data = ["subscription" => $subscription];
        \Helpers\DisplayManager::display("subscription", $data);
    }
}