<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.08.18
 * Time: 11:01
 */

namespace Productors\LoggerClient;


use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ProductorsLoggerInterface
 * @package Productors\LoggerClient
 */
interface ProductorsLoggerInterface
{
    /**
     * @param \Throwable $throwable
     * @return mixed
     */
    public function __invoke(\Throwable $throwable, ServerRequestInterface $request);
}