<?php

namespace APPJU\Http\Controllers\Survey;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use APPJU\Http\Requests;
use APPJU\Http\Controllers\Detail\SurveyController as Controller;
use APPJU\Models\Master\Customer;
use APPJU\Models\Detail\Survey;
use APPJU\Models\Detail\StreetLighting;
use APPJU\Models\Detail\StreetLightingLamp as Lamp;
use APPJU\Models\Detail\Photo;
use Validator;

/**
 * Street lighting survey controller
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class StreetLightingSurveyController extends Controller {
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function searchLamp(Request $request) {
        $params = $request->all();
        if(array_key_exists($params, 'streetlighting')) {
            $lamps = $this->getStreetLightingsByStreetLighting($params);
        }
        $data = [];
        
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
                    'mobile_survey_id' => 'required',
                    'number_of_lamp' => 'required|numeric|min:1',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'photo' => 'mimes:jpg,jpeg,JPEG,png,gif,bmp|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'code' => 400,
                        'type' => 'BAD_ARG',
                        'reason' => 'Invalid or bad argument',
                        'errors' => $validator->messages()], 400);
        }

        $params = $request->all();
        $params['survey_status'] = 1;
        $params['level'] = 1;
        $params['url'] = '/streetlighting/location/';
        $params['action'] = 'CREATE';
        if (array_key_exists('user_id', $params)) {
            $params['created_by'] = $params['user_id'];
        }
        $params['status'] = 1;

        $result = $this->saveStreetLighting($params);
        if (array_key_exists('errors', $result)) {
            $errors[] = [
                $i => $result['errors']
            ];
        }
        $streetlighting = $result['data'];
        $data = [
            'survey_id' => $streetlighting['survey_id'],
            'street_lighting_id' => $streetlighting['street_lighting_id'],
            'mobile_survey_id' => $params['mobile_survey_id'],
            'has_file' => $request->hasFile('photo')
        ];
        
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $destination = base_path() . '/public/upload/streetlighting/';
            $rename = $streetlighting['street_lighting_id'] . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $rename);
            $data = array_add($data, $rename, $destination);
        }

        return response()->json([
                    'code' => 202,
                    'status' => 'ACCEPTED',
                    'response' => 'Accepted for processing',
                    'message' => 'Street lighting survey has been saved',
                    'data' => $data], 202);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function postLamp(Request $request) {
        $validator = Validator::make($request->all(), [
                    'mobile_survey_id' => 'required',
                    'parent_survey_id' => 'required|exists:surveys,id',
                    'street_lighting_id' => 'required|exists:street_lightings,id',
                    'photo' => 'mimes:jpg,jpeg,JPEG,png,gif,bmp|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'code' => 400,
                        'type' => 'BAD_ARG',
                        'reason' => 'Invalid or bad argument',
                        'errors' => $validator->messages()], 400);
        }
        
        $survey = Survey::with('surveyable')
                ->where('id', $request->input('parent_survey_id'))
                ->first();
        
        $params = $request->all();
        $params['survey_status'] = 1;
        $params['parent_id'] = $params['parent_survey_id'];
        $params['action'] = 'CREATE';
        $params['url'] = '/streetlighting/location/lamp/';
        $params['level'] = 1;
        if (array_key_exists('user_id', $params)) {
            $params['created_by'] = $params['user_id'];
        }
        $params['status'] = 1;

        $result = $this->saveStreetLightingLamp($params);
        if (array_key_exists('errors', $result)) {
            $errors[] = [
                $i => $result['errors']
            ];
        }
        $lamp = $result['data'];
        $data = [
            'survey_id' => $lamp['survey_id'],
            'street_lighting_id' => $lamp['street_lighting_id'],
            'street_lighting_lamp_id' => $lamp['street_lighting_lamp_id'],
            'mobile_survey_id' => $params['mobile_survey_id']
        ];
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $destination = base_path() . '/public/upload/streetlighting/lamp';
            $rename = $lamp->id . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $rename);
        }
        return response()->json([
                    'code' => 202,
                    'status' => 'ACCEPTED',
                    'response' => 'Accepted for processing',
                    'message' => 'Street lighting survey has been saved',
                    'data' => $data], 202);
    }
    
    /**
     * 
     * @param array $params
     * @return type
     */
    private function saveStreetLighting(array $params) {
        if (array_key_exists('id', $params)) {
            $survey = $this->getSurveyById($params['id']);
            $streetlighting = $survey->surveyable();
        } else {
            $survey = new Survey();
            $streetlighting = new StreetLighting();
        }

        $data = [];
        try {
            $survey->class = array_key_exists('class', $params) ? trim($params['class']) : $survey->class;
            $survey->level = array_key_exists('level', $params) ? trim($params['level']) : $survey->level;
            $survey->action = array_key_exists('action', $params) ? trim($params['action']) : $survey->action;
            $survey->parent_id = array_key_exists('parent_id', $params) ? trim($params['parent_id']) : $survey->parent_id;
            $survey->url = array_key_exists('url', $params) ? trim($params['url']) : $survey->url;
            $survey->status = array_key_exists('survey_status', $params) ? $params['survey_status'] : $survey->status;
            $survey->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $survey->created_by;


            $streetlighting->customer_id = array_key_exists('customer_id', $params) ? trim($params['customer_id']) : $streetlighting->customer_id;
            $streetlighting->name = array_key_exists('name', $params) ? trim($params['name']) : $streetlighting->name;
            $streetlighting->address = array_key_exists('address', $params) ? trim($params['address']) : $streetlighting->address;
            $streetlighting->power = array_key_exists('power', $params) ? trim($params['power']) : $streetlighting->power;
            $streetlighting->rate = array_key_exists('power', $params) ? trim($params['rate']) : $streetlighting->rate;
            $streetlighting->number_of_lamp = array_key_exists('number_of_lamp', $params) ? $params['number_of_lamp'] : $streetlighting->number_of_lamp;
            $streetlighting->latitude = array_key_exists('latitude', $params) ? $params['latitude'] : $streetlighting->latitude;
            $streetlighting->longitude = array_key_exists('longitude', $params) ? $params['longitude'] : $streetlighting->longitude;
            $streetlighting->geolocation = array_key_exists('geolocation', $params) ? trim($params['geolocation']) : $streetlighting->geolocation;
            $streetlighting->status = array_key_exists('status', $params) ? $params['status'] : $streetlighting->status;
            $streetlighting->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $streetlighting->created_by;
    
            $date = new Carbon();
            if(array_has($params, 'created_at')) {
                $date = Carbon::parse($params['created_at']);
                $streetlighting->created_at = $date;
                $survey->created_at = $date;
            }
            
            if($streetlighting->customer_id == '') {
                $customer = Customer::where('status', -1)
                        ->whereRaw('LOWER(name) like ? ', array(strtolower(trim($params['name']))))
                        ->orderBy('created_at', 'desc')
                        ->first();
                if(is_null($customer)) {
                    $customer = new Customer();
                    $customer->code = 'DUMMY';
                    $customer->name = array_key_exists('name', $params) ? strtoupper(trim($params['name'])) : $customer->name;
                    $customer->address = array_key_exists('address', $params) ? ucfirst(trim($params['address'])) : $customer->address;
                    $customer->power = array_key_exists('power', $params) ? trim($params['power']) : $customer->power;
                    $customer->rate = array_key_exists('power', $params) ? trim($params['rate']) : $customer->rate;
                    $customer->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $customer->created_by;
                    $customer->status = -1;
                    $customer->created_at = $date;
                    $customer->save();
                }
                $streetlighting->customer_id = $customer->id;
            }
            
            $streetlighting->save();
            $streetlighting->survey()->save($survey);
            
            if(array_has($params, 'encoded_photo')) {
                $photo = new Photo();
                $photo->created_at = $date;
                $photo->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $photo->created_by;
                $encodedPhoto = $params['encoded_photo'];
                if(!empty($encodedPhoto)) {
                    $path = '/upload/streetlighting/' . $streetlighting->id . '.png';
                    $destination = base_path() . '/public' . $path;
                    file_put_contents($destination, base64_decode($encodedPhoto));
                    $photo->path = $path;
                }
                $photo->name = $streetlighting->id;
                $streetlighting->photo()->save($photo);
            }

            $data['data'] = [
                'survey_id' => $survey->id,
                'street_lighting_id' => $streetlighting->id,
                'customer_id' => $streetlighting->customer_id,
                'number_of_lamp' => $streetlighting->number_of_lamp,
                'latitude' => $streetlighting->latitude,
                'longitude' => $streetlighting->longitude,
                'status' => $streetlighting->status
            ];
        } catch (Exception $ex) {
            $data['errors'] = $ex->getMessage();
        }
        return $data;
    }

    /**
     * 
     * @param array $params
     * @return type
     */
    private function saveStreetLightingLamp(array $params) {
        if (array_key_exists('id', $params)) {
            $survey = $this->getSurveyById($params['id']);
            $lamp = $survey->surveyable();
        } else {
            $survey = new Survey();
            $lamp = new Lamp();
        }

        $data = [];
        try {
            $survey->class = array_key_exists('class', $params) ? trim($params['class']) : $survey->class;
            $survey->level = array_key_exists('level', $params) ? trim($params['level']) : $survey->level;
            $survey->action = array_key_exists('action', $params) ? trim($params['action']) : $survey->action;
            $survey->url = array_key_exists('url', $params) ? trim($params['url']) : $survey->url;
            $survey->parent_id = array_key_exists('parent_id', $params) ? trim($params['parent_id']) : $survey->parent_id;
            $survey->status = array_key_exists('survey_status', $params) ? $params['survey_status'] : $survey->status;
            $survey->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $survey->created_by;


            $lamp->street_lighting_id = array_key_exists('street_lighting_id', $params) ? trim($params['street_lighting_id']) : $lamp->street_lighting_id;
            $lamp->code = array_key_exists('code', $params) ? strtoupper(trim($params['code'])) : $lamp->code;
            $lamp->type = array_key_exists('type', $params) ? strtoupper(trim($params['type'])) : $lamp->type;
            $lamp->power = array_key_exists('power', $params) ? $params['power'] : $lamp->power;
            $lamp->latitude = array_key_exists('latitude', $params) ? $params['latitude'] : $lamp->latitude;
            $lamp->longitude = array_key_exists('longitude', $params) ? $params['longitude'] : $lamp->longitude;
            $lamp->geolocation = array_key_exists('geolocation', $params) ? trim($params['geolocation']) : $lamp->geolocation;
            $lamp->remark = array_key_exists('remark', $params) ? ucfirst(trim($params['remark'])) : $lamp->remark;
            $lamp->status = array_key_exists('status', $params) ? $params['status'] : $lamp->status;
            $lamp->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $lamp->created_by;

            $date = new Carbon();
            if(array_has($params, 'created_at')) {
                $date = Carbon::parse($params['created_at']);
                $lamp->created_at = $date;
                $survey->created_at = $date;
            }
            
            $lamp->save();
            $lamp->survey()->save($survey);
            
            if(array_has($params, 'encoded_photo')) {
                $photo = new Photo();
                $photo->created_at = $date;
                $photo->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $photo->created_by;
                $encodedPhoto = $params['encoded_photo'];
                if(!empty($encodedPhoto)) {
                    $path = '/upload/streetlighting/' . $streetlighting->id . '.png';
                    $destination = base_path() . '/public' . $path;
                    file_put_contents($destination, base64_decode($encodedPhoto));
                    $photo->path = $path;
                }
                $photo->name = $lamp->id;
                $lamp->photo()->save($photo);
            }

            $data['data'] = [
                'survey_id' => $survey->id,
                'street_lighting_id' => $lamp->street_lighting_id,
                'street_lighting_lamp_id' => $lamp->id,
                'status' => $lamp->status
            ];
        } catch (Exception $ex) {
            $data['errors'] = $ex->getMessage();
        }
        return $data;
    }

}
