<?php

namespace APPJU\Http\Controllers\Security;

use Auth;

use Carbon\Carbon;

use Illuminate\Http\Request;

use APPJU\Http\Requests;
use APPJU\Http\Controllers\Controller;
use APPJU\Models\Security\User;

use Validator;

/**
 * User controller
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class UserController extends Controller {
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function post(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:6|max:100|unique:users,name',
            'email' => 'required|email|max:255|unique:users',
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:2|max:50',
            'device_code' => 'required',
            'mobile' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }
        $params = $request->all();
        if($request->is('/json/user')) {
            $params['user_id'] = Auth::user()->id;
        }
        $user = $this->saveUser($params);
        
        return response()->json([
                        'code' => 202,
                        'status' => 'ACCEPTED',
                        'response' => 'Accepted for processing',
                        'message' => 'User <strong>' . $user->name . '</strong> has been saved',
                        'data' => $user], 202);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function view(Request $request, $id) {
        if(!is_null($id)) {
            $user = $this->getUserById($id);
        } 
        if(is_null($user)) {
            return response()->json([
                    'code' => 404,
                    'type' => 'NOT_FOUND',
                    'reason' => 'Resource not found',
                    'errors' => 'User not found',
                    'redirect' => '/user'], 404);
        }

        $data = [
            'id' => $user->id,
            'username' => $user->name,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'device_code' => $user->device_code,
            'mobile' => $user->mobile,
            'status' => $user->status
        ];
        
        return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'data' => $data], 200);
    }

    /**
     * 
     * @param array $params
     * @return User user
     */
    private function saveUser(array $params) {
        $user = new User();
        $user->name = strtoupper(trim($params['username']));
        $user->email = strtolower(trim($params['email']));
        $user->password = bcrypt('password123');
        $user->first_name = strtoupper(trim($params['first_name']));
        $user->last_name = strtoupper(trim($params['last_name']));
        $user->device_code = strtolower(trim($params['device_code']));
        $user->mobile = trim($params['mobile']);
        $user->level = 2;
        $user->status = 1;
        $user->save();

        return $user;
    }

    /**
     * 
     * @param string $id
     * @return User user
     */
    private function getUserById($id) {
        $user = User::where('id', $id)
                ->first();

        return $user;
    }
}
