<?php
/**
 * Created By DhanPris
 *
 * @Filename     User.php
 * @LastModified 7/30/18 11:14 PM.
 *
 * Copyright (c) 2018. All rights reserved.
 */

namespace App\Models;

use App\Models\City;
use App\Models\InfluencerCatetgory;
use App\Models\Supplier;
use App\Models\Influencer;
use App\Models\PostReportBuzzer;
use App\Models\Product;
use App\Models\ProductBrandType;
use App\Models\Sale;
use App\Models\UpdatedStockReport;
use App\Models\UserAddress;
use App\Models\UserLocationRanking;
use App\Models\UserPhone;
use App\Models\WaRotation;
use App\Models\Shipping;
use App\Models\PurchaseProductTransaction;
use App\Models\AgentPriority;
use App\Services\CompanyService;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Auth\Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Jobs\DeleteCacheRedisJob;

class User extends EloquentUser
{
    use HasApiTokens;
    use Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'permissions',
    ];

    protected $connection = 'mysql';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Array of login column names.
     *
     * @var array
     */
    protected $loginNames = ['email'];


}
