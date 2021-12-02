<?php

namespace Drupal\tableau_accessibility_block\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Logger\LoggerChannelFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for the custom endpoint.
 */
class Endpoint extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'tableau_accessibility_controller';
  }

  /**
   * The configFactory object used to manage configurations.
   *
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The requestStack object used to collect the query string.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  private $requestStack;

  /**
   * The httpClient object used to send a request to the 3rd party API endpoint.
   *
   * @var GuzzleHttp\Client
   */
  private $httpClient;

  /**
   * The logger object used for sending log messages for debugging purposes.
   *
   * @var Drupal\Core\Logger\LoggerChannelFactory
   */
  private $logger;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactory $configFactory, RequestStack $requestStack, Client $httpClient, LoggerChannelFactory $logger) {
    $this->configFactory = $configFactory;
    $this->requestStack = $requestStack;
    $this->http_client = $httpClient;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('request_stack'),
      $container->get('http_client'),
      $container->get('logger.factory')
    );
  }

  /**
   * Constructs a route for accessing the 3rd party API.
   *
   * The router _controller callback, maps the path
   * 'api/accessibility' to this method.
   *
   * _controller callbacks return a JSON response for our JS can use
   */
  public function checkUrl() {

    // Get the query parameter from the URL.
    $url = $this->requestStack->getCurrentRequest()->query->get('url');

    if (!$url) {
      $this->logger->get('tableau_accessibility_block')->notice('Missing required "url" query parameter.', []);
      return new JsonResponse([
        'data' => 'Invalid query string',
        'method' => 'GET',
        'status' => 400,
      ]);
    }

    // Load settings.
    $config = $this->configFactory->get('tableau_accessibility_block.settings');
    // Get values to use from the config.
    $base_url = $config->get('base_url.value');
    $external_endpoint = $config->get('external_endpoint.value');
    $authentication_header = $config->get('authentication_header.value');
    $header_value = $config->get('header_value.value');

    /*
     * Make the request using Guzzle
     * note: having trouble getting a valid response from the API, given the
     * authentication header and value provided, it looks like basic http
     * authentication, however I get the same result regardless of
     * username/password supplied.
     */

    $client = $this->http_client;
    $options = [
      'auth' => [$authentication_header, $header_value],
      'headers' => [
        'Accept' => 'application/json',
      ],
    ];
    if(!empty($base_url)) {
      $url = $base_url . $url;
    } else {
      $url = $_SERVER['SERVER_NAME'] . $url;
    }
    $requestUrl = $external_endpoint . '?url=' . $url;

    try {
      $request = $client->request('GET', $requestUrl, $options);
      if ($request) {
        /*
         * If the request was good, I'm assuming the data would be returned as
         * JSON - would return that to the JS file to display on the front end.
         */
        $result = json_decode($request->getBody(), TRUE);
        // Logging so I know when the $request is good.
        $this->logger->get('tableau_accessibility_block')->notice('API request result: @result', ['@result' => $result]);
        return new JsonResponse([
          'data' => $result,
          'method' => 'GET',
          'status' => 200,
        ]);

      }
      else {
        /*
         * Depending on what a good API call looks like, we could have an
         * alternative result here.
         */
        $this->logger->get('tableau_accessibility_block')->notice('API request failed.', []);
        return new JsonResponse([
          'data' => NULL,
          'method' => 'GET',
          'status' => 400,
        ]);
      }
    }
    catch (RequestException $requestException) {
      /*
       * Debug the $requestException - happening when I get a 403 status
       * code response from the API.
       */
      $this->logger->get('tableau_accessibility_block')->notice('Request Exception', []);
      return new JsonResponse([
        'data' => NULL,
        'method' => 'GET',
        'status' => 400,
      ]);
    }

  }

}
