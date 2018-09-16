<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*   for elastic search   */

use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;

/*   for elastic search   */


class ClientController extends Controller
{

    // Elasticsearch-php Client
    protected $elasticsearch;

    // Elastica Client
    protected $elastica;

    // Set up our clients
    public function __construct()
    {
        $this -> elasticsearch = ClientBuilder ::create() -> build();

        // Create an Elastica client
        $elasticaConfig =
            [
                'host'  => 'localhost',
                'port'  => 9200,
                'index' => 'pets',
            ];

        $this->elastica = new ElasticaClient($elasticaConfig);

    }

    // Test elasticsearch-php client
    public function elasticsearchTest()
    {
        // view our elasticsearch-php client object
        dump($this->elasticsearch);

        // Retrieve a document that we have indexed
        echo "\n\nRetrieve a document:\n";

        $params = [
            'index' => 'pets',
            'type' => 'dog',
            'id' => '1'
        ];
        $response = $this->elasticsearch->get($params);
        dump($response);
    }
}
