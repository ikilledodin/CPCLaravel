<?php

use Illuminate\Database\Seeder;
// use App\Company;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $count = Company::count();
        $count = DB::table('companies')->max('id');
        $cluster_mode = rand(0,1);
        $cluster_alias = '';
        $cluster_alias_arr = array('Division','Department','Region','Team');
        $cluster_key = array_rand($cluster_alias_arr);
        if($cluster_mode) {
        	$cluster_alias = $cluster_alias_arr[$cluster_key];
        }
        $group_alias_arr = array('Sector','Team','Sub-Team','Office');
        $tz_arr = array('Asia\Dubai','Asia/Kolkata','Asia/Qatar','Europe/Berlin','America/Chicago','America/Denver','America/Los_Angeles','America/New_York','America/Phoenix');
        $tz_key = array_rand($tz_arr);
        $group_key = array_rand($group_alias_arr);
        $group_alias = $group_alias_arr[$group_key];
        $program_startdate = Carbon::now()->toDateString();
        $program_enddate = Carbon::now()->addDays(rand(50,100))->toDateString();
        $shortname = sprintf('ACMETest%d',$count);
        $companyid = DB::table('companies')->insertGetId(
  			[
	        'name' => sprintf('ACME Test %d',$count),
	        'shortname' => $shortname,
	        'description' => sprintf('ACME Test Program %d',$count),
	        'description' => sprintf('ACME Test Program %d',$count),
	        'cluster_mode' => $cluster_mode,
	        'group_mode' => 1,
	        'cluster_alias' => $cluster_alias,
	        'group_alias' => $group_alias,
	        'tzname' =>	$tz_arr[$tz_key],
	        'program_startdate' => $program_startdate,
	        'program_enddate' => $program_enddate,
	        'license_type' => 0,
	        'created_at'       => new DateTime,
	        'updated_at'       => new DateTime,
	    	]
		);
		$this->create_company_code($companyid,$shortname);
		/*
        DB::table('companies')->insert([
	    [
	        'name' => sprintf('ACME Test %d',$count),
	        'shortname' => sprintf('ACMETest%d',$count),
	        'description' => sprintf('ACME Test Program %d',$count),
	        'description' => sprintf('ACME Test Program %d',$count),
	        'cluster_mode' => $cluster_mode,
	        'group_mode' => rand(0,1),
	        'cluster_alias' => $cluster_alias,
	        'group_alias' => $group_alias,
	        'tzname' =>	$tz_arr[$tz_key],
	        'program_startdate' => $program_startdate,
	        'program_enddate' => $program_enddate,
	        'license_type' => 0,
	        'created_at'       => new DateTime,
	        'updated_at'       => new DateTime,
	    ],
	    ]);
	    */
    }

    public function create_company_code($company_id,$company_shortname)
    {
    	$comp_email_domain = str_random(5).'.com';
    	DB::table('company_codes')->insert([
	    [
	    	'company_id' => $company_id,
	    	'company_code' => str_random(6),
	    	'phone' => rand(100,999).'-'.rand(1,99).'-'.rand(100000,999999),
	    	'email' => str_random(10).'@'.$comp_email_domain,
	    	'web_theme' =>rand(1,20),
	    	'app_theme_primary' =>rand(1,20),
	    	'app_theme_secondary' =>rand(1,20),
	    	'comp_logo' =>sprintf('companies/%s/comp_logo.png',$company_shortname),
	    	'app_program_logo' =>sprintf('companies/%s/web_program_logo.png',$company_shortname),
	    	'app_splash_screen' =>sprintf('companies/%s/app_splash_screen/',$company_shortname),
	    	'device_support' =>rand(1,127),
	    	'feature_enable' =>rand(1,63),
	    	'register_filter' =>rand(0,1),
	    	'email_filter' =>$comp_email_domain,
	    	'uname_enable' =>0,
	    	'datashow' =>rand(1,15),
	        'created_at'       => new DateTime,
	        'updated_at'       => new DateTime,
	    ],
	    ]);
    }
}
