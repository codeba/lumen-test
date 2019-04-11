<?php

namespace App\Http\Controllers;
use App\User;
use App\Team;
use App\TeamUser;
use Illuminate\Http\Request;
use Validator;

class TeamController extends Controller
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
    | API : GET /teams
    | Description: get team list
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |   None
    |--------------------------------------------------------------------------
    | Returns: {success: true, users: array}
    |--------------------------------------------------------------------------
    */
    
    public function index() {

        $rows = Team::with('user')->get();

        return response()->json([
            "success"=>true,
            "teams"=>$rows
        ]);
    }

   /*
    |--------------------------------------------------------------------------
    | API : POST /teams
    | Description: Create a Team
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |     name          : string    
    |--------------------------------------------------------------------------
    | Returns: {success: true/false, error: ''/'error string'}
    |--------------------------------------------------------------------------
    */
    public function store(Request $request) {
        
        $rules = array(
            'name' => 'required',
        );
        
        if (!$this->validate($request, $rules)) {
            return response()->json([
                'success' => false,
                'error' => 'validation error!',
            ]);
        } else {
            // store
            $team = new Team;
            $team->name         = $request->input("name");
            $team->is_active    = 1;
            $team->save();

            return response()->json([
                'success' => true,
                'error' => '',
            ]);
        }
    }

   /*
    |--------------------------------------------------------------------------
    | API : DELETE /teams/{id}
    | Description: Delete team
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |     $id            : Number
    |--------------------------------------------------------------------------
    | Returns: {success: true, error: ''}
    |--------------------------------------------------------------------------
    */
    public function destroy($id) {
        $team = Team::where('id', $id)->first();
        if (!is_null($team)) {
            $team->delete();
        }
        return response()->json([
            'success' => true,
            'error' => '',
        ]);
    }

  
   /*
    |--------------------------------------------------------------------------
    | API : PUT/PATCH /team/{id}
    | Description: Update team
    |--------------------------------------------------------------------------
    | Params:
    |--------------------------------------------------------------------------
    |     name           : string
    |     is_active      : number
    |--------------------------------------------------------------------------
    | Returns: {success: true/false, error: ''/'error string'}
    |--------------------------------------------------------------------------
    */  
    public function update(Request $request, $id) {
        
        $rules = array(
            'name' => 'required'
        );

        if (!$this->validate($request, $rules)) {
            return response()->json([
                'success' => false,
                'error' => 'validation error!',
            ]);
        } else {
            $input = $request->only(['name','is_active']);
            
            Team::where('id', $id)->update($input);
            return response()->json([
                'success' => true,
                'error' => '',
            ]);
        }
    }
}
