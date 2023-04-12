<?php

namespace App\Console;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->call(function () {

            // Start
            // TimeZone
            date_default_timezone_set("UTC");

            $username='mys';  # Add UserName
            $password='SAO:18.mYs'; # Add Password

            $region = '3'; #REGIONAL = 0, BRN = 1, IDN = 2, MYS = 3, PNG = 4, PHL = 5, TLS = 6
            $option = '0'; #MEDIAN = 0, LOW = 1, HIGH = 2
            $download_type = 1;  # 0 = text, 1 = csv, 2 = csvt

            $year = date('Y');
            $month= date('m');
            $day=date("d");
            $hour = date("h");
            $root_url='https://saoffg.bmkg.go.id/SAOFFG_CONSOLE/';



            $client = new Client();

            // Submit Form
            $res = $client->request('POST', $root_url.'index.php',[
                'verify' => false,
                'auth' => [$username, $password],

                'form_params' => [
                    'nav_yyyy'=>(string)$year,
                    'nav_mm'=>(string)$month,
                    'nav_dd'=>(string)$day,
                    'nav_hh'=>(string)$hour,
                    'nav_region'=>(string)$region,
                    'nav_ens'=>(string)$option,
                    'submit'=>'Submit'
                    ]
                ]);


            $html = (string)$res->getBody();



            // Extraction Html File to get Download Url

            //  Used to extract href for download link
            $extraction = preg_grep("/(COMPOSITE_)/i", explode(" ", $html));
            $extraction_string = implode(' ', $extraction);

            // Used to extract url path
            $second_extraction = preg_grep("/(COMPOSITE_)/i", explode("\"", $extraction_string));
            $second_extraction_string = implode(',', $second_extraction);

            // Used to create and extract path array
            $final_extraction= preg_grep("/(COMPOSITE_)/i", explode(",", $second_extraction_string));



            //  Download File
            $download_url=$root_url.$final_extraction[$download_type];
            print $download_url;

            // Select Download Type
            $file_type='';
            if($download_type==0){
                $file_type='txt';
            }else if($download_type==1){
                $file_type='csv';
            }else{
                $file_type='csvt';
            }

            $response = $client->request('GET', $download_url,[
                'verify' => false,
                'auth' => [$username, $password],
                'sink' => 'Download/'.$year."-".$month."-".$day." ".$hour.date(" A").'.'.$file_type
                ]);


                echo "Done !";





            // End
        })->hourly()->appendOutputTo('scheduler.log');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
