<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*   for elastic search   */

use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;

/*   for elastic search   */

use Faker\Factory as Faker;


class ClientController extends Controller
{

    // Elasticsearch-php Client
    protected $elasticsearch;

    // Elastica Client
    protected $elastica;

    // Elastica Index
    protected $elasticaIndex;

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

        $this -> elastica = new ElasticaClient($elasticaConfig);
        $this -> elasticaIndex = $this -> elastica -> getIndex('pets');

    }

    // Test elasticsearch-php client
    // http://elasticsearch.local/elasticsearch/test
    public function elasticsearchTest()
    {
        // view our elasticsearch-php client object
        dump($this -> elasticsearch);

        // Retrieve a document that we have indexed
        echo "\n\nRetrieve a document:\n";

        $params = [
            'index' => 'pets',
            'type'  => 'dog',
            'id'    => '1',
        ];
        $response = $this -> elasticsearch -> get($params);
        dump($response);
    }

    // Test elastica client
    // http://elasticsearch.local/elastica/test
    public function elasticaTest()
    {
        // view our elastica client object
        dump($this -> elastica);

        // view our elastica index
        dump($this -> elasticaIndex);

        // Get the types and mappings
        echo "\n\nGet types and mappings:\n";
        $dogType = $this -> elasticaIndex -> getType('dog');
        dump($dogType -> getMapping());

        // Retrieve a document that we have indexed
        echo "\n\nGet a document:\n";
        $response = $dogType -> getDocument('1');
        dump($response);

    }

    // Data structures with elasticsearch-php
    //  http://elasticsearch.local/elasticsearch/data
    public function elasticsearchData()
    {
        // set the index mapping
        // i change all the type to text
        // as string remove in 6.0
        // https://www.elastic.co/guide/en/elasticsearch/reference/master/mapping.html
        $params =
            [
                'index' => 'pets',
                'type'  => 'bird',
                'body'  =>
                    [

                        'bird' =>
                            [
                                '_source'    =>
                                    [
                                        'enabled' => true,
                                    ],
                                'properties' =>
                                    [
                                        "name"       => array("type" => "text"),
                                        "age"       => array("type" => "long"),
                                        "gender"     => array("type" => "text"),
                                        "color"      => array("type"=> "text"),
                                        "braveBird"  => array("type"=> "boolean"),
                                        "home town"  => array("type" => "text"),
                                        "about"      => array("type" => "text"),
                                        "registered" => array("type" => "date"),
                                    ],

                            ],
                    ],
            ];


        $response = $this -> elasticsearch -> indices() -> putMapping($params);
        dump($response);

        // Get A mapping
        $params =
            [
                'index' => 'pets',
                'type'  => 'bird',
            ];

        $response = $this -> elasticsearch -> indices() -> getMapping($params);
        dump($response);
        // https://www.elastic.co/blog/removal-of-mapping-types-elasticsearch
        // since the are no two types for the same index
        // i delete the old one
        // with curl -XDELETE http://elasticsearch.local:9200/pets
        // https://www.elastic.co/guide/en/elasticsearch/reference/current/indices-create-index.html
        // then create new index with :
        // in browser in extension
        // PUT pets

    }
}
