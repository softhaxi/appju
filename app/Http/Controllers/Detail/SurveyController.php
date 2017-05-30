<?php

namespace APPJU\Http\Controllers\Detail;

use Illuminate\Http\Request;

use APPJU\Http\Requests;
use APPJU\Http\Controllers\Controller;
use APPJU\Models\Detail\Survey;

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
            $item = [
                'id' => $survey->id,
                'survey_class' => $survey->class,
                'date_time' => $survey->created_at,
                'status' => $survey->status,
                'url' => $survey->url
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
     * @param array $params
     * @return List of survey
     */
    private function getParentSurveys(array $params) {
        $surveys = Survey::whereNull('parent_id')
            ->get();
        if(array_key_exists('status', $params) && $params['status'] != '') {
            $surveys = $surveys->where('status', $params['status']);
        }

        return $surveys->all();
    }

    /**
     *
     * @return Survey
     */
    private function getSurveyById($id) {
        $survey = Survey::where('id', $id)
            ->first();

        return $survey;
    }
}
