<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Voucher;
use App\Models\Employee;
use DateTime;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validasi = $this->validate(
            $request,
            [
                'nama'          => 'required',
                'alamat'        => 'required',
                'no_hp'         => 'required',
                'hobi'          => 'required',
                'gender'        => 'required',
            ]
        );

        DB::beginTransaction();

        try {
           
            DB::commit();
            $employee = new Employee();
            $employee->nama = $request->get('nama');
            $employee->alamat = $request->get('alamat');
            $employee->no_hp = $request->get('no_hp');
            $employee->hobi = $request->get('hobi');
            $employee->gender = $request->get('gender');
            $employee->save();
      
            $txt = $employee['nama'].'|'.$employee['alamat'].'|'.$employee['no_hp'].'|'.$employee['hobi'].'|'.$employee['gender'];
            $filename = "user_employee_".Carbon::now()->format('Ymdhis').".txt";
            Storage::disk('local')->put($filename, $txt);
            
            Log::info('Simpan data dan export user employee to txt in storage success -'. Carbon::now()->format('Ymdhis'));
            return [
                "status" => true,
                "code" => 200,
                "data" => "succcess"
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
