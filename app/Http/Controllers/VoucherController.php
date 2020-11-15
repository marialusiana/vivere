<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function store(Request $request)
    {
        $validasi = $this->validate(
            $request,
            [
                'voucher'      => 'required|mimes:csv,txt',
            ]
        );

        DB::beginTransaction();
       
        try {
           
            DB::commit();

            $file            = $request->file('voucher');
            $destinationPath = 'voucher';

            $filename = "voucher_".Carbon::now()->format('Ymdhis').".txt";
            $file->move($destinationPath, $filename);

            $params = [
                'file_name'       => $filename,
            ];

            $path_file         = public_path() . '/voucher/'.$filename ;
    
            $file = file($path_file);

            foreach ($file as $key=>$line){
                $array[$key] = explode('|', $line);

                if ($key > 0){
                    $voucher = Voucher::create(
                        [
                            'Voucher_Number'    => $array[$key][0] ,
                            'Voucher_Material'  => $array[$key][1],
                            'Basic_Material'    => $array[$key][2],
                            'Valid_From'        => DateTime::createFromFormat('d/m/Y', $array[1][3]),
                            'Valid_To'          => DateTime::createFromFormat('d/m/Y', $array[1][4]),
                            'Reg_Date'          => DateTime::createFromFormat('d/m/Y', $array[1][5]),
                            'Status'            => $array[$key][6],
                            'Voucher_Value'     => $array[$key][7],
                            'Currency'          => $array[$key][8],
                            'Voucher_Type'      => $array[$key][9],
                        ]
                    );

                }
               
            }

            $count_data = count($array);

            $result = Voucher::orderBy('id', 'desc')->take($count_data-1)->get();
            Log::info('Simpan data voucher success -'. Carbon::now()->format('Ymdhis'));
            return [
                "status" => true,
                "code" => 200,
                "data" => $result
            ];

        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                "status" => false,
                "code" => 500,
                "data" => "failed"
            ];
        }
    }


}
