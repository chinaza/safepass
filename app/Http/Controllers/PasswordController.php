<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\TeamUser;
use App\Password;
use App\Traits\EncLib;
use App\Pkey;

class PasswordController extends Controller
{
  use EncLib;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $team = Team::find($request->teamId);

      if (!$team) return response('Team not found', 404);

      if (Gate::denies('get-password', $team))
      return response('You are not authorized to view passwords belonging to this team', 401);

      return Password::select('id', 'title', 'imgurl', 'username', 'team_id', 'url')->where('team_id', $request->teamId)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = Team::find($request->teamId);

        if (!$team) return response('Team not found', 404);

        if (Gate::denies('edit-password', $team))
        return response('You are not authorized to create passwords for this team', 401);

        //Validate Password

        //Generate AES Key from master

        //Encrypt password

        return Password::create([
          'title' => $request->title,
          'imgurl' => $request->imgurl,
          'username' => $request->username,
          'password' => $request->password,
          'company_id' => $request->company_id,
          'team_id' => $request->teamId,
          'url' => $request->url
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
		$password = Password::find($id);

		if (!$password) return response('Password not found', 404);
			
		$team = Team::find($password);
		$pkey = Auth::User()->pkey()

		if (Gate::denies('get-password', $team))
		return response('You are not authorized to view this password', 401);
		
		//Generate AES Key
		$this->generateAESKey($request->master, )
			
		$collection = collect($password);

		$filtered = $collection->only(['id', 'username', 'password']);

		$filtered->all();

		return Password::select('id', 'title', 'imgurl', 'username', 'team_id', 'url')->where('team_id', $request->teamId)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
