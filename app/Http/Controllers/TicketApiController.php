<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Hash;
use Carbon\Carbon;
use App\User;
use App\Ticket;
use Illuminate\Support\Facades\DB;

class TicketApiController extends Controller
{
    //

   ///Next, I will create a function through which a user can get,create,update and delete todo items.
 
 
 
   /**
    * Get a validator for an incoming Todo request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  $type
    * @return \Illuminate\Contracts\Validation\Validator
    */
 
   public function validations($request,$type)
   {
		$errors = [];
		$error = false;

		if($type == "login"){
			$validator = Validator::make($request->all(),[
				'email' => 'required|email|max:255',
				'password' => 'required',
			]);

			if($validator->fails()){
				   $error = true;
				   $errors = $validator->errors();

			} 
		}
		else if($type == "create ticket")
		{
			$validator = Validator::make($request->all(),[
			   // 'todo' => 'required',
			   // 'description' => 'required',
			   // 'category' => 'required'
				'title' => 'required|min:3',
	            'content'=> 'required|min:10',
			]);

			if($validator->fails()){
			   $error = true;
			   $errors = $validator->errors();
			}

		} 
		else if($type == "update ticket")
		{
			$validator = Validator::make($request->all(),[
			   'title' => 'filled',
			   'content' => 'filled',
			   // 'category' => 'filled'
			]);

			if($validator->fails()) {
			   $error = true;
			   $errors = $validator->errors();
			}
		}

		return ["error" => $error,"errors"=>$errors];
   }

   /**
 
    * Display a listing of the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
 /*
   private function prepareResult($status, $data, $errors,$msg)
   {
       return ['status' => $status,'data'=> $data,'message' => $msg,'errors' => $errors];
   }
   */

   /**
    * Display a listing of the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
 
   public function index(Request $request)
   {
   		// $tickets = Ticket::where('user_id', $request->user()->id)->get();
       // return $this->prepareResult(true, Ticket::where('user_id', $request->user()->id)
   		return tupe_prepareResult(true, Ticket::where('user_id', $request->user()->id)
               ->get(), [],"All user tickets");
   }

    /**
    * Display the specified resource.
    *
    * @param  \App\Todo  $todo
    * @return \Illuminate\Http\Response
    */
 
   public function show(Request $request,$slug)
   {
   		$ticket = Ticket::whereSlug($slug)->firstOrFail();
       if($ticket->user_id == $request->user()->id)
       {
           // return $this->prepareResult(true, $ticket, [],"All results fetched");
       		return tupe_prepareResult(true, $ticket, [],"All results fetched");
       }
       else
       {
           // return $this->prepareResult(false, [], "unauthorized","You are not authenticated to view this ticket");
       	   return tupe_prepareResult(false, [], "unauthorized","You are not authenticated to view this ticket");
       }
 
   }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
 
   public function store(Request $request)
   {
       $error = $this->validations($request,"create ticket");
       if ($error['error']) 
       {
           // return $this->prepareResult(false, [], $error['errors'],"Error in creating todo");
       		return tupe_prepareResult(false, [], $error['errors'],"Error in creating todo");
       } 
       else 
       {
			$slug = uniqid();
			$ticket = new Ticket(array(
				'title' => $request->get('title'),
				'content' => $request->get('content'),
				'user_id' => $request->user()->id,
				'slug' => $slug
			));

			$ticket->save();
           // $todo = $request->user()->todo()->Create($request->all());
           // return $this->prepareResult(true, $ticket, $error['errors'],"Ticket created");
			return tupe_prepareResult(true, $ticket, $error['errors'],"Ticket created");
       }
   }

	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Todo  $todo
	* @return \Illuminate\Http\Response
	*/

   public function update(Request $request, $slug)
   {
		if($ticket->user_id == $request->user()->id)
		{
			$error = $this->validations($request,"update ticket");
			if($error['error']) 
			{
				// return $this->prepareResult(false, [], $error['errors'],"error in updating data");
				return tupe_prepareResult(false, [], $error['errors'],"error in updating data");
			} 
			else 
			{
				$ticket = Ticket::whereSlug($slug)->firstOrFail();
				$ticket->title = $request->get('title');
				$ticket->content = $request->get('content');
				if($request->get('status') != null) {
					$ticket->status = 0;
				} else {
					$ticket->status = 1;
				}
				$ticket->save();
				// $ticket = $ticket->fill($request->all())->save();
				// return $this->prepareResult(true, $ticket, $error['errors'],"updating data");
				return tupe_prepareResult(true, $ticket, $error['errors'],"updating data");

			}
		}
		else
		{
			// return $this->prepareResult(false, [], "unauthorized","You are not authenticated to edit this ticket");
			return tupe_prepareResult(false, [], "unauthorized","You are not authenticated to edit this ticket");
		}
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Todo  $todo
    * @return \Illuminate\Http\Response
    */
 
   public function destroy(Request $request,$slug)
   {
   		$ticket = Ticket::whereSlug($slug)->firstOrFail();
		if($ticket->user_id == $request->user()->id)
		{
			if ($ticket->delete()) 
			{
				// return $this->prepareResult(true, [], [],"Ticket deleted");
				return tupe_prepareResult(true, [], [],"Ticket deleted");
			}
		}
		else
		{
			// return $this->prepareResult(false, [], "unauthorized","You are not authenticated to delete this Ticket");
			return tupe_prepareResult(false, [], "unauthorized","You are not authenticated to delete this Ticket");
		}        

	}

	public function companyconfig(Request $request)
	{
		return response()->json([
	  	  'name' => 'Abigail',
	  	  'state' => 'CA'
		]);
	}


	public function logout(Request $request) 
	{

		// Auth::user()->AauthAcessToken()->delete();
		// $accessToken = Auth::user()->AauthAcessToken();
		$accessTokens = $request->user()->tokens()->get();
		foreach ($accessTokens as $token) {
			# code...
			DB::table('oauth_access_tokens')
            ->where([
            	['id', '=', $token->id],
            	['name', '=', 'HealthQuest App'],
            ])
            ->update([
                'revoked' => true
            ]);
        	// $token->revoke();
		}
       
        return response()->json(null, 204);

	}
 
}
