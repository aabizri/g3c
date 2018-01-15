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
        if (empty($subscription_id))
        {
            http_response_code(400);
            return;
        }
        $subscription = (new \Queries\Subscriptions) -> retrieve($subscription_id);
        if (empty($subscription))
        {
            http_response_code(400);
            return;
        }


        // Publish view
        $data["subscription"] = $subscription;
        \Helpers\DisplayManager::display("subscription", $data);
    }
}