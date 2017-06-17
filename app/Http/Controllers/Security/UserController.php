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
    public function auth(Request $request) {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required'
            //'device_code' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }

        $user = $this->getUserMobile($request->all());

        if(!is_null($user)) {
            $full_name = $user->first_name;
            if($user->middle_name != '') {
                $full_name .= ' ' . $user->middle_name;
            } 
            $full_name .= ' ' . $user->last_name;
            $data = [
                'id' => $user->id,
                'username' => $user->name,
                'email' => $user->email,
                'full_name' => $full_name,
                'avatar' => $user->avatar,
                'level' => $user->level,
                'status' => $user->status
            ];
            return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'data' => $data], 200);
        } else {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => 'Username or password is invalid'], 400);
        }
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function search(Request $request) {
        $users = $this->getUsers($request->all());
        $data = [];
        foreach ($users as $user) {
            $full_name = $user->first_name;
            if($user->middle_name != '') {
                $full_name .= ' ' . $user->middle_name;
            } 
            $full_name .= ' ' . $user->last_name;
            $item = [
                'id' => $user->id,
                'username' => $user->name,
                'email' => $user->email,
                'full_name' => $full_name,
                'device_code' => $user->device_code,
                'mobile' => $user->mobile,
                'status' => $user->status
            ];
            $data[] = $item;
        }
        
        return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'data' => $data], 200);
    }

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
        $params['password'] = bcrypt('password123');
        $params['level'] = 2;
        $params['status'] = 1;
        $params['created_by'] = Auth::user()->id;
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
    public function put(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:6|max:100',
            'email' => 'required|email|max:255',
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
        $params['updated_by'] = Auth::user()->id;
        $user = $this->saveUser($params);
        
        return response()->json([
                        'code' => 202,
                        'status' => 'ACCEPTED',
                        'response' => 'Accepted for processing',
                        'message' => 'User <strong>' . $user->name . '</strong> has been updated',
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
            'avatar' => $user->avatar,
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
     * @param Request $request
     * @return type
     */
    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }
        $user = $this->getUserById($request->input('id'));
        $user->status = 0;
        $user->save();
        $user->delete();

        return response()->json([
                        'code' => 202,
                        'status' => 'ACCEPTED',
                        'response' => 'Accepted for processing',
                        'message' => 'User <strong>' . $user->name . '</strong> has been deleted'
                        ], 202);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function status(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'action' => 'in:activate,deactivate'
        ]);
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }
        $user = $this->getUserById($request->input('id'));
        if(is_null($user)) {
            return response()->json([
                    'code' => 404,
                    'type' => 'NOT_FOUND',
                    'reason' => 'Resource not found',
                    'errors' => 'User not found',
                    'redirect' => '/user'], 404);
        }
        if($request->input('action') == 'activate') {
            $user->status = 1;
        } else if($request->input('action') == 'deactivate') {
            $user->status = 0;
        }
        $user->updated_by = Auth::user()->id;
        $user->save();

        return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'message' => 'User <strong>' . $user->name . '</strong> has been <strong>' . $request->input('action') .'d</strong>',
                        'data' => $user], 200);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function reset(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }
        
        $user = $this->getUserById($request->input('id'));
        
        if(is_null($user)) {
            return response()->json([
                    'code' => 404,
                    'type' => 'NOT_FOUND',
                    'reason' => 'Resource not found',
                    'errors' => 'User not found',
                    'redirect' => '/user'], 404);
        }
        
        $password = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
        $params['id'] = $user->id;
        $params['password'] = bcrypt($password);
        $params['status'] = 2;
        $params['updated_by'] = Auth::user()->id;
        $user = $this->saveUser($params);
        
        $data = [
            'id' => $user->id,
            'username' => $user->name,
            'password' => $password
        ];
        
        return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'data' => $data], 200);
    }
    
    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'new_password' => 'required',
        ]);
        
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }
        
        $user = $this->getUserById($request->input('id'));
        
        if(is_null($user)) {
            return response()->json([
                    'code' => 404,
                    'type' => 'NOT_FOUND',
                    'reason' => 'Resource not found',
                    'errors' => 'User not found',
                    'redirect' => '/user'], 404);
        }
        
        $params['id'] = $user->id;
        $params['password'] = bcrypt(trim($request->input('new_password')));
        $params['status'] = 1;
        $user = $this->saveUser($params);
        
        $data = [
            'id' => $user->id,
            'username' => $user->name,
            'status' => $user->status
        ];
        
        return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'message' => 'New password for <strong>' . $user->name . '</strong> saved',
                        'data' => $data], 200);
    }

    /**
     * 
     * @param array $params
     * @return User user
     */
    private function saveUser(array $params) {
        if(array_key_exists('id', $params)) {
            $user = $this->getUserById($params['id']);
        } else {    
            $user = new User();
        }
        $user->name = array_key_exists('username', $params) ? strtoupper(trim($params['username'])) : $user->name;
        $user->email = array_key_exists('email', $params) ? strtolower(trim($params['email'])) : $user->email;
        $user->password = array_key_exists('password', $params) ? $params['password'] : $user->password;
        $user->first_name = array_key_exists('first_name', $params) ? ucfirst(trim($params['first_name'])) : $user->first_name;
        $user->middle_name = array_key_exists('middle_name', $params) ? ucfirst(trim($params['middle_name'])) : $user->middle_name;
        $user->last_name = array_key_exists('last_name', $params) ? ucfirst(trim($params['last_name'])) : $user->last_name;
        $user->device_code = array_key_exists('device_code', $params) ? strtolower(trim($params['device_code'])) : $user->device_code;
        $user->mobile = array_key_exists('mobile', $params) ? trim($params['mobile']) : $user->mobile;
        $user->level = array_key_exists('level', $params) ? $params['level'] : $user->level;
        $user->status = array_key_exists('status', $params) ? $params['status'] : $user->status;
        $user->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $user->created_by;
        $user->updated_by = array_key_exists('updated_by', $params) ? $params['updated_by'] : $user->updated_by;
        $user->save();

        return $user;
    }

    /**
     * 
     * @param array $params
     * @return List of User
     */
    private function getUsers(array $params) {
        $users = User::where('level', 2)
            ->get();
        

        return $users->all();
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

    /**
     * 
     * @param array $params
     * @return User mobile
     */
    private function getUserMobile(array $params) {
        $login = $params['login'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if($field == 'name') {
            $login = strtoupper(trim($login));
        } else {
            $login = strtolower(trim($login));
        }

        $credentials = [
            $field => $login,
            'password' => $params['password']
        ];

        if(Auth::attempt($credentials)) {
            $user = Auth::user();
        }

        if(!is_null($user)) {
            if($user->status == 0) {
                return null;
            }
            if($user->level == 2) {
                if(array_key_exists('device_code',$params) && $user->device_code == $params['device_code']) {
                    return $user;
                } else {
                    return $user;
                }
            } else if($user->level == 1) {
                return $user;
            }
        } else {
            return null;
        }
    }
}
