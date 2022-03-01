<?php

namespace Drupal\pjg_tech_assessment\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Renderer;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns Ajax response for Accessibility Ajax Route.
 */
class AjaxController extends ControllerBase {

  /**
   * Renderer service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  private Renderer $renderer;

  /**
   * AjaxController constructor.
   *
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Renderer service.
   */
  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
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
      $container->get('renderer')
    );
  }

  /**
   * Returns Ajax Response containing API response.
   *
   * @param string $path
   *  The path making the request to the accessibility api.
   *
   * @return Drupal\Core\Ajax\AjaxResponse
   *   Ajax response containing html to render time.
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \Exception
   */
  public function createAjaxResponse(): AjaxResponse {

    $config_data = \Drupal::config('pjgTechAssessment.adminsettings')->getRawData();

    $client = new Client([
      'base_uri' => 'https://us-central1-api-project-30183362591.cloudfunctions.net/'
    ]);

    $path = str_replace(\Drupal::request()->getSchemeAndHttpHost(), '', $_ENV['HTTP_REFERER']);

    $url = 'https://dev-tech-homework.pantheonsite.io'.$path;

    $response = $client->request('GET', 'axe-puppeteer-test', [
      'headers' => ['x-tableau-auth' => $config_data['header_value']],
      'query' => ['url' => $url]
    ]);

    $body = json_decode($response->getBody()->getContents());

    $results = [];

    foreach($body->violations as $violation){
      $count = count($violation->nodes);

      $results[] = [
        'id' => $violation -> id,
        'count' => $count,
        'rating' => $count > 2 ? 'bad' : 'good'
      ];
    }

    $violations_data = [
      '#theme' => 'violations-container',
      '#violations' => $results
    ];

    $ajax_response = new AjaxResponse();

    $ajax_response->addCommand(new HTMLCommand('#violations-div', $this->renderer->render($violations_data)));

    return $ajax_response;
  }
}
