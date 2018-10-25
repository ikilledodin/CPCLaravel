<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorpinformationStream extends Model
{
    //
    protected $table = 'corpinformation_streams';
    protected $guarded = ['id'];
   

    public function company()
    {
    	return $this->belongsTo('App\Company');
    }



    public function getStreams($corpid)
    {
        $streams = CorpinformationStream::where('company_id','=',$corpid)
                    ->where('state','=','1')
                    ->orderBy('id','desc')               
                    ->get();

        $streamList = array();

        foreach($streams as $stream)
        {
            $streamData = array('title'=>$stream['title'],'message'=>$stream['message'],'photourl'=>$stream['photourl']);
            array_push($streamList,$streamData);
        }
        return $streamList;    
    }


}
