<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Corpchallenge extends Model
{
    //
    protected $guarded = ['id'];
    
    public function company()
    {
    	return $this->belongsTo('App\Company');
    }

    public function winners()
    {
        // return $this->hasMany('App\UserGroup')->as('activeusers')->withTimestamps();
        return $this->hasMany('App\ChallengeWinner','challenge_id');
        // return $this->hasManyThrough('App\UserGroup', 'App\User');
    }

    public function scopeOfpastchallenges($query,$date)
    {
    	$today = Carbon::parse($date);
        $tomorrow = Carbon::parse($date)->tomorrow();
        $query = $query->whereDate('enddate','<=',$today->toDateString())
        						->where('isphone',1);
    }

    public function scopeOfactivechallenges($query,$date)
    {    
    
    	$today = Carbon::parse($date);
        $nextday = Carbon::parse($date)->tomorrow();
        /*
    	$first = DB::table('users')
            ->whereNull('first_name');

		$past = DB::Corpchallenge('users')
            ->whereNull('last_name')
            ->union($first)
            ->get();

            */
    	$query = $query->whereRaw('date(startdate) <= ? and date(enddate) >= ?',[$today->toDateString(),$nextday->toDateString()])
    		->where('isphone',1);

    }
     /**
     * Pull results for CHALLENGE TYPE 2001
     *
     */
    public function challenge1_results()
    {
        /*
        SELECT 
            SUM(numberofsteps) steps, a.userid, c.name username
        FROM
            hmm.d_pm a,
            drupal.users_groups b,
            drupal.users c
        WHERE
            a.userid = b.userid AND b.userid = c.uid
                AND b.orgid = $corpid
                AND datetimestamp >= '$startdate'
                AND datetimestamp <= '$enddate'
        GROUP BY b.userid
        ORDER BY steps DESC
        */

        // $limit = 100;
        /* extract rows from ChallengeWinner since this challenge is already processed 
            meaning there's an archived result for this challenge */
        if($this->isprocessed) {
            $query = $this->winners()->SELECT('user_id','name as fullname','steps as totalsteps','total','participants','photourl as avatar')->orderby('rank','desc');
        } else {
            $query = $this->company->allUsers()->join('d_pms','user_groups.user_id','d_pms.user_id')->join('user_profiles as t3','user_groups.user_id','t3.user_id')->selectRaw('user_groups.user_id as user_id,sum(d_pms.numberofsteps) as totalsteps,t3.avatar,CONCAT(t3.first_name,\' \',t3.last_name) as fullname')->whereBetween('d_pms.datetimestamp',[$this->startdate,$this->enddate])->groupby('user_id')->orderby('totalsteps','desc');

            /* check if threshold is more than 0 
                if yes then only return the rows that satisfy the threshold number
                */
            $query = $query->when($this->threshold > 0, function ($q) {
                // return $q->where('totalsteps', '>', $this->threshold);
                return $q->having('totalsteps', '>', $this->threshold);
            });

            /* if limit is greater than 0 then limit the result */
            $query = $query->when($this->challenge_limit > 0, function ($q) {
                // return $q->where('totalsteps', '>', $this->threshold);
                return $q->limit($this->challenge_limit);
            });

        }
        

        return $query;

    }
    /**
     * Pull results for CHALLENGE TYPE 2002
     *
     */
    public function challenge2_results()        
    {
        /*
        SELECT 
                            SUM(sumsteps) / COUNT(a.userid) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.userid) 'participants', city
                            FROM
                                (SELECT 
                                 a.userid, sum(numberofsteps) 'sumsteps', city
                                 FROM
                                 hmm.d_pm a, hmm.profile_extra b, drupal.users_groups c
                                 WHERE
                                 a.userid = b.userid
                                 AND a.userid = c.userid
                                 AND c.orgid = $corpid
                                 AND datetimestamp >= '$startdate'
                                 AND datetimestamp <= '$enddate'
                                 AND b.city <> ''
                                 AND b.city <> 'City'
                                 GROUP BY a.userid ORDER by sumsteps) a
                            GROUP BY city
                            ORDER BY avgsteps DESC
                            */
        if($this->isprocessed) {
            $query = $this->winners()->SELECT('user_id','name as city','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } else {
            $query = DB::table('user_profiles')
                      ->join(DB::raw("(SELECT 
                                             a.user_id, sum(numberofsteps) 'sumsteps'
                                             FROM
                                             d_pms a, user_groups c
                                             WHERE
                                             a.user_id = c.user_id
                                             AND c.company_id = ?
                                             AND datetimestamp >= ?
                                             AND datetimestamp <= ?
                                             GROUP BY a.user_id ORDER by sumsteps
                        )as table1"), function ($join) {
                            $join->on ( 'table1.user_id', '=', 'user_profiles.user_id' );
                        })
                    ->selectRaw("SUM(sumsteps) / COUNT(table1.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(table1.user_id) 'participants', city")
                    /*
                    ->where(function ($query1) {
                        $query1->whereNotNull('city')
                            ->orWhere('city', '<>', 'City');
                    })
                    */
                    ->whereNotNull('city')
                    ->groupby('city')
                    ->orderby('avgsteps','desc')
                    ->setbindings([$this->company_id,$this->startdate,$this->enddate]);

        }
        return $query;
    }
    /**
     * Pull results for CHALLENGE TYPE 2003
     *
     */
    public function challenge3_results()
    {
        /*
        $leaderboarddata =  $dbcon->doQuery ("SELECT 
            SUM(sumsteps) / COUNT(a.userid) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.userid) 'participants', gender
            FROM
                (SELECT 
                 a.userid, sum(numberofsteps) 'sumsteps', gender
                 FROM
                 hmm.d_pm a, hmm.profile_extra b, drupal.users_groups c
                 WHERE
                 a.userid = b.userid
                 AND a.userid = c.userid
                 AND c.orgid = $corpid
                 AND datetimestamp >= '$startdate'
                 AND datetimestamp <= '$enddate'
                 AND (b.gender = '0' OR b.gender = '1')
                 GROUP BY a.userid ORDER by sumsteps) a
            GROUP BY gender
            ORDER BY avgsteps DESC");
            */
        if($this->isprocessed) {
            $query = $this->winners()->SELECT('user_id','name as gender','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } else {
            $query = DB::table('user_profiles')
                      ->join(DB::raw("(SELECT 
                                             a.user_id, sum(numberofsteps) 'sumsteps'
                                             FROM
                                             d_pms a, user_groups c
                                             WHERE
                                             a.user_id = c.user_id
                                             AND c.company_id = ?
                                             AND datetimestamp >= ?
                                             AND datetimestamp <= ?
                                             GROUP BY a.user_id ORDER by sumsteps
                        )as table1"), function ($join) {
                            $join->on ( 'table1.user_id', '=', 'user_profiles.user_id' );
                        })
                    ->selectRaw("SUM(sumsteps) / COUNT(table1.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(table1.user_id) 'participants', 
                        CASE
                            WHEN gender = 0 THEN 'MALE'
                            ELSE 'FEMALE'
                        END as gender")
                    /*
                    ->where(function ($query1) {
                        $query1->whereNotNull('city')
                            ->orWhere('city', '<>', 'City');
                    })
                    */
                    ->whereIn('gender',[0,1])
                    ->groupby('gender')
                    ->orderby('avgsteps','desc')
                    ->setbindings([$this->company_id,$this->startdate,$this->enddate,[0,1]]);

        }
        return $query;
    }
    /**
     * Pull results for CHALLENGE TYPE 2004
     *
     */
    public function challenge4_results()
    {
        /*
        SELECT 
            SUM(sumsteps) / COUNT(a.userid) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.userid) 'participants', gender
            FROM
                (SELECT 
                 a.userid, sum(numberofsteps) 'sumsteps', gender
                 FROM
                 hmm.d_pm a, hmm.profile_extra b, drupal.users_groups c
                 WHERE
                 a.userid = b.userid
                 AND a.userid = c.userid
                 AND c.orgid = $corpid
                 AND datetimestamp >= '$startdate'
                 AND datetimestamp <= '$enddate'
                 AND (b.gender = '0' OR b.gender = '1')
                 GROUP BY a.userid ORDER by sumsteps) a
            GROUP BY gender
            ORDER BY avgsteps DESC
            */
        if($this->isprocessed) {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } else {
            $query = DB::table('user_profiles')
                      ->join(DB::raw("(SELECT 
                                             a.user_id, sum(numberofsteps) 'sumsteps'
                                             FROM
                                             d_pms a, user_groups c
                                             WHERE
                                             a.user_id = c.user_id
                                             AND c.company_id = ?
                                             AND datetimestamp >= ?
                                             AND datetimestamp <= ?
                                             GROUP BY a.user_id ORDER by sumsteps
                        )as table1"), function ($join) {
                            $join->on ( 'table1.user_id', '=', 'user_profiles.user_id' );
                        })
                    ->selectRaw("SUM(sumsteps) / COUNT(table1.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(table1.user_id) 'participants', country")
                    /*
                    ->where(function ($query1) {
                        $query1->whereNotNull('city')
                            ->orWhere('city', '<>', 'City');
                    })
                    */
                    ->whereNotNull('country')
                    ->groupby('country')
                    ->orderby('avgsteps','desc')
                    ->setbindings([$this->company_id,$this->startdate,$this->enddate]);

        }
        return $query;
    }
    /**
     * Pull results for CHALLENGE TYPE 2006
     *
     */
    public function challenge6_results()
    {
        /*
        SELECT
   SUM(numberofsteps)/COUNT(distinct(a.userid)) AS avgsteps, SUM(numberofsteps) 'totalsteps',COUNT(distinct(a.userid)) 'participants',
   agegroup
FROM
   (SELECT
       a.userid,
           numberofsteps,
           CASE
               WHEN TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 30 THEN '1'
               ELSE CASE
                   WHEN
                       TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 30
                           AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 40
                   THEN
                       '2'
                   ELSE CASE
                       WHEN
                           TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 40
                               AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 50
                       THEN
                           '3'
                       ELSE CASE
                           WHEN
                               TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 50
                                   AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 60
                           THEN
                               '4'
                           ELSE CASE
                               WHEN
                                   TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) > 60
                                       AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.dob,'%b-%d-%Y'), CURDATE()) <= 70
                               THEN
                                   '5'
                               ELSE '6'
                           END
                       END
                   END
               END
           END AS agegroup
   FROM
       hmm.d_pm a, hmm.profile_extra b, drupal.users_groups c
   WHERE
       a.userid = b.userid
           AND b.dob <> ''
           AND a.userid = c.userid
           AND c.orgid = $corpid
           AND datetimestamp >= '$startdate'
           AND datetimestamp <= '$enddate') a
GROUP BY agegroup
ORDER BY avgsteps DESC
            */
        if($this->isprocessed) {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } else {
            $query = DB::table('user_profiles')
                      ->join(DB::raw("(SELECT 
                                             a.user_id, sum(numberofsteps) 'sumsteps'
                                             FROM
                                             d_pms a, user_groups c
                                             WHERE
                                             a.user_id = c.user_id
                                             AND c.company_id = ?
                                             AND datetimestamp >= ?
                                             AND datetimestamp <= ?
                                             GROUP BY a.user_id ORDER by sumsteps
                        )as table1"), function ($join) {
                            $join->on ( 'table1.user_id', '=', 'user_profiles.user_id' );
                        })
                    ->selectRaw("SUM(sumsteps) / COUNT(table1.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(table1.user_id) 'participants', 
                            CASE
                               WHEN TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) <= 30 THEN '1'
                               ELSE CASE
                                   WHEN
                                       TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) > 30
                                           AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) <= 40
                                   THEN
                                       '2'
                                   ELSE CASE
                                       WHEN
                                           TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) > 40
                                               AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) <= 50
                                       THEN
                                           '3'
                                       ELSE CASE
                                           WHEN
                                               TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) > 50
                                                   AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) <= 60
                                           THEN
                                               '4'
                                           ELSE CASE
                                               WHEN
                                                   TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) > 60
                                                       AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(birthdate,'%b-%d-%Y'), CURDATE()) <= 70
                                               THEN
                                                   '5'
                                               ELSE '6'
                                           END
                                       END
                                   END
                               END
                           END AS agegroup")
                    /*
                    ->where(function ($query1) {
                        $query1->whereNotNull('city')
                            ->orWhere('city', '<>', 'City');
                    })
                    */
                    ->whereNotNull('birthdate')
                    ->groupby('agegroup')
                    ->orderby('avgsteps','desc')
                    ->setbindings([$this->company_id,$this->startdate,$this->enddate]);
                    // ->toSql();
        }
        return $query;
    }
    /**
     * Pull results for CHALLENGE TYPE 2008
     *
     */
    public function challenge8_results()
    {
        /*
        SELECT 
            username,
            userid,
            SUM(sumsteps) / COUNT(*) AS avgsteps,
            sum(sumsteps) 'totalsteps'
        FROM
            (SELECT 
                tbl1.userid, tbl3.name 'username',
                    SUM(numberofsteps) 'sumsteps',
                    DATE(datetimestamp) 'datestr'
            FROM
                hmm.d_pm tbl1, drupal.users_groups tbl2,drupal.users tbl3
            WHERE
                tbl1.userid = tbl2.userid AND tbl2.userid=tbl3.uid
                    AND tbl2.orgid = $corpid
                    AND datetimestamp >= '$startdate'
                    AND datetimestamp <= '$enddate'
            GROUP BY DATE(datetimestamp) , tbl1.userid) AS table1
        GROUP BY userid
        ORDER BY avgsteps DESC
            */
        if($this->isprocessed) {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } else {
            $query = DB::table('user_profiles')
                      ->join(DB::raw("(SELECT 
                                             a.user_id, sum(numberofsteps) 'sumsteps'
                                             FROM
                                             d_pms a, user_groups c
                                             WHERE
                                             a.user_id = c.user_id
                                             AND c.company_id = ?
                                             AND datetimestamp >= ?
                                             AND datetimestamp <= ?
                                             GROUP BY DATE(datetimestamp) , a.user_id ORDER by sumsteps
                        )as table1"), function ($join) {
                            $join->on ( 'table1.user_id', '=', 'user_profiles.user_id' );
                        })
                    ->selectRaw("table1.user_id as user_id,SUM(sumsteps) / COUNT(*) AS avgsteps,sum(sumsteps) as totalsteps,avatar,CONCAT(first_name,' ',last_name) as fullname,COUNT(*) numdayssync")
                    ->groupby('table1.user_id')
                    ->orderby('avgsteps','desc')
                    ->setbindings([$this->company_id,$this->startdate,$this->enddate]);
                    // ->toSql();
                    /* if threshold is > 0 then filter the result with it */
                    $query = $query->when($this->threshold > 0, function ($q) {
                        return $q->having('totalsteps', '>', $this->threshold);
                    });
        }
        return $query;
    }
    /**
     * Pull results for CHALLENGE TYPE 2010
     *
     */
    public function challenge10_results()
    {

        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        }
        else 
        {


/*
    $query =DB::select( DB::raw("
    
    SELECT finaltbl.*,totalsteps from (
        SELECT 
            username, table1.user_id, COUNT(*) 'numdays'
        from
            (SELECT 
                tbl1.user_id,
                    tbl3.name 'username',
                    SUM(numberofsteps) 'sumsteps',
                    DATE(datetimestamp) 'datestr'
            FROM
               d_pms tbl1, user_groups tbl2, users tbl3
            WHERE
                tbl1.user_id = tbl2.user_id
                    AND tbl2.user_id = tbl3.id
                    AND tbl2.company_id = $this->company_id
                    AND datetimestamp >= $this->startdate
                AND datetimestamp <= $this->enddate
            GROUP BY DATE(datetimestamp) , tbl1.user_id) AS table1
        where
            sumSteps > $this->threshold
        GROUP BY table1.user_id) as finaltbl 
            LEFT JOIN (SELECT 
                tbl1.user_id,
                    SUM(numberofsteps) 'totalsteps'
            FROM
                d_pms tbl1, user_groups tbl2, users tbl3
            WHERE
                tbl1.user_id = tbl2.user_id
                    AND tbl2.user_id = tbl3.id
                    AND tbl2.company_id = $this->company_id
                    AND datetimestamp >= $this->startdate
                AND datetimestamp <= $this->enddate
            GROUP BY tbl1.user_id order by totalsteps) AS table2 ON finaltbl.user_id=table2.user_id

        ORDER BY numdays DESC,totalsteps DESC
    "));

*/


    $query = DB::table(DB::raw('    
                (SELECT 
                tbl1.user_id,
                    tbl3.name "username",
                    SUM(numberofsteps) "sumsteps",
                    DATE(datetimestamp) "datestr"
                FROM
                d_pms tbl1, user_groups tbl2, users tbl3
                WHERE
                    tbl1.user_id = tbl2.user_id
                        AND tbl2.user_id = tbl3.id
                        AND tbl2.company_id = "2"
                        AND datetimestamp >= "2018-07-16 00:00:00"
                    AND datetimestamp <= "2018-07-18 00:00:00"
                GROUP BY DATE(datetimestamp) , tbl1.user_id) AS table1
                '))
                ->select(DB::raw('
                username, table1.user_id, COUNT(*) "numdays"
                '))
              ->toSql();
                return DB::table(DB::raw('(' . $query . ' where sumSteps > "200" GROUP BY table1.user_id) AS finaltbl '))    
                ->select(DB::raw('
                finaltbl.*,totalsteps
                '))
                ->leftJoin(DB::raw('                
                (SELECT 
                tbl1.user_id,
                    SUM(numberofsteps) "totalsteps"
                FROM
                    d_pms tbl1, user_groups tbl2, users tbl3
                WHERE
                    tbl1.user_id = tbl2.user_id
                        AND tbl2.user_id = tbl3.id
                        AND tbl2.company_id = "2"
                        AND datetimestamp >= "2018-07-16 00:00:00"
                    AND datetimestamp <= "2018-07-18 00:00:00"
                GROUP BY tbl1.user_id order by totalsteps) AS table2                
                '),
                'finaltbl.user_id','=','table2.user_id')
                ->orderby('numdays','desc')
                ->orderby('totalsteps','desc')
                ->get();
        }
        return $query;


     
        /*
        SELECT finaltbl.*,totalsteps from (
            SELECT 
                username, table1.userid, COUNT(*) 'numdays'
            from
                (SELECT 
                    tbl1.userid,
                        tbl3.name 'username',
                        SUM(numberofsteps) 'sumsteps',
                        DATE(datetimestamp) 'datestr'
                FROM
                    hmm.d_pm tbl1, drupal.users_groups tbl2, drupal.users tbl3
                WHERE
                    tbl1.userid = tbl2.userid
                        AND tbl2.userid = tbl3.uid
                        AND tbl2.orgid = $corpid
                        AND datetimestamp >= '$startdate'
                        AND datetimestamp <= '$enddate'
                GROUP BY DATE(datetimestamp) , tbl1.userid) AS table1
            where
                sumSteps > $threshold
            GROUP BY table1.userid) as finaltbl 
                LEFT JOIN (SELECT 
                    tbl1.userid,
                        SUM(numberofsteps) 'totalsteps'
                FROM
                    hmm.d_pm tbl1, drupal.users_groups tbl2, drupal.users tbl3
                WHERE
                    tbl1.userid = tbl2.userid
                        AND tbl2.userid = tbl3.uid
                        AND tbl2.orgid = $corpid
                        AND datetimestamp >= '$startdate'
                        AND datetimestamp <= '$enddate'
                GROUP BY tbl1.userid order by totalsteps) AS table2 ON finaltbl.userid=table2.userid

            ORDER BY numdays DESC,totalsteps DESC
            */
    }
     /**
     * Pull results for CHALLENGE TYPE 2012
     *
     */
	 
	  
    public function challenge12_results()
    {
		if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        }
        else 
        {
            $query = DB::table('d_pms')->select('numberofsteps')
                       ->join('user_groups', 'd_pms.user_id', '=', 'user_groups.user_id')
                       //->where('user_groups.company_id', '=', $this->company_id )
                      // ->whereBetween('d_pms.datetimestamp', [$this->startdate, $this->enddate])               
                     
                      ->where('user_groups.company_id', '=', '2' )
                      ->whereBetween('d_pms.datetimestamp', ['2018-07-16 00:00:00','2018-07-18 00:00:00'])               
                      
                      ->get();
        }
        return $query;
		
		
		
        /*
            SELECT 
        SUM(numberofsteps) 'totalsteps'
    FROM 
        hmm.d_pm t1,
        drupal.users_groups t2
    WHERE
        t1.userid = t2.userid AND t2.orgid = $corpid
            AND t1.datetimestamp >= '$startdate'
            AND t1.datetimestamp <= '$enddate'
            */
    }
    /**
     * Pull results for CHALLENGE TYPE 2016 & 2017
     *
     */
    public function challenge16and17_results($clustermode = FALSE)
    {




        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {    
            if ($clustermode="2016")
            {            
                $query = DB::table(DB::raw('
                (SELECT 
                    t1.user_id, SUM(t2.numberofsteps) "sumSteps"
                FROM
                    user_groups t1,
                    d_pms t2
                WHERE
                    t1.user_id = t2.user_id AND t1.company_id = "2"
                        AND t1.group_id = "0"
                        AND t2.datetimestamp >= "2018-07-16 00:00:00"
                        AND t2.datetimestamp <= "2018-07-18 00:00:00"
                GROUP BY t1.user_id) tbl_a             
                '))
                ->select(DB::raw('
                tbl_b.id "user_id",tbl_b.name "username",tbl_a.sumSteps "steps"
                '))
                ->join('users AS tbl_b','tbl_a.user_id','=','tbl_b.id')
                ->orderby('steps','desc')
                ->get();
            }
            else if($clustermode="2017")
            {
                $query = DB::table(DB::raw('
                (SELECT 
                    t1.user_id, SUM(t2.numberofsteps) "sumSteps"
                FROM
                    user_groups t1,
                    d_pms t2
                WHERE
                    t1.user_id = t2.user_id AND t1.company_id = "2"
                        AND t1.cluster_id = "0"
                        AND t2.datetimestamp >= "2018-07-16 00:00:00"
                        AND t2.datetimestamp <= "2018-07-18 00:00:00"
                GROUP BY t1.user_id) tbl_a             
                '))
                ->select(DB::raw('
                tbl_b.id "user_id",tbl_b.name "username",tbl_a.sumSteps "steps"
                '))
                ->join('users AS tbl_b','tbl_a.user_id','=','tbl_b.id')
                ->orderby('steps','desc')
                ->get();

            }

            
            
        }
        return $query;
















        /*
        if !$clustermode (2016)
            SELECT tbl_b.uid 'userid',tbl_b.name 'username',tbl_a.sumSteps 'steps' from (SELECT 
                t1.userid, SUM(t2.numberofsteps) 'sumSteps'
            FROM
                drupal.users_groups t1,
                hmm.d_pm t2
            WHERE
                t1.userid = t2.userid AND t1.orgid = $orgid
                    AND t1.groupid = $groupid
                    AND t2.datetimestamp >= '$startdate'
                    AND t2.datetimestamp <='$enddate'
            GROUP BY t1.userid) tbl_a INNER JOIN drupal.users tbl_b ON tbl_a.userid=tbl_b.uid
            ORDER BY steps DESC
        else if $clustermode (2017)
        SELECT tbl_b.uid 'userid',tbl_b.name 'username',tbl_a.sumSteps 'steps' from (SELECT 
                t1.userid, SUM(t2.numberofsteps) 'sumSteps'
            FROM
                drupal.users_groups t1,
                hmm.d_pm t2
            WHERE
                t1.userid = t2.userid AND t1.orgid = $orgid
                    AND t1.clusterid = $groupid
                    AND t2.datetimestamp >= '$startdate'
                    AND t2.datetimestamp <= '$enddate'
            GROUP BY t1.userid) tbl_a INNER JOIN drupal.users tbl_b ON tbl_a.userid=tbl_b.uid
            ORDER BY steps DESC
            */
    }
    /**
     * Pull results for CHALLENGE TYPE 2018 and 2019
     *
     */
    public function challenge18and19_results()
    {

        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {   

            $query = DB::table(DB::raw('    
            (SELECT 
                t1.user_id, COUNT(*) "activeminutes"
            FROM
                user_groups t1, d_pms t2
            WHERE
                t1.user_id = t2.user_id AND t1.company_id = "2"
                    AND t1.group_id = "1"
                    AND t2.datetimestamp >= "2018-07-16 00:00:00"
                    AND t2.datetimestamp <= "2018-07-18 00:00:00"
                    AND numberofsteps >= 120
            GROUP BY t1.user_id) tbl_a
            '))
            ->leftjoin('users AS tbl_b','tbl_a.user_id','=','tbl_b.id')
            ->leftjoin('user_devices AS tbl_c','tbl_b.id','=','tbl_c.user_id')
            ->where('tbl_c.serial','<>','666974626974')
            ->where('tbl_c.serial','<>','6a6177626f6e65')
            ->where('tbl_c.serial','<>','7065646f6d657465720d0a')
            ->orderby('steps','desc')         
            ->select(DB::raw('
            tbl_b.id "userid",
            tbl_b.name "username",
            tbl_a.activeMinutes "steps"
            '))
            ->distinct()
             
            ->get();



        }
        return $query;
        /*
        if !$clustermode (2018)
            SELECT DISTINCT
            (tbl_b.uid) 'userid',
            tbl_b.name 'username',
            tbl_a.activeMinutes 'steps'
        FROM
            (SELECT 
                t1.userid, COUNT(*) 'activeminutes'
            FROM
                drupal.users_groups t1, hmm.d_pm t2
            WHERE
                t1.userid = t2.userid AND t1.orgid = $orgid
                    AND t1.groupid = $groupid
                    AND t2.datetimestamp >= '$startdate'
                    AND t2.datetimestamp <= '$enddate'
                    AND numberofsteps >= 120
            GROUP BY t1.userid) tbl_a
                LEFT JOIN
            drupal.users tbl_b ON tbl_a.userid = tbl_b.uid
                LEFT JOIN
            hmm.devices tbl_c ON tbl_b.uid = tbl_c.userid
        WHERE
            tbl_c.serial <> '666974626974'
                AND tbl_c.serial <> '6a6177626f6e65'
                AND tbl_c.serial <> '7065646f6d657465720d0a'
        ORDER BY steps DESC
        else if $clustermode (2019)
            SELECT DISTINCT
            (tbl_b.uid) 'userid',
            tbl_b.name 'username',
            tbl_a.activeMinutes 'steps'
        FROM
            (SELECT 
                t1.userid, COUNT(*) 'activeminutes'
            FROM
                drupal.users_groups t1, hmm.d_pm t2
            WHERE
                t1.userid = t2.userid AND t1.orgid = $orgid
                    AND t1.clusterid = $groupid
                    AND t2.datetimestamp >= '$startdate'
                    AND t2.datetimestamp <= '$enddate'
                    AND numberofsteps >= 120
            GROUP BY t1.userid) tbl_a
                LEFT JOIN
            drupal.users tbl_b ON tbl_a.userid = tbl_b.uid
                LEFT JOIN
            hmm.devices tbl_c ON tbl_b.uid = tbl_c.userid
        WHERE
            tbl_c.serial <> '666974626974'
                AND tbl_c.serial <> '6a6177626f6e65'
                AND tbl_c.serial <> '7065646f6d657465720d0a'
        ORDER BY steps DESC
            */
    }

    /**
     * Pull results for CHALLENGE TYPE 2020
     *
     */
    public function challenge20_results()
    {



        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {         
                $query = DB::table(DB::raw('
                (SELECT 
                t1.user_id,
                    t1.group_id,
                    t1.company_id,
                    SUM(t2.numberofsteps) "sumSteps"
                FROM
                    user_groups t1, d_pms t2
                WHERE
                    t1.user_id = t2.user_id AND t1.company_id = "2"
                        AND t1.cluster_id = "0"
                        AND t2.datetimestamp >= "2018-07-16 00:00:00"
                        AND t2.datetimestamp <= "2018-07-18 00:00:00"
                GROUP BY t1.user_id,t1.group_id,
                        t1.company_id) AS overall
                '))
                ->select(DB::raw('
                AVG(sumsteps) "average",
                SUM(sumSteps) "totalsteps",
                COUNT(*) "participants",
                company_id,
                group_id
                '))
                ->toSql();
                return DB::table(DB::raw('(' . $query . ' GROUP BY group_id) tbl_a'))           
                ->select(DB::raw(' tbl_a.*, tbl_b.name,tbl_b.photourl'))
                ->join('company_group_names as tbl_b','tbl_a.company_id','=','tbl_b.company_id')
                ->orderby('average','desc')
                ->get();        
        }
        return $query;
        /*
       SELECT 
    tbl_a.*, tbl_b.name,tbl_b.photourl
FROM
    (SELECT 
        AVG(sumsteps) 'average',
            SUM(sumSteps) 'totalsteps',
            COUNT(*) 'participants',
            orgid,
            groupid
    FROM
        (SELECT 
        t1.userid,
            t1.groupid,
            t1.orgid,
            SUM(t2.numberofsteps) 'sumSteps'
    FROM
        drupal.users_groups t1, hmm.d_pm t2
    WHERE
        t1.userid = t2.userid AND t1.orgid = $orgid
            AND t1.clusterid = $clusterid
            AND t2.datetimestamp >= '$startdate'
            AND t2.datetimestamp <= '$enddate'
    GROUP BY t1.userid) AS overall
    GROUP BY groupid) tbl_a
        INNER JOIN
    drupal.users_group_name tbl_b ON tbl_a.orgid = tbl_b.orgid
        AND tbl_a.groupid = tbl_b.groupid ORDER BY average DESC
            */
    }
    /**
     * Pull results for CHALLENGE TYPE 2021
     *
     */
    public function challenge21_results()
    {


        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {           
            /*                                                                        
$query = DB::select( DB::raw("
SELECT 
tbl_a.*, tbl_b.name,tbl_b.photourl
FROM
(SELECT 
    AVG(sumsteps) 'average',
        SUM(sumSteps) 'totalsteps',
        COUNT(*) 'participants',
        company_id,
        group_id
FROM
    (SELECT 
    t1.user_id,
        t1.group_id,
        t1.company_id,
        SUM(t2.numberofsteps) 'sumSteps'
FROM
    user_groups t1, d_pms t2
WHERE
    t1.user_id = t2.user_id 
        AND t1.company_id = '2'
        AND t2.datetimestamp >= '2018-07-16 00:00:00'
        AND t2.datetimestamp <= '2018-07-18 00:00:00'
GROUP BY t1.user_id) AS overall
GROUP BY group_id) tbl_a
    INNER JOIN
    company_group_names tbl_b ON tbl_a.company_id = tbl_b.company_id
   
     ORDER BY average desc

"));

       */
        $query = DB::table(DB::raw('
            (SELECT 
            t1.user_id,
                t1.group_id,
                t1.company_id,
                SUM(t2.numberofsteps) "sumSteps"
        FROM
            user_groups t1, d_pms t2
        WHERE
            t1.user_id = t2.user_id 
                AND t1.company_id = "2"
                AND t2.datetimestamp >= "2018-07-16 00:00:00"
                AND t2.datetimestamp <= "2018-07-18 00:00:00"
        GROUP BY t1.user_id,t1.group_id) AS overall
        '))
        ->select(DB::raw('
            AVG(sumsteps) "average",
            SUM(sumSteps) "totalsteps",
            COUNT(*) "participants",
            company_id,
            group_id
        '))
        ->toSql();
        return DB::table(DB::raw('(' . $query . ' GROUP BY group_id) tbl_a'))           
        ->select(DB::raw('tbl_a.*, tbl_b.name,tbl_b.photourl'))
        ->join('company_group_names as tbl_b','tbl_a.company_id','=','tbl_b.company_id')
        ->orderby('average','desc')
        ->get();
        }
        return $query;









        /*
       SELECT 
    tbl_a.*, tbl_b.name,tbl_b.photourl
FROM
    (SELECT 
        AVG(sumsteps) 'average',
            SUM(sumSteps) 'totalsteps',
            COUNT(*) 'participants',
            orgid,
            groupid
    FROM
        (SELECT 
        t1.userid,
            t1.groupid,
            t1.orgid,
            SUM(t2.numberofsteps) 'sumSteps'
    FROM
        drupal.users_groups t1, hmm.d_pm t2
    WHERE
        t1.userid = t2.userid 
            AND t1.orgid = $orgid
            AND t2.datetimestamp >= '$startdate'
            AND t2.datetimestamp <= '$enddate'
    GROUP BY t1.userid) AS overall
    GROUP BY groupid) tbl_a
        INNER JOIN
    drupal.users_group_name tbl_b ON tbl_a.orgid = tbl_b.orgid
        AND tbl_a.groupid = tbl_b.groupid ORDER BY average desc
            */
    }
    /**
     * Pull results for CHALLENGE TYPE 2022
     *
     */
    public function challenge22_results()
    {


        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {   
        $query = DB::table(DB::raw('
                (SELECT 
                t1.user_id, t1.group_id,company_id, COUNT(*) "activeminutes"
                    FROM
                        user_groups t1, d_pms t2
                    WHERE
                        t1.user_id = t2.user_id 
                            AND t1.company_id = "2"
                            AND t1.cluster_id = "0"
                            AND t2.datetimestamp >= "2018-07-16 00:00:00"
                            AND t2.datetimestamp <= "2018-07-18 00:00:00"
                            AND numberofsteps >= 120
                    GROUP BY t1.user_id, t1.group_id) AS overall
                '))
                ->select(DB::raw('
                    AVG(activeminutes) "average",
                    SUM(activeminutes) "totalsteps",
                    COUNT(*) "participants",
                    company_id,
                    group_id
                '))->toSql();
                return DB::table(DB::raw('(' . $query . ' GROUP BY group_id) tbl_a'))           
                ->select(DB::raw('
                    tbl_a.*, tbl_b.name,tbl_b.photourl
                '))
                ->join('company_group_names as tbl_b','tbl_a.company_id','=','tbl_b.company_id')
                ->orderby('average','desc')
                ->get();
        }
        return $query;
        /*
       SELECT 
    tbl_a.*, tbl_b.name,tbl_b.photourl
FROM
    (SELECT 
        AVG(activeminutes) 'average',
            SUM(activeminutes) 'totalsteps',
            COUNT(*) 'participants',
            orgid,
            groupid
    FROM
        (SELECT 
        t1.userid, t1.groupid,orgid, COUNT(*) 'activeminutes'
    FROM
        drupal.users_groups t1, hmm.d_pm t2
    WHERE
        t1.userid = t2.userid AND t1.orgid = $orgid
            AND t1.clusterid = $clusterid
            AND t2.datetimestamp >= '$startdate'
            AND t2.datetimestamp <= '$enddate'
            AND numberofsteps >= 120
    GROUP BY t1.userid) AS overall
    GROUP BY groupid) tbl_a
        INNER JOIN
    drupal.users_group_name tbl_b ON tbl_a.orgid = tbl_b.orgid
        AND tbl_a.groupid = tbl_b.groupid ORDER BY average DESC
            */
    }

    /**
     * Pull results for CHALLENGE TYPE 2023
     *
     */
    public function challenge23_results()
    {

        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {   
/*
            $query = DB::select( DB::raw("        
            SELECT 
                tbl_a.*, tbl_b.name,tbl_b.photourl
            FROM
                (SELECT 
                    AVG(activeminutes) 'average',
                        SUM(activeminutes) 'totalsteps',
                        COUNT(*) 'participants',
                        company_id,
                        group_id
                FROM
                    (SELECT 
                    t1.user_id, t1.group_id,company_id, COUNT(*) 'activeminutes'
                FROM
                    user_groups t1, d_pms t2
                WHERE
                    t1.user_id = t2.user_id 
                        AND t1.company_id = '2'
                        AND t2.datetimestamp >= '2018-07-16 00:00:00'
                        AND t2.datetimestamp <= '2018-07-18 00:00:00'
                        AND numberofsteps >= 120
                GROUP BY t1.user_id) AS overall
                GROUP BY group_id) tbl_a
                    INNER JOIN
                    company_group_names tbl_b ON tbl_a.company_id = tbl_b.company_id
                    ORDER BY average DESC
            "));

*/
            $query = DB::table(DB::raw('
            (SELECT 
            t1.user_id, t1.group_id,company_id, COUNT(*) "activeminutes"
            FROM
                user_groups t1, d_pms t2
            WHERE
                t1.user_id = t2.user_id 
                    AND t1.company_id = "2"
                    AND t2.datetimestamp >= "2018-07-16 00:00:00"
                    AND t2.datetimestamp <= "2018-07-18 00:00:00"
                    AND numberofsteps >= 120
            GROUP BY t1.user_id) AS overall
            '))
            ->select(DB::raw('
                AVG(activeminutes) "average",
                SUM(activeminutes) "totalsteps",
                COUNT(*) "participants",
                company_id,
                group_id
            '))->toSql();
            return DB::table(DB::raw('(' . $query . ' GROUP BY group_id) tbl_a'))           
            ->select(DB::raw('
                tbl_a.*, tbl_b.name,tbl_b.photourl
            '))
            ->join('company_group_names as tbl_b','tbl_a.company_id','=','tbl_b.company_id')
            ->orderby('average','desc')
            ->get();





        }
        return $query;

        /*
      SELECT 
    tbl_a.*, tbl_b.name,tbl_b.photourl
FROM
    (SELECT 
        AVG(activeminutes) 'average',
            SUM(activeminutes) 'totalsteps',
            COUNT(*) 'participants',
            orgid,
            groupid
    FROM
        (SELECT 
        t1.userid, t1.groupid,orgid, COUNT(*) 'activeminutes'
    FROM
        drupal.users_groups t1, hmm.d_pm t2
    WHERE
        t1.userid = t2.userid 
            AND t1.orgid=$orgid
            AND t2.datetimestamp >= '$startdate'
            AND t2.datetimestamp <= '$enddate'
            AND numberofsteps >= 120
    GROUP BY t1.userid) AS overall
    GROUP BY groupid) tbl_a
        INNER JOIN
    drupal.users_group_name tbl_b ON tbl_a.orgid = tbl_b.orgid
        AND tbl_a.groupid = tbl_b.groupid ORDER BY average DESC
            */
    }

    /**
     * Pull results for CHALLENGE TYPE 2024
     *
     */
    public function challenge24_results()
    {


        if($this->isprocessed) 
        {
           // $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {   
/*
            $query = DB::select( DB::raw("        
            SELECT 
            tbl_a.*, tbl_b.name
            FROM
                (SELECT 
                    AVG(sumsteps) 'average',
                        SUM(sumSteps) 'totalsteps',
                        COUNT(*) 'participants',
                        company_id,
                        cluster_id
                FROM
                    (SELECT 
                    t1.user_id,
                        t1.group_id,
                        t1.cluster_id,
                        t1.company_id,
                        SUM(t2.numberofsteps) 'sumSteps'
                FROM
                    user_groups t1, d_pms t2
                WHERE
                    t1.user_id = t2.user_id 
                        AND t1.company_id = '2'
                        AND t2.datetimestamp >= '2018-07-16 00:00:00'
                        AND t2.datetimestamp <= '2018-07-18 00:00:00'
                GROUP BY t1.user_id, t1.group_id,
                        t1.cluster_id) AS overall
                GROUP BY cluster_id) tbl_a
                    INNER JOIN
                    company_cluster_names tbl_b 
                    ON tbl_a.company_id = tbl_b.company_id
                    AND tbl_a.cluster_id = tbl_b.clusterid 
                    ORDER BY average desc
                "));
*/


                $query = DB::table(DB::raw('
                (SELECT 
                    t1.user_id,
                        t1.group_id,
                        t1.cluster_id,
                        t1.company_id,
                        SUM(t2.numberofsteps) "sumSteps"
                FROM
                    user_groups t1, d_pms t2
                WHERE
                    t1.user_id = t2.user_id 
                        AND t1.company_id = "2"
                        AND t2.datetimestamp >= "2018-07-16 00:00:00"
                        AND t2.datetimestamp <= "2018-07-18 00:00:00"
                GROUP BY t1.user_id, t1.group_id,
                        t1.cluster_id) AS overall
                '))
                ->select(DB::raw('
                        AVG(sumsteps) "average",
                        SUM(sumSteps) "totalsteps",
                        COUNT(*) "participants",
                        company_id,
                        cluster_id
                '))->toSql();
                return DB::table(DB::raw('(' . $query . ' GROUP BY cluster_id) tbl_a'))           
                ->select(DB::raw('
                tbl_a.*, tbl_b.name
                '))
                ->join('company_cluster_names as tbl_b',function($join){
                    $join->on("tbl_a.company_id","=","tbl_b.company_id")
                        ->on("tbl_a.cluster_id","=","tbl_b.clusterid");
                })
                ->orderby('average','desc')
                ->get();

         



        }
        return $query;







        /*
      SELECT 
    tbl_a.*, tbl_b.name
FROM
    (SELECT 
        AVG(sumsteps) 'average',
            SUM(sumSteps) 'totalsteps',
            COUNT(*) 'participants',
            orgid,
            clusterid
    FROM
        (SELECT 
        t1.userid,
            t1.groupid,
            t1.clusterid,
            t1.orgid,
            SUM(t2.numberofsteps) 'sumSteps'
    FROM
        drupal.users_groups t1, hmm.d_pm t2
    WHERE
        t1.userid = t2.userid AND t1.orgid = $orgid
            AND t2.datetimestamp >= '$startdate'
            AND t2.datetimestamp <= '$enddate'
    GROUP BY t1.userid) AS overall
    GROUP BY clusterid) tbl_a
        INNER JOIN
    drupal.users_cluster_name tbl_b ON tbl_a.orgid = tbl_b.orgid
        AND tbl_a.clusterid = tbl_b.clusterid ORDER BY average desc
            */
    }
    /**
     * Pull results for CHALLENGE TYPE 2025
     *
     */
    public function challenge25_results()
    {

        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {   
/*
            $query = DB::select( DB::raw("        
            SELECT 
            username,
            user_id,
            COUNT(*) 'steps'
        from
            (SELECT 
                tbl1.user_id, tbl3.name 'username',
                    SUM(numberofsteps) 'sumsteps',
                    DATE(datetimestamp) 'datestr'
            FROM
                d_pms tbl1, user_groups tbl2,users tbl3
            WHERE
                tbl1.user_id = tbl2.user_id 
                    AND tbl2.user_id=tbl3.id
                    AND tbl2.company_id = $orgid
                    AND tbl2.cluster_id = $groupid
                    AND datetimestamp >= '$startdate'
                    AND datetimestamp <= '$enddate'
            GROUP BY DATE(datetimestamp) , tbl1.user_id) AS table1
        where
            sumSteps > '200'
        GROUP BY user_id
        ORDER BY steps DESC
            "));
*/
            $query = DB::table(DB::raw('
            (SELECT 
                tbl1.user_id, tbl3.name "username",
                    SUM(numberofsteps) "sumsteps",
                    DATE(datetimestamp) "datestr"
                FROM
                d_pms tbl1, user_groups tbl2,users tbl3
                WHERE
                tbl1.user_id = tbl2.user_id 
                    AND tbl2.user_id=tbl3.id
                    AND tbl2.company_id = "2"
                    AND tbl2.cluster_id = "0"
                    AND datetimestamp >= "2018-07-16 00:00:00"
                    AND datetimestamp <= "2018-07-18 00:00:00"
                GROUP BY DATE(datetimestamp) , tbl1.user_id) AS table1
            '))
            ->select(DB::raw('
            username,
            user_id,
            COUNT(*) "steps"
            '))
            ->where('sumSteps','>','200')
            ->groupby('user_id')
            ->orderby('steps','DESC')
            
            
            ->get();
        }
        return $query;



        /*
      SELECT 
                username,
                userid,
                COUNT(*) 'steps'
            from
                (SELECT 
                    tbl1.userid, tbl3.name 'username',
                        SUM(numberofsteps) 'sumsteps',
                        DATE(datetimestamp) 'datestr'
                FROM
                    hmm.d_pm tbl1, drupal.users_groups tbl2,drupal.users tbl3
                WHERE
                    tbl1.userid = tbl2.userid and tbl2.userid=tbl3.uid
                        AND tbl2.orgid = $orgid
                        AND tbl2.clusterid = $groupid
                        AND datetimestamp >= '$startdate'
                        AND datetimestamp <= '$enddate'
                GROUP BY DATE(datetimestamp) , tbl1.userid) AS table1
            where
                sumSteps > $threshold
            GROUP BY userid
            ORDER BY steps DESC
            */
    }
    /**
     * Pull results for CHALLENGE TYPE 2026
     *
     */
    public function challenge26_results()
    {
        if($this->isprocessed) 
        {
            $query = $this->winners()->SELECT('user_id','name as country','steps as avgsteps','total as totalsteps','participants','photourl as avatar')->orderby('rank','desc');
        } 
        else 
        {   

            $query = DB::select( DB::raw("        
           
            "));
        }
        return $query;
    }

    /**
     * Pull results for CHALLENGE TYPE 2027
     *
     */
    public function challenge27_results()
    {
        /*
      
            */
    }

     /**
     * Pull results for CHALLENGE TYPE 2028
     *
     */
    public function challenge28_results()
    {
        /*
      
            */
    }

     /**
     * Pull results for CHALLENGE TYPE 2029
     *
     */
    public function challenge29_results()
    {
        /*
      
            */
    }

    /**
     * Pull results for CHALLENGE TYPE 2030
     *
     */
    public function challenge30_results()
    {
        /*
      
            */
    }

    /**
     * Pull results for CHALLENGE TYPE 2031
     *
     */
    public function challenge31_results()
    {
        /*
      
            */
    }
    /**
     * Pull results for CHALLENGE TYPE 2032
     *
     */
    public function challenge32_results()
    {
        /*
      
            */
    }

    /**
     * Pull results for CHALLENGE TYPE 2033
     *
     */
    public function challenge33_results()
    {
        /*
      
            */
    }

    /**
     * Pull results for CHALLENGE TYPE 2034
     *
     */
    public function challenge34_results()
    {
        /*
      
         */
    }






/*

NIKKO


*/


public function getChallengeIDdata($userid, $corpid, $date)
{

    $origdate = strtotime($date);
    $startdate = date('Y-m-d',$origdate);
    $endOfDay = strtotime("tomorrow", $origdate);
    $ld = date('Y-m-d', $endOfDay);
    $idarray = array();
    $messagecount = 0;

    $challengeData = DB::select(DB::Raw("       
    SELECT 
    *
    FROM
        (SELECT 
            *
        FROM
            corpchallenges
        WHERE
            company_id = '2'
                AND DATE(startdate) <= '2018-08-01'
                AND DATE(enddate) >= '2018-08-06') AS ActiveChallenges 
    UNION SELECT 
        *
    FROM
        (SELECT 
            *
        FROM
            corpchallenges
        WHERE
            company_id = '2'
                AND DATE(enddate) <= '2018-08-01') AS PastChallenge
    ORDER BY weight DESC 
    "));

    
    foreach($challengeData as $data)
    {
        $idarray[] = json_encode($data->id);
    }


    foreach($idarray as $data)
    {
// SELECT * from corpchallenge where id='.$value.' and company_id='.$corpid.'
        $challenge_startdate = '';
		$challenge_enddate = '';
		$challenge_unit = 0;
        $threshold = 0;
        $challengeData = DB::select(DB::Raw("SELECT * from corpchallenges where id=1 and company_id=2"));
        
        foreach($challengeData as $data)
        {
           
            $challenge_startdate = $data->startdate;
            $challenge_enddate = $data->enddate;
            $challenge_unit = $data->challenge_unit;
            $challenge_url = $data->challenge_imageurl;
            $isprocessed = $data->isprocessed;
            $challengeid = $data->id;
            $threshold = $data->threshold;
        }



        
    

    $message = '';
    $urlpath = '';
    $title = '';
    $steps = 0;
    $nowtime = time();
	$isprocessed = 0;


    if(!empty($challenge_startdate) && !empty($challenge_enddate)) 
    {
        $title = $data->challenge_header;
        $challengeendtime = strtotime($challenge_enddate);
        $isEnd = $challengeendtime <= $nowtime ? 1:0;
        $expiration7days = strtotime('+7 day', $challengeendtime);
       // if($isEnd && $nowtime > $expiration7days) 
        //        continue;
                
      

        switch($challenge_unit)
        {
            case 2001:
            break;

            case 2002:
                    $uid = '';
                    if($isprocessed) 
                    {
                        //$leaderdata = $dbcon->doQuery("SELECT * from challenge_winners where challenge_id=$challengeid");                   
                        $leaderdata = DB::select(DB::raw("SELECT * from challenge_winners where challenge_id=$challengeid"));
                        
                        if($leaderdata) 
                        {
                            $name = $leaderdata['name'];
                            $avgsteps = $leaderdata['steps'];
                            $avgsteps = number_format($avgsteps);
                        }
                    } 
                    else 
                    {
                        $leaderdata = Corpchallenge::challenge_challenge2_query($corpid,$challenge_startdate,$challenge_enddate,TRUE);
                        if($leaderdata)
                        {
                            //$leaderdata = json_encode($leaderdata);
                            foreach($leaderdata as $data)
                            {
                                $name = $data->city;
                                $avgsteps = $data->avgsteps;
                                $avgsteps = number_format($avgsteps);
                                $total = $data->totalsteps;	
                            }
                            
                        }
                    }
                    if(!empty($name)) 
                    {
                        if($isEnd) 
                        {
                            $message = 'The city of ' .$name. ' has won this challenge with '.$avgsteps.' average steps!';
                        } else {
                            $message = 'The city of ' .$name. ' is currently leading this challenge with '.$avgsteps.' average steps!';
                        }
                    }
            break;

            case 2003;
                if($isprocessed) {
                  //  $leaderdata = $dbcon->doQuery("SELECT * from hmm.challenge_winners where challengeid=$challengeid");
                    $leaderdata = DB::select(DB::raw("SELECT * from hmm.challenge_winners where challengeid=$challengeid"));
                    if($leaderdata) {
                        $name = $$leaderdata['name'];
                        $steps = $$leaderdata['steps'];
                        $steps = number_format($$leaderdata);
                    }
                    
                } else {
                    $leaderdata = Corpchallenge::challenge_challenge3_query($corpid,$challenge_startdate,$challenge_enddate,TRUE);
                    if($leaderdata) {

                        foreach($leaderdata as $data)
                        {
                            if($data->gender == 0) {
                                $gender = "Male";
                            } else if($data->gender == 1) {
                                $gender = "Female";
                            }
                            $name = $gender;
                            $avgsteps = $data->avgsteps;
                            $steps = number_format($avgsteps);
                        }
                        }
                }
                if($isprocessed) {
                    $message = $name. ' has won this challenge with '.$steps.' average steps!';
                } else {
                    $message = $name. ' is currently leading this challenge with '.$steps.' average steps!';
                }
            break;



            case 2004;
                $leaderdata = Corpchallenge::challenge_challenge4_query($corpid,$challenge_startdate,$challenge_enddate,TRUE);
                if($leaderdata) 
                {
                    foreach($leaderdata as $data)
                    {
                        $uid = '';
                        $name = $data->country;
                        $avgsteps = $data->avgsteps;
                        $avgsteps = number_format($avgsteps);
                        $total = $data->totalsteps;
                        if($isEnd) {
                            $message = 'The country of ' .$name. ' has won this challenge with '.$avgsteps.' average steps!';
                        } else {
                            $message = 'The country of ' .$name. ' is currently leading this challenge with '.$avgsteps.' average steps!';
                        }
                    }
                }
            break;



            case 2005;#COUNCIL
					$leaderdata = challenge_challenge5_query($corpid,$challenge_startdate,$challenge_enddate,TRUE);
					if($leaderdata) {
                        foreach($leaderdata as $data)
                        {
                            $uid = '';
                            $name = hmm_challenge_service_getCouncilname($leaderdata->council_type);
                            $avgsteps = $leaderdata->avgsteps;
                            $avgsteps = number_format($avgsteps);
                            $total = $leaderdata->totalsteps;
                            if($isEnd) {
                                $message = 'The council of ' .$name. ' has won this challenge with '.$avgsteps.' average steps!';
                            } else {
                                $message = 'The council of ' .$name. ' is currently leading this challenge with '.$avgsteps.' average steps!';
                            }
                        }
					}
				break;


                case 2006:
                    if($isprocessed) 
                    {
                  
                        $leaderdata = DB::table('challenge_winners')->where('challenge_id','=',$challengeid)->get();
                        if($leaderdata) 
                        {
                            foreach($leaderdata as $data)
                            {
                                $name = $data->name;
                                $avgsteps = $data->steps;
                                $avgsteps = number_format($avgsteps);
                            }
                        }
                    } 
                    else 
                    {
                        $leaderdata = Corpchallenge::challenge_challenge6_query($corpid,$challenge_startdate,$challenge_enddate,TRUE);
                        if($leaderdata) 
                        {
                            foreach($leaderdata as $data)
                            {
                                $uid = '';
                                $name = Corpchallenge::hmm_challenge_service_getAgeGroupName($data->agegroup);
                                $avgsteps = $data->avgsteps;
                                $avgsteps = number_format($avgsteps);
                            }
                        }
                    }         
                    if($isprocessed) {
                        $message = 'The age group \'' .$name. '\' has won this challenge with '.$avgsteps.' average steps!';
                    } else {
                        $message = 'The age group \'' .$name. '\' is currently leading this challenge with '.$avgsteps.' average steps!';
                    }
                break;


                case CHALLENGE_10;
                if($isprocessed) 
                {

                    $leaderdata = DB::table('challenge_winners')
                                        ->where('challenge_id','=',$challengeid)
                                        ->orderBy('steps','DESC')
                                        ->take(1)
                                        ->get();
                
                
                    if($leaderdata) 
                    {
                        foreach($leaderdata as $data)
                        {
						    $name = $data->name;
						    $steps = $data->steps;
                        }
                    }
					
                }
                else 
                {
					$leaderdata = challenge_topavgsteps_numdays($corpid,$threshold,$challenge_startdate,$challenge_enddate,TRUE);	
					if($leaderrow = mysql_fetch_assoc($leaderdata)) {
						$urlpath = '';
						$uid = $leaderrow['userid'];
						$cp = content_profile_load('profile', $uid);
						if($cp) {
							if($cp->field_avatar[0]['filepath']) {
								$urlpath = $base_url . '/' . $cp->field_avatar[0]['filepath'];
							}	
						}						
						$name = $leaderrow['username'];
						$steps = $leaderrow['numdays'];
					}
				}
                if(isset($steps) && $steps > 0) 
                {
					$steps = number_format($steps);
                    if(validateMail($name))
                    {
						$removedomain = explode('@',$name);
                        if(count($removedomain) > 0) 
                        {
							$name = $removedomain[0];
						}
					}
					if($isEnd) {
						$message = $name . ' has won this challenge with '.$steps.' number of days reached '.number_format($threshold).' steps!';
					} else {
						$message = $name . ' is currently leading this challenge with '.$steps.' number of days reached '.number_format($threshold).' steps!';
					}
				}
				break;







        }
        if(!empty($message)) {
			if(empty($urlpath) && !empty($challenge_url)) {
				$urlpath = $base_url . '/' . $challenge_url;
			}
			$messagecount++;
			$messagearray[] = array("title"=> $title,"message"=> $message,"photourl"=>$urlpath);
		}

    }
    return $messagearray;
    } 
}




public function challenge_challenge2_query($corpid, $startdate, $enddate, $isTop = NO) 
{
      if($isTop) 
      {
        $leaderboarddata = DB::select(DB::raw("            
              SELECT 
              SUM(sumsteps) / COUNT(a.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNT(a.user_id) 'participants', city
              FROM
                  (SELECT 
                      a.user_id, sum(numberofsteps) 'sumsteps', b.city
                      FROM
                      d_pms a, user_profiles b, user_groups c
                      WHERE
                      a.user_id = b.user_id
                      AND a.user_id = c.user_id
                      AND c.company_id = '".$corpid."'
                      AND a.datetimestamp >= '".$startdate."'
                      AND a.datetimestamp <= '".$enddate."'
                      AND b.city <> ''
                      AND b.city <> 'City'
                      GROUP BY a.user_id,city ORDER by sumsteps) a
              GROUP BY city
              ORDER BY avgsteps DESC LIMIT 1

          "));
      }  
      else 
      {
          $leaderboarddata = DB::select(DB::raw("  
                      SELECT 
                      SUM(sumsteps) / COUNT(a.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNT(a.user_id) 'participants', a.city
                      FROM
                          (SELECT 
                              a.user_id, sum(numberofsteps) 'sumsteps', city
                              FROM
                              d_pms a, user_profiles b, user_groups c
                              WHERE
                              a.user_id = b.user_id
                              AND a.user_id = c.user_id
                              AND c.company_id = '".$corpid."'
                              AND a.datetimestamp >= '".$startdate."'
                              AND a.datetimestamp <= '".$enddate."'
                              AND b.city <> ''
                              AND b.city <> 'City'
                              GROUP BY a.user_id,city ORDER by sumsteps) a
                      GROUP BY city
                      ORDER BY avgsteps DESC
          "));
      }
      
      return $leaderboarddata;
  }



public function challenge_challenge3_query($corpid, $startdate, $enddate, $isTop = NO) 
{        
    if($isTop) {
       



         $leaderboarddata =  DB::select(DB::raw("

            SELECT 
            SUM(sumsteps) / COUNT(a.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.user_id) 'participants', gender
           FROM
               (SELECT 
                a.user_id, sum(numberofsteps) 'sumsteps', gender
                FROM
                d_pms a, user_profiles b, user_groups c
                WHERE
                a.user_id = b.user_id
                AND a.user_id = c.user_id
                AND c.company_id = '".$corpid."'
                AND datetimestamp >= '".$startdate."'
                AND datetimestamp <= '".$enddate."'
                AND (b.gender = '0' OR b.gender = '1')
                GROUP BY a.user_id,gender ORDER by sumsteps) a
           GROUP BY gender
           ORDER BY avgsteps DESC LIMIT 1
            "));
    } 
    else 
    {
        $leaderboarddata =  DB::select(DB::raw("
            SELECT 
             SUM(sumsteps) / COUNT(a.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.user_id) 'participants', gender
            FROM
                (SELECT 
                 a.user_id, sum(numberofsteps) 'sumsteps', gender
                 FROM
                 d_pms a, user_profiles b, user_groups c
                 WHERE
                 a.user_id = b.user_id
                 AND a.user_id = c.user_id
                 AND c.company_id = '".$corpid."'
                 AND datetimestamp >= '".$startdate."'
                 AND datetimestamp <= '".$enddate."'
                 AND (b.gender = '0' OR b.gender = '1')
                 GROUP BY a.user_id,gender ORDER by sumsteps) a
            GROUP BY gender
            ORDER BY avgsteps DESC              
            "));
    }    
    return $leaderboarddata;
}


public function challenge_challenge4_query($corpid, $startdate, $enddate, $isTop = NO) 
{
    if($isTop) 
    {
        $leaderboarddata = DB::select(DB::raw("        
        SELECT 
        SUM(sumsteps) / COUNT(a.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.user_id) 'participants',
        country
        FROM
            (SELECT 
                a.user_id, sum(numberofsteps) 'sumsteps', country
            FROM
                d_pms a, user_profiles b, user_groups c
            WHERE
                a.user_id = b.user_id
                    AND a.user_id = c.user_id
                    AND c.company_id = '".$corpid."'
                    AND datetimestamp >= '".$startdate."'
                    AND datetimestamp <= '".$enddate."'
                    AND b.country <> ''
                Group by a.user_id,country ORDER by sumsteps) a
        GROUP BY country
        ORDER BY avgsteps DESC
        LIMIT 1  
        "));
    } 

    else 
    {     
        $leaderboarddata = DB::select(DB::raw("        
        SELECT 
        SUM(sumsteps) / COUNT(a.user_id) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.user_id) 'participants',
        country
        FROM
        (SELECT 
            a.user_id, sum(numberofsteps) 'sumsteps', country
        FROM
           	d_pms a, user_profiles b, user_groups c
        WHERE
            a.user_id = b.user_id
                AND a.user_id = c.user_id
                AND c.company_id = '".$corpid."'
                AND datetimestamp >= '".$startdate."'
                AND datetimestamp <= '".$enddate."'
                AND b.country <> ''
            Group by a.user_id,country ORDER by sumsteps) a
        GROUP BY country
        ORDER BY avgsteps DESC
        "));
	}	
	return $leaderboarddata;
}


/*

HOLD DUE TO COUNCIL TABLE


public function challenge_challenge5_query($corpid, $startdate, $enddate, $isTop = NO) 
{

	if($isTop) {
		$leaderboarddata = DB::select(DB::raw("
        SELECT SUM(sumsteps) / COUNT(a.userid) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.userid) 'participants', council_type
			FROM
				(SELECT 
				 a.userid, sum(numberofsteps) 'sumsteps', council_type
				 FROM
				 hmm.d_pm a, drupal.council_info b, drupal.users_groups c
				 WHERE
				 a.userid = b.uid
    			 AND a.userid = c.userid
    			 AND c.orgid = $corpid
    			 AND datetimestamp >= '$startdate'
    			 AND datetimestamp <= '$enddate'
				 GROUP BY a.userid ORDER by sumsteps) a
			GROUP BY council_type
            ORDER BY avgsteps DESC LIMIT 1
            
            
            "));
    } 
    else 
    {
		$leaderboarddata =  $dbcon->doQuery ("SELECT SUM(sumsteps) / COUNT(a.userid) AS avgsteps, sum(sumsteps) 'totalsteps', COUNt(a.userid) 'participants', council_type
			FROM
				(SELECT 
				 a.userid, sum(numberofsteps) 'sumsteps', council_type
				 FROM
				 hmm.d_pm a, drupal.council_info b, drupal.users_groups c
				 WHERE
				 a.userid = b.uid
    			 AND a.userid = c.userid
    			 AND c.orgid = $corpid
    			 AND datetimestamp >= '$startdate'
    			 AND datetimestamp <= '$enddate'
				 GROUP BY a.userid ORDER by sumsteps) a
			GROUP BY council_type
			ORDER BY avgsteps DESC");
	}	
	return $leaderboarddata;
}

*/



public function challenge_challenge6_query($corpid, $startdate, $enddate, $isTop = NO) {
	
	if($isTop) {
		$leaderboarddata = DB::select(DB::raw("SELECT
        SUM(numberofsteps)/COUNT(distinct(a.user_id)) AS avgsteps, SUM(numberofsteps) 'totalsteps',COUNT(distinct(a.user_id)) 'participants',
        agegroup
     FROM
        (SELECT
            a.user_id,
                numberofsteps,
                CASE
                    WHEN TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 30 THEN '1'
                    ELSE CASE
                        WHEN
                            TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 30
                                AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 40
                        THEN
                            '2'
                        ELSE CASE
                            WHEN
                                TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 40
                                    AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 50
                            THEN
                                '3'
                            ELSE CASE
                                WHEN
                                    TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 50
                                        AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 60
                                THEN
                                    '4'
                                ELSE CASE
                                    WHEN
                                        TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 60
                                            AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 70
                                    THEN
                                        '5'
                                    ELSE '6'
                                END
                            END
                        END
                    END
                END AS agegroup
        FROM
            d_pms a, user_profiles b, user_groups c
        WHERE
            a.user_id = b.user_id
                AND b.birthdate <> ''
                AND a.user_id = c.user_id
                AND c.company_id = '".$corpid."'
                AND datetimestamp >= '".$startdate."'
                AND datetimestamp <=  '".$enddate."') a
     GROUP BY agegroup
     ORDER BY avgsteps DESC LIMIT 1"));
	} else {
		$leaderboarddata = DB::select(DB::raw("SELECT
        SUM(numberofsteps)/COUNT(distinct(a.user_id)) AS avgsteps, SUM(numberofsteps) 'totalsteps',COUNT(distinct(a.user_id)) 'participants',
        agegroup
     FROM
        (SELECT
            a.user_id,
                numberofsteps,
                CASE
                    WHEN TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 30 THEN '1'
                    ELSE CASE
                        WHEN
                            TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 30
                                AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 40
                        THEN
                            '2'
                        ELSE CASE
                            WHEN
                                TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 40
                                    AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 50
                            THEN
                                '3'
                            ELSE CASE
                                WHEN
                                    TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 50
                                        AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 60
                                THEN
                                    '4'
                                ELSE CASE
                                    WHEN
                                        TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) > 60
                                            AND TIMESTAMPDIFF(YEAR,  STR_TO_DATE(b.birthdate,'%b-%d-%Y'), CURDATE()) <= 70
                                    THEN
                                        '5'
                                    ELSE '6'
                                END
                            END
                        END
                    END
                END AS agegroup
        FROM
            d_pms a, user_profiles b, user_groups c
        WHERE
            a.user_id = b.user_id
                AND b.birthdate <> ''
                AND a.user_id = c.user_id
                AND c.company_id = '".$corpid."'
                AND datetimestamp >= '".$startdate."'
                AND datetimestamp <=  '".$enddate."') a
     GROUP BY agegroup
     ORDER BY avgsteps DESC"));
	}
	return $leaderboarddata;
}




public function hmm_challenge_service_getAgeGroupName($ageGroup) {
	$agegroupname = '';
	
	switch ($ageGroup) {
		case 1:
		$agegroupname .= '30 and under';
		break;
		case 2:
		$agegroupname .= '31 to 40';
		break;
		case 3:
		$agegroupname .= '41 to 50';
		break;
		case 4:
		$agegroupname .= '51 to 60';
		break;
		case 5:
		$agegroupname .= '61 to 70';
		break;
		case 6:
		$agegroupname .= 'over 70';
		break;
	}
	return $agegroupname;
}










}




