<?php

namespace App\Http\Controllers;
use App\User;
use App\Team;
use App\TeamUser;
use Illuminate\Http\Request;
use Validator;
use Log;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

   /*
    |--------------------------------------------------------------------------
    | API : GET /users
    | Description: get user list
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |     
    |--------------------------------------------------------------------------
    | Returns: {success: true, users: array}
    |--------------------------------------------------------------------------
    */
    
    public function index() {

        $rows = User::with('team')->get();

        return response()->json([
            "success"=>true,
            "users"=>$rows
        ]);
    }

   /*
    |--------------------------------------------------------------------------
    | API : POST /users
    | Description: Create user
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |     firstname      : string
    |     lastname       : string
    |     email          : string
    |     title          : string
    |     description    : string
    |     teams          : array
    |--------------------------------------------------------------------------
    | Returns: {success: true/false, error: ''/'error string'}
    |--------------------------------------------------------------------------
    */
    public function store(Request $request) {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
        );

        if (!$this->validate($request, $rules)) {
            return response()->json([
                'success' => false,
                'error' => 'validation error!',
            ]);
        } else {
            // store
            $user = new User;
            $user->firstname    = $request->input("firstname");
            $user->lastname     = $request->input("lastname");
            $user->email        = $request->input("email");
            $user->title        = $request->input("title");
            $user->description  = $request->input("description");
            $user->save();
            
            $teams = explode(",", $request->input("teams"));
            $user_id = $user->id;
            
            if (is_array($teams)) {
                TeamUser::where('user_id', $user_id)->delete();
                foreach ($teams as $team_id) {
                    $team_user_data = [
                        'user_id' => $user_id,
                        'team_id' => $team_id
                    ];
                    try {
                        TeamUser::create($team_user_data);
                    } catch (QueryException $ex) {
                        $error .= $ex->getMessage() . ",";
                    }
                }
            }
            return response()->json([
                'success' => true,
                'error' => $error,
            ]);
        }
    }

   /*
    |--------------------------------------------------------------------------
    | API : DELETE /users/{id}
    | Description: Delete user
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |     $id            : Number
    |--------------------------------------------------------------------------
    | Returns: {success: true, error: ''}
    |--------------------------------------------------------------------------
    */
    public function destroy($id) {
        $user = User::where('id', $id)->first();
        if (!is_null($user)) {
            $user->delete();
        }
        return response()->json([
            'success' => true,
            'error' => '',
        ]);
    }

  
   /*
    |--------------------------------------------------------------------------
    | API : PUT/PATCH /users/{id}
    | Description: Update user
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |     firstname      : string
    |     lastname       : string
    |     email          : string
    |     title          : string
    |     description    : string
    |     teams          : array
    |--------------------------------------------------------------------------
    | Returns: {success: true/false, error: ''/'error string'}
    |--------------------------------------------------------------------------
    */  
    public function update(Request $request, $id) {
        
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
        );

        if (!$this->validate($request, $rules)) {
            return response()->json([
                'success' => false,
                'error' => 'validation error!',
            ]);
        } else {
            $input = $request->only(['id','firstname','lastname','email','title','description']);
            
            User::where('id', $id)->update($input);
            $teams = explode(",", $request->input("teams"));
            $user_id = $id;
            $error = '';
            if (count($teams)>0) {
                TeamUser::where('user_id', $user_id)->delete();
                foreach ($teams as $team_id) {
                    $team_user_data = [
                        'user_id' => $user_id,
                        'team_id' => $team_id
                    ];
                    try {
                        TeamUser::create($team_user_data);
                    } catch (QueryException $ex) {
                        $error .= $ex->getMessage() . ",";
                    }
                    
                }
            }
            return response()->json([
                'success' => true,
                'error' => $error,
            ]);
        }
    }
}
