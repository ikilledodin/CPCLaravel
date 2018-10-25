<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    //
    protected $guarded = ['id'];
    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    /**
     * Set the serial with an encoded value.
     *
     * @param  string  $value
     * @return void
     */
    public function setSerialAttribute($value)
    {
    	switch ($this->attributes['devicetype']) {
    		case 100:
    		case 101:
    			# code...
    			$this->attributes['serial'] = hmm_devices_encode_serial($value);
    			break;
    		case 120:
    			$this->attributes['serial'] = hmm_devices_encode_serial_hrm($value);
    			break;
    		default:
    			# code...
    			$this->attributes['serial'] = $value;
    			break;
    	}
        	
    }
    /**
     * Set the serial with an encoded value.
     *
     * @param  string  $value
     * @return void
     */
    public function getSerialDecodedAttribute()
    {
    	switch ($this->attributes['devicetype']) {
    		case 100:
    		case 101:
    			# code...
    			return hmm_devices_decode_serial($this->attributes['serial']);// = hmm_devices_decode_serial($value);
    			break;
    		case 120:
    			return hmm_devices_decode_serial_hrm($this->attributes['serial']);// = hmm_devices_decode_serial_hrm($value);
    			break;
    		default:
    			# code...
    			return $this->attributes['serial'] = $value;
    			break;
    	}
        	
    }
}
