<?php

namespace APPJU\Http\Controllers\Master;

use Auth;

use Illuminate\Http\Request;

use APPJU\Http\Requests;
use APPJU\Http\Controllers\Controller;
use APPJU\Models\Master\Customer;

use PHPExcel_Cell;
use PHPExcel_IOFactory;

use Validator;

/**
 * Street lighting controller
 * 
 * @author Raja Sihombing <if09051@gmail.com>
 * @version 1.0.0
 * @since 1.0
 */
class StreetLightingController extends Controller {

    /**
     * TODO
     * @param Request $request
     * @return type
     */
    public function search(Request $request) {
        $customers = $this->getCustomers($request->all());
        $data = [];

        foreach ($customers as $customer) {
            $address = $customer->address;
            if($customer->address2 != '') {
                $address .= ' ' . $customer->address2;
            } 
            if($customer->address3 != '') {
                $address .= ' ' . $customer->address3;
            }
            $item = [
                'id' => $customer->id,
                'code' => $customer->code,
                'name' => $customer->name,
                'address' => $address,
                'power' => $customer->power,
                'status' => $customer->status
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
     * TODO
     * @param Request $request
     * @return type
     */
    public function post(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required|min:6|max:100|unique:customers,code',
            'name' => 'required|min:3|max:255',
            'address' => 'required|min:3|max:50',
            'rate' => 'in:P1,P2,P3'
        ]);
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }
        $params = $request->all();
        $params['user'] = Auth::user()->id;
        $result = $this->saveCustomer($params);
        if(array_key_exists('errors', $result)) {
            $errors[] = [
                $i => $result['errors']
            ];
        }
        $customer = $result['data'];
        return response()->json([
                    'code' => 202,
                    'status' => 'ACCEPTED',
                    'response' => 'Accepted for processing',
                    'message' => 'Legal street lighting <strong>' . $customer->name . '</strong> has been saved',
                    'data' => $customer], 202);
    }

    /**
     * TODO
     * @param Request $request
     * @return type
     */
    public function view(Request $request) {

    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function import(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimetypes:application/vnd.ms-excel,text/plain,text/csv,text/tsv',
        ]);
        
        if($validator->fails()) {
            return response()->json([
                    'code' => 400,
                    'type' => 'BAD_ARG',
                    'reason' => 'Invalid or bad argument',
                    'errors' => $validator->messages()], 400);
        }

        try {
            $content = $this->readContentFile($request->file('file'));
            $user = Auth::user()->id;
            $result = $this->saveContentFile($content, $user);
            if(array_has($result, 'errors')) {
                return response()->json([
                        'code' => 202,
                        'status' => 'ACCEPTED',
                        'response' => 'Accepted for processing',
                        'errors' => $result['errors'],
                        'message' => $result['message']], 202);
            } else {
                return response()->json([
                        'code' => 202,
                        'status' => 'ACCEPTED',
                        'response' => 'Accepted for processing',
                        'message' => $result['message']], 202);
            }
        } catch (Exception $ex) {
            return response()->json([
                        'code' => 500,
                        'status' => 'SERVER_ERROR',
                        'response' => 'Internal server error',
                        'data' => $ex->getMessage()], 500);
        }
    }

    /**
     * TODO
     * @param Request $request
     * @return type
     */
    public function export(Request $request) {

    }
    
    /**
     * TODO
     * @param Request $request
     * @return type
     */
    public function download(Request $request) {

    } 

    /**
     * 
     * @param array $params
     * @return List of customer
     */
    private function getCustomers(array $params) {
        $customers = Customer::where('status', 1)
            ->get();

        return $customers->all();
    }


    /**
     * Read all content from uploaded file
     * 
     * @param File $file
     * @return type
     * @throws Exception
     */
    private function readContentFile($file) {
        try {
            $type = PHPExcel_IOFactory::identify($file);
            if($type == 'CSV' || $type == 'TSV') {$reader = PHPExcel_IOFactory::createReader($type)
                    ->setDelimiter(',')->setEnclosure('"')->setSheetIndex(0)->load($file);} 
            else {$reader = PHPExcel_IOFactory::load($file)->setActiveSheetIndex(0);}
            $worksheet = $reader->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());
            for ($row = 1; $row <= $highestRow; $row++) {$file_data = array();
                for ($col= 0; $col < $highestColumn; $col++){  
                    $value=$worksheet->getCellByColumnAndRow($col, $row)->getValue(); 
                    $data[$col]=trim($value);
                }
                $content[] = $data;
            }
            return $content;
        } catch (Exception $ex) {
            throw new Exception($ex);
        }
    }

    /**
     * 
     * @param type $content
     * @param type $user
     * @return string
     */
    protected function saveContentFile($content, $user) {
        $content = array_slice($content, 1);
        $errors = [];
        $i = 1;
        foreach ($content as $item) {
            $customer = Customer::where('code', trim($item[0]))
                    ->where('status', 1)
                    ->first();
            if(!is_null($customer)) {
                $errors[] = [
                    $i => 'Customer at line was already exist'
                ];
                $i++;
                continue;
            } else {
                $params = [
                    'code' => trim($item[0]),
                    'name' => strtoupper(trim($item[1])),
                    'address' => trim($item[2]),
                    'rate' => trim($item[3]),
                    'power' => trim($item[4]),
                    'stand_start' => trim($item[5]),
                    'stand_end' => trim($item[6]),
                    'kwh' => trim($item[7]),
                    'ptl' => trim($item[8]),
                    'stamp' => trim($item[9]),
                    'ppn' => trim($item[10]),
                    'pju' => trim($item[11]),
                    'monthly_bill' => trim($item[12]),
                    'user' => $user
                ];
                $result = $this->saveCustomer($params);
                if(array_key_exists('errors', $result)) {
                    $errors[] = [
                        $i => $result['errors']
                    ];
                }
                $i++;
            }
        }
        $data['message'] = 'Data has been uploaded and saved.';
        if(!is_null($errors)) {
            $data['errors'] = $errors; 
        }
        return $data;
    }

    /**
     * 
     * @param array $params
     * @return array of value customer
     */
    private function saveCustomer(array $params) {
        $data = [];
        try {
            $customer = new Customer();
            $customer->code = $params['code'];
            $customer->name = $params['name'];
            $customer->address = $params['address'];
            $customer->address2 = array_key_exists('address2', $params) ? $params['address2'] : null;
            $customer->address3 = array_key_exists('address3', $params) ? $params['address3'] : null;
            $customer->rate = $params['rate'];
            $customer->power = $params['power'];
            $customer->stand_start = $params['stand_start'];
            $customer->stand_end = $params['stand_end'];
            $customer->kwh = $params['kwh'];
            $customer->ptl = $params['ptl'];
            $customer->stamp = $params['stamp'];
            $customer->ppn = $params['ppn'];
            $customer->pju = array_key_exists('pju', $params) ? $params['pju'] : 0;
            $customer->monthly_bill = $params['monthly_bill'];
            $customer->status = 1;
            $customer->created_by = $params['user'];

            $customer->save();

            $data['data'] = $customer;
        } catch (Exception $ex) {
            $data['errors'] = $ex->getMessage();
        }
        return $data;
    } 
}
