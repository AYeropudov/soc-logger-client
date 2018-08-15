<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.08.18
 * Time: 11:03
 */

namespace Productors\LoggerClient;


interface ClientInterface
{
    public function send(array $message);
}