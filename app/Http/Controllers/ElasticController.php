<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

class ElasticController
{

    /**
     * @return void
     * @throws \Elastic\Elasticsearch\Exception\AuthenticationException
     *
     *More Info  https://www.elastic.co/guide/en/elasticsearch/reference/current/rest-apis.html
     *
     * API key located here: https://sandbox-ad1166.kb.us-east-1.aws.found.io:9243/app/management/security/api_keys/
     */
    public function index()
    {


        $client = ClientBuilder::create()
            ->setHosts([env('ELASTIC_HOST')])
            ->setApiKey(env('ELASTIC_KEY'))
            ->build();


        $params = [
            'index' => 'my_index',
            'body' => ['testField12' => 'new row1']
        ];

        try {
//--------------------VERSION1  ---- strict search -----
//            $response = $client->index($params);
//            $params = [
//                'index' => 'asw_customer_sample_data',
//                'body'  => [
//                    'query' => [
//                        'match' => [
//                            'FNAME' => 'JONATHON'
//                        ]
//                    ]
//                ]
//            ];

            //--------VERSION2 ----- when you look "MEAGAN SMITH" as s phrase-----
//            $response = $client->index($params);
//            $params = [
//                'index' => 'asw_customer_sample_data',
//                'body' => [
//                    'query' => [
//                        'match_phrase' => [
//                            "DOCUMENT" =>"MEAGAN SMITH"
//                        ]
//                    ]
//                ]
//            ];

            //--------VERSION3 --- when you look "MEGAN" but exists  "MEAGAN" with typo as s phrase-----
//            $response = $client->index($params);
//            $params = [
//                'index' => 'asw_customer_sample_data',
//                'body' => [
//                    'query' => [
//                        'match' => [
//                            "DOCUMENT" =>
//                                [
//                                    'query'=> "MEGAN",
//                                    'fuzziness' => '1',
//                                ]
//                        ]
//                    ]
//                ]
//            ];


            ### VERSION 4--- when you look "MEGAN" with type  "MEAGAN" and want to seach with LAST NAME SMITH using multi fields with fuzzy -----
//            $response = $client->index($params);
//            $params = [
//                'index' => 'asw_customer_sample_data',
//                'body' => [
//                    'query' => [
//                        'match' => [
//                            "DOCUMENT" =>
//                                [
//                                    'query' => "MEGAN",
//                                    'fuzziness' => '2',
//                                ],
//                        ],
//                        'match' => [
//                            'LNAME' => [
//                                "query" => 'SMITH'
//                            ],
//                        ],
//                    ]
//                ]
//            ];
            ### VERSION 5--- when you look "Megan Smith Zyglobe" with type   to search with in multi fields with fuzzy -----
            $response = $client->index($params);
            $params = [
                'index' => 'asw_customer_sample_data',
                'body' => [
                    'query' => [

                        'multi_match' => [
                            'query' => "Zyglobe",
                            "fields" => ["LNAME", "FNAME", "ADDRESS1", "DOCUMENT"],
//                            'fuzziness' => '2',
                         ],

                    ]
                ]
            ];


            $response = $client->search($params);
            printf("Total docs: %d\n", $response['hits']['total']['value']);
            printf("Max score : %.4f\n", $response['hits']['max_score']);
            printf("Max score : %.4f\n", $response['hits']['create_weight']);
            printf("Took      : %d ms\n", $response['took']);


        } catch (ClientResponseException $e) {
            // manage the 4xx error
        } catch (ServerResponseException $e) {
            // manage the 5xx error
        } catch (\Exception $e) {
            // eg. network error like NoNodeAvailableException
        }
        if (isset($response->asArray()['hits']['hits'])) {
            foreach ($response->asArray()['hits']['hits'] as $_source) {
                echo $_source['_source']['DOCUMENT'] . "<BR>";

            }
        }
        if (isset($response->asArray()['hits']['hits'])) {
            dd($response->asArray()['hits']['hits']);
        }
        dd($response->asArray());


    }
}
