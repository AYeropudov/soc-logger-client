<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.05.18
 * Time: 14:57
 */

namespace Productors\LoggerClient;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class Logger
 * @package Productors\LoggerClient
 */
class Logger implements ProductorsLoggerInterface
{

    /** @var ClientInterface */
    private $client;

    /**
     * Logger constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if(!empty($config)) {
            $clientConfig = $config['client'];
            $this->client = new $clientConfig['handler']($config);

        } else {
            $this->client = new ClientDefault();
        }
    }

    /**
     * @param \Throwable $throwable
     */
    public function __invoke(\Throwable $throwable, ServerRequestInterface $request)
    {
        $message = $this->prepareThrowable($throwable, $request);
        if($this->getClient()) {
            $this->getClient()->send($message);
        }
    }

    /**
     * @param \Throwable $throwable
     */
    public function prepareThrowable(\Throwable $throwable, ServerRequestInterface $request){
        
        $message = [
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'message' => $throwable->getMessage()
        ];

        $message['body'] = $request->getBody()->getContents();
        $message['params'] = $request->getAttributes();
        $message['parsedBody'] = $request->getParsedBody();
        $message['cookies'] = $request->getCookieParams();
        $message['query'] = $request->getQueryParams();
        $message['headers'] = $request->getHeaders();
        $message['user'] = $request->getAttribute('identity')->getId();
        if (property_exists($throwable, 'level')) {
            $message['level'] = $throwable->getLevel();
        } else {
            $message['level'] = 500;
        }
        if (property_exists($throwable, 'response')) {
            $message['response'] = $throwable->getResponse()->getBody()->getContents();
        }
        return $message;
    }

    /**
     * @return ClientInterface | bool
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client): void
    {
        $this->client = $client;
    }
}