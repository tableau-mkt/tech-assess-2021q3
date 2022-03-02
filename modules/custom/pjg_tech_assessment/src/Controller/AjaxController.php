<?php

namespace Drupal\pjg_tech_assessment\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Renderer;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Returns Ajax response for Accessibility Ajax Route.
 */
class AjaxController extends ControllerBase {

  /**
   * Config Factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;


  /**
   * Renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  private Renderer $renderer;

  /**
   * Request service.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  private RequestStack $request;

  /**
   * AjaxController constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   Config Factory service.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Renderer service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request
   *   Request service.
   */
  public function __construct(ConfigFactory $configFactory, Renderer $renderer, RequestStack $request) {
    $this->configFactory = $configFactory;
    $this->renderer = $renderer;
    $this->request = $request;
  }

  /**
   * Creates static instance of InstrumentCitationFinderController container.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The service container.
   *
   * @return \Drupal\pjg_tech_assessment\Controller\AjaxController
   *   Returns the static instance of the
   *   AjaxController container.
   */
  public static function create(ContainerInterface $container): AjaxController {
    return new static(
      $container->get('config.factory'),
      $container->get('renderer'),
      $container->get('request_stack')
    );
  }

  /**
   * Returns Ajax Response containing API response.
   *
   * @return Drupal\Core\Ajax\AjaxResponse
   *   Ajax response containing html to render time.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \Exception
   */
  public function createAjaxResponse(): AjaxResponse {

    // Access the block's configuration
    // and save it for use in the request.
    $config_data = $this->configFactory->get('accessibilityApiBlock.config')->getRawData();

    // Build Client with base uri,
    // to be paired later with query page.
    $client = new Client([
      'base_uri' => 'https://us-central1-api-project-30183362591.cloudfunctions.net/',
    ]);

    // The ajax call is pseudo-divorced from the node the block sits on,
    // so to get the node's path, the scheme and host are split from the
    // HTTP referer then pair it with the appropriate url.
    $path = str_replace($this->request->getCurrentRequest()->getSchemeAndHttpHost(), '', $_ENV['HTTP_REFERER']);
    $url = 'https://dev-tech-homework.pantheonsite.io' . $path;

    // Build the request with the headers and query parameters.
    $response = $client->request('GET', 'axe-puppeteer-test', [
      'headers' => ['x-tableau-auth' => $config_data['x_tableau_auth_value']],
      'query' => ['url' => $url],
    ]);

    // Convert the response into a body.
    $body = json_decode($response->getBody()->getContents());

    // Reformat the body information for what is necessary to display.
    $results = [];

    foreach ($body->violations as $violation) {
      $count = count($violation->nodes);

      $results[] = [
        'id' => $violation->id,
        'count' => $count,
        'rating' => $count > 2 ? 'bad' : 'good',
      ];
    }

    // Construct render array for AjaxResponse Object.
    $violations_data = [
      '#theme' => 'violations-container',
      '#violations' => $results,
    ];

    // Build AjaxResponse Object and its commands.
    $ajax_response = new AjaxResponse();

    $ajax_response->addCommand(new HTMLCommand('#violations-div', $this->renderer->render($violations_data)));

    // Return AjaxResponse Object.
    return $ajax_response;
  }

}
