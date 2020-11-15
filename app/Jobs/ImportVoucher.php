<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class ImportVoucher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $request;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request = array())
    {
        $this->request=$request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

       

        $file_name         = $this->request['file_name'];
        $path_file         = public_path() . '/voucher/'.$file_name ;

        $file = file($path_file);

       

        try {

            foreach (explode("\n", $file) as $key=>$line){
                $array[$key] = explode('|', $line);
            }

            dd($array);
            

            $data = array_map('str_getcsv', file($path_file));
            $jum = 0 ;
            $total = 0 ;
            $customer_number = [];
            $user_id =[];
            $fcm_token=[];
            foreach ($data as $row) {
                $jum++;
                if ($jum > 1) {
                    $total++;
                    array_push($customer_number, $row[0]);
                    array_push($user_id, $row[2]);
                    array_push($fcm_token, $row[6]);
                }
            }

            $dataUser = array(
                "user_id"           => $user_id,
                "fcm_token"         => $fcm_token,
                "customer_number"   => $customer_number,
            );


            $notifikasi    = Notification::where('id', $notification_id)->update(
                [
                    'recipient'            => json_encode($dataUser),
                    'recipient_count'      => $total,
                ]
            );

            Log::info('Update Recipient success ');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            Log::error('Update Recipient failed ');
        }
    }
}
