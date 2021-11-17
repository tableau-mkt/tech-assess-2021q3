<?php

namespace Drupal\tableau\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\node\NodeInterface;

/**
 * Provides API endpoint.
 */

class TableauController extends ControllerBase {

    public function get_api_endpoint(Request $request, NodeInterface $node) {
        // Get header value from admin settings.
        $header_value = (\Drupal::config('tableau.adminsettings')->get('header_value'));
        // Create a stream.
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "x-tableau-auth:" . $header_value
            ]
        ];
        $context = stream_context_create($opts);
        // Google cloud function url.
        $cloud_url = 'https://us-central1-api-project-30183362591.cloudfunctions.net/axe-puppeteer-test';
        $dev_url = 'https://dev-tech-homework.pantheonsite.io';
        // Get the node id.
        $node_id = $node->id();
        // Concatenate json url.
        $json_url = $cloud_url . '?url=' . $dev_url . '/node/' . $node_id;
        // Open the file using the HTTP headers set above.
        $json = file_get_contents($json_url, false, $context);
        // Decode JSON string.
        $response['data'] = json_decode($json);

        return new JsonResponse($response);
    }
}
