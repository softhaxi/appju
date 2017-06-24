<?php

namespace APPJU\Http\Controllers\Master;

use Auth;

use Carbon\Carbon;

use Illuminate\Http\Request;

use APPJU\Http\Requests;
use APPJU\Http\Controllers\Controller;
use APPJU\Models\Master\Customer;
use APPJU\Models\Detail\StreetLighting;
use APPJU\Models\Detail\StreetLightingLamp;

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
     * 
     * @param Request $request
     * @return type
     */
    public function index(Request $request) {
        $params = $request->all();
        $params['status'] = 1;
        $customers = $this->getCustomers($params);
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
                'full_address' => $address,
                'address' => $customer->address,
                'address2' => $customer->address2,
                'address3' => $customer->address3,
                'rate' => $customer->rate,
                'power' => $customer->power,
                'stand_start' => $customer->stand_start,
                'stand_end' => $customer->stand_end,
                'kwh' => $customer->kwh,
                'ptl' => $customer->ptl,
                'stamp' => $customer->stamp,
                'bank_fee' => $customer->bank_fee,
                'ppn' => $customer->ppn,
                'pju' => $customer->pju,
                'monthly_bill' => $customer->monthly_bill,
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
    public function search(Request $request) {
        $params = $request->all();
        $customers = $this->getCustomers($params);
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
                'full_address' => $address,
                'address' => $customer->address,
                'address2' => $customer->address2,
                'address3' => $customer->address3,
                'rate' => $customer->rate,
                'power' => $customer->power,
                'stand_start' => $customer->stand_start,
                'stand_end' => $customer->stand_end,
                'kwh' => $customer->kwh,
                'ptl' => $customer->ptl,
                'stamp' => $customer->stamp,
                'bank_fee' => $customer->bank_fee,
                'ppn' => $customer->ppn,
                'pju' => $customer->pju,
                'monthly_bill' => $customer->monthly_bill,
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
     * @param Request $request
     * @return type
     */
    public function post(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required|min:6|max:100',
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
        $params['status'] = 1;
        $params['created_by'] = Auth::user()->id;
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
    public function put(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => 'required|min:6|max:100',
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
        $params['updated_by'] = Auth::user()->id;
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
                    'message' => 'Legal street lighting <strong>' . $customer->name . '</strong> has been updated',
                    'data' => $customer], 202);
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function view(Request $request, $id) {
        if(!is_null($id)) {
            $customer = $this->getCustomerById($id);
        } 
        if(is_null($customer)) {
            return response()->json([
                    'code' => 404,
                    'type' => 'NOT_FOUND',
                    'reason' => 'Resource not found',
                    'errors' => 'Street lighting not found',
                    'redirect' => '/streetlighting'], 404);
        }

        $data = [
            'id' => $customer->id,
            'code' => $customer->code,
            'name' => $customer->name,
            'address' => $customer->address,
            'address2' => $customer->address2,
            'address3' => $customer->address3,
            'rate' => $customer->rate,
            'power' => $customer->power,
            'stand_start' => $customer->stand_start,
            'stand_end' => $customer->stand_end,
            'kwh' => $customer->kwh,
            'ptl' => $customer->ptl,
            'stamp' => $customer->stamp,
            'bank_fee' => $customer->bank_fee,
            'ppn' => $customer->ppn,
            'pju' => $customer->pju,
            'monthly_bill' => $customer->monthly_bill,
            'status' => $customer->status
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
        $customer = $this->getCustomerById($request->input('id'));
        $customer->status = 0;
        $customer->save();
        $customer->delete();

        return response()->json([
                        'code' => 202,
                        'status' => 'ACCEPTED',
                        'response' => 'Accepted for processing',
                        'message' => 'Street lighting <strong>' . $customer->code . ' - ' . $customer->name . '</strong> has been deleted'
                        ], 202);
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
        $customer = $this->getCustomerById($request->input('id'));
        if(is_null($customer)) {
            return response()->json([
                    'code' => 404,
                    'type' => 'NOT_FOUND',
                    'reason' => 'Resource not found',
                    'errors' => 'Street lighting not found',
                    'redirect' => '/streetlighting'], 404);
        }
        if($request->input('action') == 'activate') {
            $customer->status = 1;
        } else if($request->input('action') == 'deactivate') {
            $customer->status = 0;
        }
        $customer->updated_by = Auth::user()->id;
        $customer->save();

        return response()->json([
                        'code' => 202,
                        'status' => 'ACCEPTED',
                        'response' => 'Accepted for processing',
                        'message' => 'Street lighting <strong>' . $customer->code . ' - ' . $customer->name . '</strong> has been <strong>' . $request->input('action') .'d</strong>',
                        'data' => $customer], 202);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function unregistered(Request $request) {
        $params = $request->all();
        $params['status'] = -1;
        $customers = $this->getUnregisteredCustomers($params);
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
                'full_address' => $address,
                'address' => $customer->address,
                'address2' => $customer->address2,
                'address3' => $customer->address3,
                'rate' => $customer->rate,
                'power' => $customer->power,
                'stand_start' => $customer->stand_start,
                'stand_end' => $customer->stand_end,
                'kwh' => $customer->kwh,
                'ptl' => $customer->ptl,
                'stamp' => $customer->stamp,
                'bank_fee' => $customer->bank_fee,
                'ppn' => $customer->ppn,
                'pju' => $customer->pju,
                'monthly_bill' => $customer->monthly_bill,
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
     * 
     * @param Request $request
     * @return type
     */
    public function location(Request $request) {
        $params = $request->all();
        if(array_key_exists('customer', $params)) {
            $streetLightings = $this->getStreetLightingsByCustomer($params);
        } else {
            $streetLightings = $this->getStreetLightings($params);
        }
        $data = [];
        
        foreach ($streetLightings as $streetLighting) {
            $createdAt = new Carbon($streetLighting->created_at);
            if(!is_null($streetLighting->photo)) {
                $photo = url($streetLighting->photo->path);
            } else {
                $photo = null;
            }
            $item = [
                'id' => $streetLighting->id,
                'customer_id' => $streetLighting->customer->id,
                'customer_code' => $streetLighting->customer->code,
                'customer_name' => $streetLighting->customer->name,
                'photo' => $photo,
                'latitude' =>  $streetLighting->latitude,
                'longitude' => $streetLighting->longitude,
                'number_of_lamp' => $streetLighting->number_of_lamp,
                'survey_date' => $createdAt->toFormattedDateString()
            ];
            $data[] = $item;
        }
        
        return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'data' => $data], 200);
    }
    
    public function locationView(Request $request, $id=null) {
        if(is_null($id)) {
            return $this->location($request);
        }
        
        if(!is_null($id)) {
            $streetLighting = $this->getStreetLightingById($id);
        } 
        if(is_null($streetLighting)) {
            return response()->json([
                    'code' => 404,
                    'type' => 'NOT_FOUND',
                    'reason' => 'Resource not found',
                    'errors' => 'Street lighting not found',
                    'redirect' => '/streetlighting'], 404);
        }
        $createdAt = new Carbon($streetLighting->created_at);
        if(!is_null($streetLighting->photo)) {
            $photo = url($streetLighting->photo->path);
        } else {
            $photo = null;
        }
        $data = [
            'id' => $streetLighting->id,
            'customer_id' => $streetLighting->customer->id,
            'customer_code' => $streetLighting->customer->code,
            'customer_name' => $streetLighting->customer->name,
            'photo' => $photo,
            'latitude' =>  $streetLighting->latitude,
            'longitude' => $streetLighting->longitude,
            'number_of_lamp' => $streetLighting->number_of_lamp,
            'survey_date' => $createdAt->toFormattedDateString(),
            'lamps' => $streetLighting->lamps
        ];
        
        return response()->json([
                        'code' => 200,
                        'status' => 'OK',
                        'response' => 'Ok',
                        'data' => $data], 200);
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
                    'bank_fee' => trim($item[0]),
                    'ppn' => trim($item[11]),
                    'pju' => trim($item[12]),
                    'monthly_bill' => trim($item[13]),
                    'status' => 1,
                    'created_by' => $user
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
        if(array_key_exists('id', $params)) {
            $customer = $this->getCustomerById($params['id']);
        } else {
            $customer = new Customer();
        }

        $data = [];
        try {
            $customer->code = array_key_exists('code', $params) ? strtoupper($params['code']) : $customer->code;
            $customer->name = array_key_exists('name', $params) ? ucfirst(trim($params['name'])) : $customer->name;
            $customer->address = array_key_exists('address', $params) ? strtoupper(trim($params['address'])) : $customer->address;
            $customer->address2 = array_key_exists('address2', $params) ? strtoupper(trim($params['address2'])) : $customer->address2;
            $customer->address3 = array_key_exists('address3', $params) ? strtoupper(trim($params['address3'])) : $customer->address3;
            $customer->rate = array_key_exists('rate', $params) ? $params['rate'] : $customer->rate;
            $customer->power = array_key_exists('power', $params) ? $params['power'] : $customer->power;
            $customer->stand_start = array_key_exists('stand_start', $params) ? $params['stand_start'] : $customer->stand_start;
            $customer->stand_end = array_key_exists('stand_end', $params) ? $params['stand_end'] : $customer->stand_end;
            $customer->kwh = array_key_exists('kwh', $params) ? $params['kwh'] : $customer->kwh;
            $customer->ptl = array_key_exists('ptl', $params) ? $params['ptl'] : $customer->ptl;
            $customer->stamp = array_key_exists('stamp', $params) ? $params['stamp'] : $customer->stamp;
            $customer->ppn = array_key_exists('ppn', $params) ? $params['ppn'] : $customer->ppn;
            $customer->pju = array_key_exists('pju', $params) ? $params['pju'] : $customer->pju;
            $customer->monthly_bill = array_key_exists('monthly_bill', $params) ? $params['monthly_bill'] : $customer->monthly_bill;
            $customer->status = array_key_exists('status', $params) ? $params['status'] : $customer->status;
            $customer->created_by = array_key_exists('created_by', $params) ? $params['created_by'] : $customer->created_by;

            $customer->save();

            $data['data'] = $customer;
        } catch (Exception $ex) {
            $data['errors'] = $ex->getMessage();
        }
        return $data;
    } 

    /**
     * 
     * @param array $params
     * @return List of customer
     */
    private function getCustomers(array $params) {
        $customers = Customer::where('code', '!=', 'DUMMY')
                ->get();
        if(array_key_exists('code', $params) && $params['code'] != '') {
            $customers = $customers->where('code', $params);
        }
        if(array_key_exists('status', $params)) {
            $customers = $customers->where('status', (int) $params['status']);
        }

        return $customers->all();
    }
    
    /**
     * 
     * @param array $params
     * @return List of customer
     */
    private function getUnregisteredCustomers(array $params) {
        $customers = Customer::where('status', -1)
                ->get();
        if(array_key_exists('code', $params) && $params['code'] != '') {
            $customers = $customers->where('code', $params);
        }
        if(array_key_exists('status', $params)) {
            $customers = $customers->where('status', (int) $params['status']);
        }

        return $customers->all();
    }

    /**
     * 
     * @param string $id
     * @return Customer customer
     */
    private function getCustomerById($id) {
        $customer = Customer::where('id', $id)
                ->first();

        return $customer;
    }
    
    /**
     * 
     * @param array $params
     * @return type
     */
    private function getStreetLightings(array $params) {
        $streetLightings = StreetLighting::where('status', 1)
                ->get();
        
        return $streetLightings->all();
    }

    /**
     * 
     * @param array $params
     * @return type
     */
    private function getStreetLightingsByCustomer(array $params) {
        $customer = Customer::where('id', $params['customer'])
                ->orWhereRaw('UPPER(code) LIKE ?', array(strtoupper(trim($params['customer']))))
                ->orWhereRaw('LOWER(name) LIKE ?', array('%'.strtolower(trim($params['customer'].'%'))))
                ->first();
        
        $streetLightings = StreetLighting::with('customer')
                ->where('customer_id', $customer->id)
                ->where('status', 1)
                ->get();
        
        return $streetLightings->all();
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    private function getStreetLightingById($id) {
        $streetLighting = StreetLighting::with('lamps')
                ->where('id', $id)
                ->first();

        return $streetLighting;
    }
}
