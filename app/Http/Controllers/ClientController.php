<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


/*   for elastic search   */

use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;
use Elastica;
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
                                        "age"        => array("type" => "long"),
                                        "gender"     => array("type" => "text"),
                                        "color"      => array("type" => "text"),
                                        "braveBird"  => array("type" => "boolean"),
                                        "home town"  => array("type" => "text"),
                                        "about"      => array("type" => "text"),
                                        "registered" => array("type" => "date"),
                                    ],

                            ],
                    ],
            ];

// this to create thew type
//        $response = $this -> elasticsearch -> indices() -> putMapping($params);
//        dump($response);


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


        // Index a document
        $params = [
            'index' => 'pets',
            'type'  => 'bird',
            'id'    => '1',
            'body'  => [
                'name'       => 'Charlie Shittles',
                'age'        => '13',
                'gender'     => 'male',
                'color'      => 'brown',
                'braveBird'  => true,
                'home town'  => 'Phoenix, Arizona',
                'about'      => 'Lore ipsum dolor sit amet, consectetur
              adipiscing elit. Maecenas pharetra lobortis tristique. Magna aute enim officia quis ad in duis ad in duis partiatur. Inproident  eiusmo/pets/dog/1/d et excepteur quis in voluptate qui culpa sint dolore. Ad eu sint fugiat deserunt proident minim. Deserunt ea commodo dolor anim sunt deserunt.',
                'registered' => date('Y-m-d'),
            ],
        ];
        $response = $this -> elasticsearch -> index($params);
        dump($response);

        // Bulk index documents
        $faker = Faker ::create();

        $params = [];

        for ($i = 0 ; $i < 100 ; $i ++) {
            $params[ 'body' ][] = [
                'index' => [
                    '_index' => 'pets',
                    '_type'  => 'bird',

                ],
            ];
            $gender = $faker -> randomElement(['male', 'female']);
            $age = $faker -> numberBetween(1, 15);
            $params[ 'body' ][] =
                [
                    'name'       => $faker -> name($gender),
                    'age'        => $age,
                    'gender'     => $gender,
                    'color'      => $faker -> safeColorName,
                    'braveBird'  => $faker -> boolean,
                    'home town'  => "{$faker->city}, {$faker->state}",
                    'about'      => $faker -> realText(),
                    'registered' => $faker -> dateTimeBetween("-{$age} years", 'now') -> format('Y-m-d'),

                ];


        }

        $response = $this -> elasticsearch -> bulk($params);
        dump($response);


    }

//  http://elasticsearch.local/elastica/data
    public function elasticaData()
    {
        $catType = $this -> elasticaIndex -> getType('cat');

        $mapping = new Elastica\Type\Mapping($catType, [
            'name'       => array('type' => 'text'),
            "age"        => array("type" => "long"),
            "gender"     => array("type" => "text"),
            "color"      => array("type" => "text"),
            "braveBird"  => array("type" => "boolean"),
            "home town"  => array("type" => "text"),
            "about"      => array("type" => "text"),
            "registered" => array("type" => "date"),
        ]);

//        $response = $mapping->send();
//        dump($response);

        // Index a document
        $catDocument = new Elastica\Document();
        $catDocument -> setData([
            'name'       => 'Meowlixander Hamilton',
            'age'        => '4',
            'gender'     => 'male',
            'color'      => 'orange',
            'braveBird'  => true,
            'home town'  => 'Portland, Oregon',
            'about'      => 'Lore ipsum dolor sit amet, consectetur
              adipiscing elit. Maecenas pharetra lobortis tristique. Magna aute enim officia quis ad in duis ad in duis partiatur.',
            'registered' => date('Y-m-d'),
        ]);

        $response = $catType -> addDocument($catDocument);
        dump($response);

        // Bulk index documents
        $faker = Faker ::create();

        $documents = [];
        for ($i = 0 ; $i < 100 ; $i ++) {
            $gender = $faker -> randomElement(['male', 'female']);
            $age = $faker -> numberBetween(1, 15);
            $documents[] = (new Elastica\Document()) -> setData([
                'name'       => $faker -> name($gender),
                'age'        => $age,
                'gender'     => $gender,
                'color'      => $faker -> safeColorName,
                'braveBird'  => $faker -> boolean,
                'home town'  => "{$faker->city}, {$faker->state}",
                'about'      => $faker -> realText(),
                'registered' => $faker -> dateTimeBetween("-{$age} years", 'now') -> format('Y-m-d'),
            ]);
        }

        $response = $catType -> addDocuments($documents);
        dump($response);
    }

// http://elasticsearch.local/elasticsearch/queries
    public function elasticsearchQueries()
    {
        // Run  our query on the name field
        $params = [
            'index' => 'pets',
            'type'  => 'cat',
            'body'  => [
                'query' => [
                    'match' => [
                        'name' => 'MD',
                    ],
                ],
            ],
        ];
        $response = $this -> elasticsearch -> search($params);
        dump($response);
        $params = [
            'index' => 'pets',
            'type'  => 'cat',
            'size'  => 15,
            'body'  => [
                'query' => [
                    'match' => [
                        'about' => 'Alice',
                    ],
                ],
            ],
        ];

        $response = $this -> elasticsearch -> search($params);
        dump($response);

        // Run a boolean query
        $params = [
            'index' => 'pets',
            'type'  => 'cat',
            'body'  => [
                'query' => [
                    'bool' => [
                        'must'   => [
                            'match' => ['about' => 'Alice'],
                        ],
                        'should' => [
                            'term' => ['braveBird' => true],
                            'term' => ['gender' => 'male'],
                        ],
                        'filter' => [
                            'range' => [
                                'registered' => [
                                    'gte' => '2015-01-01',
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $response = $this->elasticsearch->search($params);
        dump($response);


    }

    public function elasticaQueries()
    {
        // GET the cat type
        $catType = $this->elasticaIndex->getType('cat');

        // Run our query on the same field
        $query = new Elastica\Query;

        $match = new Elastica\Query\Match('name','MD');
        $query->setQuery($match);

        $response = $catType->search($query);
        dump($response);

        // Run our query on the about field
        $query = new Elastica\Query;

        $match = new Elastica\Query\Match;
        $match->setField('about','Alice');
        $query->setQuery($match);
        $query->setSize(15);

        $response = $catType->search($query);
        dump($response);

        // Run a boolean query
        $query = new Elastica\Query;

        $bool = new Elastica\Query\BoolQuery;
        $mustMatch = new Elastica\Query\Match('about','Alice');
        $shouldOne = new Elastica\Query\Term(['braveBird'=>true]);
        $shouldTwo = new Elastica\Query\Term(['gender'=>'female']);
        $filterRange = new Elastica\Query\Range('registered',['gte'=>'2015-01-01']);

        $bool->addMust($mustMatch);
        $bool->addShould($shouldOne);
        $bool->addShould($shouldTwo);
        $bool->addFilter($filterRange);

        $query->setQuery($bool);

        $response = $catType->search($query);
        dump($response);
    }
}
