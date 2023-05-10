<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\ClientErrorResponseException;
use Elasticsearch\Common\Exceptions\ServerErrorResponseException;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Console\Command;

class IndexSampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:sample
        {--limit=1000 : The count of sample records to insert. 0 = no limit, Default: 1000}
        {--all : Ignore any limit and import the complete sample data set}
        {--index=asw_customer_sample_data : The name of the ES index. Default: asw_customer_sample_data}
        {--v|verbose : Verbose logging';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(re)index the ASW customer sample data into ElasticSearch';

    private function getCSVPath()
    {
        return storage_path('app/sample-asw-customer-data.csv');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $index = $this->option('index') ?: 'asw_customer_sample_data';
        $limiter = $this->option('limit') ?: 1000;
        $verbose = $this->option('verbose');
        if ($this->option('all')) {
            $limiter = false;
        }
        if ($verbose) {
            $this->line('Connecting to Elasticsearch...');
        }
        try {
//            $es = ClientBuilder::create()
//                ->setHosts(['elastic:9200'])
//                ->build();
            $es = ClientBuilder::create()
                ->setElasticCloudId('sandbox:dXMtZWFzdC0xLmF3NDBiNW.......')
                ->setApiKey('.........', '.........')
                ->build();
        } catch (NoNodesAvailableException $e) { // can't seem to catch this
            // $this->error('Connection Failed...');
            // $this->error($e->getMessage());
            // return Command::FAILURE;
        }
        //    dump($es->info());
        if ($verbose) {
            $this->info('Connected to Elasticsearch');
        }
        // Confirm overwrite of existing index
        if ($es->indices()->exists(compact('index'))) {
            $idxCnt = $es->indices()->stats(compact('index'))['indices'][$index]['total']['docs']['count'];
            if ($idxCnt > 0) {
                if (!$this->confirm('Delete existing index of ' . number_format($idxCnt, 0) . ' records?')) {
                    $this->error('Operation canceled by user');
                    return Command::FAILURE;
                }
            }
        }
        // Create the index with mapping
        if ($es->indices()->exists(compact('index'))) {
            // Delete the index if it exists
            try {
                if ($verbose) {
                    $this->line('Deleting existing index');
                }
                $r = $es->indices()->delete(compact('index'));
            } catch (\Exception $e) {
                $this->error('Could not delete the index');
                return Command::FAILURE;
            }
        }
        if ($verbose) {
            $this->line('Creating the index, "' . $index . '"');
        }
        $resp = $es->indices()->create([
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    'properties' => [
                        'ACCTNUM' => ['type' => 'text'],
                        'ADDRNUM' => ['type' => 'integer'],
                        'CAG' => ['type' => 'text'],
                        'ACCTNAME' => ['type' => 'text'],
                        'ADDRNAME' => ['type' => 'text'],
                        'FNAME' => ['type' => 'text'],
                        'LNAME' => ['type' => 'text'],
                        'ADDR1' => ['type' => 'text'],
                        'ADDR3' => ['type' => 'text'],
                        'ADDR2' => ['type' => 'text'],
                        'CITY' => ['type' => 'text'],
                        'STATE' => ['type' => 'text'],
                        'POSTCODE' => ['type' => 'text'],
                        'ORIGSTATEPOST' => ['type' => 'text'],
                        'COUNTRY' => ['type' => 'keyword'],
                        'CUSTSTATUS' => ['type' => 'keyword'],
                        'TITLE' => ['type' => 'text'],
                        'CREATEDATE' => ['type' => 'text'],
                        'PHONE1' => ['type' => 'text'],
                        'PHONE2' => ['type' => 'text'],
                        'EMAIL' => ['type' => 'text'],
                        'CURRENTERPSALES' => ['type' => 'float'],
                        'OLDERPSALES' => ['type' => 'float'],
                        'INVOICENUM' => ['type' => 'text'],
                        'INVOICEADDRNUM' => ['type' => 'integer'],
                        'SHIPTOADDRNUM' => ['type' => 'integer'],
                        'CURRENCY' => ['type' => 'keyword'],
                        'TOPCODE' => ['type' => 'keyword'],
                        'DEBTORNUM' => ['type' => 'text'],
                        'DEBTORADDRNUM' => ['type' => 'integer'],
                        'CREDITLIMIT' => ['type' => 'float'],
                        'AVETTISITEID' => ['type' => 'text'],
                        'DOCUMENT' => ['type' => 'text'],
                    ]
                ]
            ]
        ]);
        // Get Index information
        // $resp = $es->indices()->getSettings(compact('index'));
        // Get number of documents in the index
        // $idxCnt = $es->indices()->stats(compact('index'))['indices'][$index]['total']['docs']['count'];
        // Stream read large CSV file and insert data
        $this->line('Importing sample data');
        $csvLineCount = $this->getCSVLineCount();
        $timer_start = microtime(true);
        $fh = fopen($this->getCSVPath(), 'r');
        $bar = $this->output->createProgressBar($limiter ?: $csvLineCount);
        $lineNum = 1;
        $bar->start();
        while (($raw_str = fgets($fh)) !== false) {
            // skip the first line, which is the header (column names)
            if ($lineNum > 1) {
                $row = str_getcsv($raw_str);
                $data = [
                    'ACCTNUM' => $row[0],
                    'ADDRNUM' => $row[1],
                    'CAG' => $row[2],
                    'ACCTNAME' => $row[3],
                    'ADDRNAME' => $row[4],
                    'FNAME' => $row[5],
                    'LNAME' => $row[6],
                    'ADDR1' => $row[7],
                    'ADDR3' => $row[8],
                    'ADDR2' => $row[9],
                    'CITY' => $row[10],
                    'STATE' => $row[11],
                    'POSTCODE' => $row[12],
                    'ORIGSTATEPOST' => $row[13],
                    'COUNTRY' => $row[14],
                    'CUSTSTATUS' => $row[15],
                    'TITLE' => $row[16],
                    'CREATEDATE' => $row[17],
                    'PHONE1' => $row[18],
                    'PHONE2' => $row[19],
                    'EMAIL' => $row[20],
                    'CURRENTERPSALES' => $row[21],
                    'OLDERPSALES' => $row[22],
                    'INVOICENUM' => $row[23],
                    'INVOICEADDRNUM' => $row[24],
                    'SHIPTOADDRNUM' => $row[25],
                    'CURRENCY' => $row[26],
                    'TOPCODE' => $row[27],
                    'DEBTORNUM' => $row[28],
                    'DEBTORADDRNUM' => $row[29],
                    'CREDITLIMIT' => $row[30],
                    'AVETTISITEID' => $row[31],
                    'DOCUMENT' => $row[32],
                ];
                $resp = $es->index(['index' => $index, 'body' => $data]);
                // if ($verbose && $resp['result'] === 'created') {
                //     $this->line($resp['result'] . ' ' . $row[0] . '.' . $row[1]);
                // }
                if ($resp['result'] !== 'created') {
                    $this->error($resp['result'] . ' Record: ' . $row[0] . '.' . $row[1]);
                }
                unset($row);
                unset($data);
                $bar->advance();
            }
            $lineNum++;
            // line #1 doesn't count as a record because it's the header
            if ($limiter && $lineNum - 1 > $limiter) {
                break;
            }
        }
        $timer_end = microtime(true);
        $total_insertion_time = ($timer_end - $timer_start);
        $bar->finish();
        $this->newLine(1);
        $this->info('Imported ' . ($limiter ? '' : 'all ') . number_format($lineNum - 2, 0) . ' records in ' . round($total_insertion_time, 2) . ' seconds.');
        return Command::SUCCESS;
    }

    /**
     * The count of lines in a CSV without loading the CSV or iterating rows. To be able to quickly get the count before
     * parsing the file
     * @return int
     */
    private function getCSVLineCount()
    {
        $file = new \SplFileObject($this->getCSVPath(), 'r');
        $file->seek(PHP_INT_MAX);
        return $file->key(); //TODO: if PHP_INT_MAX === key, we probably ran into physical limitation of file size
    }

    public function searchTest()
    {
        //return response()->json($es->search(['index' => $index,
        //    'body' => [
        //        'query' => [
        //            'multi_match' => [
        //                'query' => 'smith',
        //                'fields' => ['ACCTNAME^4', 'ADDRNAME^2', 'ADDR1', 'ADDR2']
        //                'fuzziness' => 2
        //            ]
        //        ]
        //
        //    ]
        //]), $status=200, $headers=[], $options=JSON_PRETTY_PRINT);
    }
}
