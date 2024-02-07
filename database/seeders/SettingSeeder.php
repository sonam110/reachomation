<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\Appsetting;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('appsettings')->delete();
        $appSetting = new Appsetting();
        $appSetting->app_name    = 'Reachomation';
        $appSetting->app_logo    = 'images/reachomation_logo_black.png';
        $appSetting->email       = 'support@reachomation.com';
        $appSetting->address     = 'Sector 135, Noida, Uttar Pradesh 201301';
        $appSetting->mobilenum   = '9871272058';
        $appSetting->tax_percentage   = '18.0';
        $appSetting->save();
    }
}
