<?php

return [

    /*
    |--------------------------------------------------------------------------
    | TupeloLife Constants 
    |--------------------------------------------------------------------------
    |
    |
    */

    'challenges' => [
        'CHALLENGE_OVERALL' => 1,
        'CHALLENGE_DAILY' => 2,
        'CHALLENGE_WEEKLY' => 3,
        'CHALLENGE_INTRADAY' => 4,
        'CHALLENGE_DURATION' => 5,
    ],

    'challengetype' => [
        'CHALLENGE_MAIN_STEPS' => 2500,     // main challenge that is presented in the leaderboard tab of walking challenge app
        'CHALLENGE_1' => 2001,      // individual steps whole company; if threshold is > 0 then leaderboard will only show user that reached the threshold
        'CHALLENGE_2' => 2002,      // city challenge
        'CHALLENGE_3' => 2003,      // gender challenge
        'CHALLENGE_4' => 2004,      // country challenge
        'CHALLENGE_5' => 2005,      // council challenge
        'CHALLENGE_6' => 2006,      // age group challenge
        'CHALLENGE_7' => 2007,
        'CHALLENGE_8' => 2008,      // individual average steps
        'CHALLENGE_9' => 2009,
        'CHALLENGE_10' => 2010,     // consistency based challenge
        'CHALLENGE_11' => 2011,
        'CHALLENGE_12' => 2012,     // count-up to 10 million
        'CHALLENGE_13' => 2013,     
        'CHALLENGE_14' => 2014,
        'CHALLENGE_15' => 2015,
        'CHALLENGE_GROUP_IND_TOPSTEPS' => 2016,         // top stepper within sub group (sector)
        'CHALLENGE_CLUSTER_IND_TOPSTEPS' => 2017,       // top stepper within main group (division)
        'CHALLENGE_GROUP_IND_TOPMINUTES' => 2018,       // top active minutes within sub group (sector) for mymo only
        'CHALLENGE_CLUSTER_IND_TOPMINUTES' => 2019,     // top active minutes within main group (division) for mymo 
        'CHALLENGE_CLUSTER_GRP_TOPSTEPS' => 2020,       // top sub group steps within the main group
        'CHALLENGE_COMPANY_GRP_TOPSTEPS' => 2021,       // top sub group steps in whole company
        'CHALLENGE_CLUSTER_GRP_TOPMINUTES' => 2022,     
        'CHALLENGE_COMPANY_GRP_TOPMINUTES' => 2023,
        'CHALLENGE_COMPANY_CLUSTER_TOPSTEPS' => 2024,       // top main group in whole company
        'CHALLENGE_CLUSTER_IND_TOPAVGSTEPS_NUMDAYS' => 2025,   // Individual Leaderboard w/in Division but based on consistency 
        'CHALLENGE_IND_TOPCALS' => 2026,
        'CHALLENGE_SUB_GROUP_TOPCALS' => 2027,
        'CHALLENGE_MAIN_GROUP_TOPCALS' => 2028,
        'CHALLENGE_IND_TOPDIST' => 2029,
        'CHALLENGE_SUB_GROUP_TOPDIST' => 2030,
        'CHALLENGE_MAIN_GROUP_TOPDIST' => 2031,
        'CHALLENGE_IND_TOPFLOORS' => 2032,
        'CHALLENGE_SUB_GROUP_TOPFLOORS' => 2033,
        'CHALLENGE_MAIN_GROUP_TOPFLOORS' => 2034,
    ],

    'apitype' => [
        'API_TYPE_FITBIT' => 1001,
        'API_TYPE_JAWBONE' => 1002,
        'API_TYPE_HEALTHKIT' => 1003,
        'API_TYPE_GOOGLEFIT' => 1004,
        'API_TYPE_GARMIN' => 1005,
        'API_TYPE_SHEALTH' => 1006
    ],

    'tokentype' => [
        'TOKEN_TYPE_FITBIT' => 'FITBIT',
        'TOKEN_TYPE_JAWBONE' => 'JAWBONE',
        'TOKEN_TYPE_GARMIN' => 'GARMIN'
    ],

];
