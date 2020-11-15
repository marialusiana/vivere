<?php
/**
 * Created by PhpStorm.
 * User: oselwang
 * Date: 27/03/19
 * Time: 16.06
 */

namespace App\Http\Requests;

use App\Http\Requests\RequestForm;
use App\Models\UserAddress;

class RegisterRequest extends RequestForm
{
    protected $rules = [

        'password'        => 'min:8|required',
        'email'           => 'required|unique:users',
        'first_name'      => 'required',
        'last_name'       => 'required'
    ];

   

    public function handle()
    {
        $this->isValid();

        $temp = explode(' ', $this->fields('name'));
        $firstname = '';
        $lastname = '';
        for ($i = 0; $i < count($temp); $i++) {
            if ($i != count($temp) - 1) {
                $firstname .= $temp[$i] . ' ';
            } elseif (count($temp) == 1) {
                $firstname .= $temp[$i];
            } else {
                $lastname .= $temp[$i];
            }
        }
        $data = [
            'password'   => $this->fields('password'),
            'first_name' => ucwords(trim($firstname)),
            'last_name'  => ucwords($lastname),
            'email'       => $this->fields('email')
        ];

        $user = \Sentinel::registerAndActivate($data);
       
        // $role = \Sentinel::findRoleById(3);
        // $role->users()
        //     ->attach($user);

        return $user;
    }
}