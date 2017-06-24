<?php

namespace APPJU\Http\Controllers\Detail;

use Illuminate\Http\Request;

use APPJU\Http\Requests;
use APPJU\Http\Controllers\Controller;
use APPJU\Models\Security\User;
use APPJU\Models\Detail\Survey;
use APPJU\Models\Detail\StreetLighting;
use APPJU\Models\Master\Customer;

/**
 * Survey controller
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class SurveyController extends Controller {
    
        /**
     * TODO
     * @param Request $request
     * @return type
     */
    public function search(Request $request) {
        $surveys = $this->getParentSurveys($request->all());
        $data = [];

        foreach ($surveys as $survey) {
            $dateTime = date_create($survey->created_at);
            $user = User::where('id', $survey->created_by)
                    ->first();
            
            $customer = $this->getCustomerByid($survey->surveyable->customer_id);
            if(!is_null($customer)) {
                $item = [
                    'id' => $survey->id,
                    'survey_class' => $survey->class,
                    'action' => $survey->action,
                    'customer_id' => $customer->id,
                    'customer_code' => $customer->code != 'DUMMY' ? $customer->code : null,
                    'customer_name' => $customer->name,
                    'date_time' => date_format($dateTime,"Y/m/d H:i:s"),
                    'created_by' => $user->name,
                    'status' => $survey->status,
                    'base_url' => url($survey->url),
                    'url' => url($survey->url . $survey->surveyable->id)
                ];
            } else {
                $item = [
                    'id' => $survey->id,
                    'survey_class' => $survey->class,
                    'action' => $survey->action,
                    'customer_id' => null,
                    'customer_code' => null,
                    'customer_name' => strtoupper($survey->surveyable->name),
                    'date_time' => date_format($dateTime,"Y/m/d H:i:s"),
                    'created_by' => $user->name,
                    'status' => $survey->status,
                    'base_url' => url($survey->url),
                    'url' => url($survey->url . $survey->surveyable->id)
                ];
            }
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
     * @param array $params
     * @return List of survey
     */
    protected function getParentSurveys(array $params) {
        $surveys = Survey::whereNull('parent_id')
                //->where('status', '!=', 1)
                ->orderBy('created_at', 'desc')
                ->get();
        if(array_key_exists('status', $params)) {
            $surveys = $surveys->where('status', (int)$params['status']);
        }
        return $surveys->all();
    }

    /**
     *
     * @return Survey
     */
    protected function getSurveyById($id) {
        $survey = Survey::where('id', $id)
            ->first();

        return $survey;
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    protected function getCustomerByid($id) {
        $customer = Customer::where('id', $id)
                ->first();

        return $customer;
    }
}
