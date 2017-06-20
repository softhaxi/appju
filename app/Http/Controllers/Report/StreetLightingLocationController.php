<?php

namespace APPJU\Http\Controllers\Report;

use Carbon\Carbon;

use Illuminate\Http\Request;

use APPJU\Http\Requests;
use APPJU\Http\Controllers\Controller;

use APPJU\Models\Master\Customer;
use APPJU\Models\Detail\StreetLighting;
use APPJU\Models\Detail\StreetLightingLamp as Lamp;

use FPDF;

class StreetLightingLocationController extends Controller {
    
    public function view(Request $request) {
        
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function download(Request $request) {
        $pdf = new FPDF('L', 'pt', 'A4');
        $pdf->AliasNbPages();
        $pdf->SetAutoPageBreak(true, 40);
        $customers = $this->getCustomers($request->all());
        foreach($customers as $customer) {
            $pdf->AddPage();
            if($pdf->PageNo() == 1) {
                $pdf->SetFont('Times', 'I', 8);
                $pdf->Cell(0, 13, 'Printed By: Raja Yudha Pratama Sihombing', 0, 1, 'R');
                $pdf->Cell(0, 13, 'Printed at: ' . date('F j, Y'), 0, 1, 'R');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 20);
                $pdf->Cell(0, 30, trans('report.street_lighting_location'), 0, 2, 'C');   
                $pdf->Ln(40);
            }
            $markers = '';
            $streetLightings = $this->getStreetLightingsByCustomer($customer->id);
            $pdf->SetFont('Times', 'B', 11);
            $pdf->Cell(60, 13, trans('form.customer'));
            if($customer->code != 'DUMMY') {
                $pdf->SetFont('Times', '', 11);
                $pdf->Cell(150, 13, $customer->code);
            } else {
                $pdf->SetFont('Times', 'I', 11);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->Cell(150, 13, strtoupper(trans('form.unregistered_customer')));
                $pdf->SetTextColor(0);
                $pdf->SetFont('Times', '', 11);
            }
            $pdf->Ln();
            $pdf->SetX(89); 
            $pdf->Cell(100, 13, $customer->name, 0, 1);
            $pdf->SetX(89);
            $address = $customer->address;
            if($customer->address2 != '') {
                $address .= ' ' . $customer->address2;
            } 
            if($customer->address3 != '') {
                $address .= ' ' . $customer->address3;
            }
            $pdf->Cell(100, 13, $address, 0, 2);
            $pdf->Ln(1);
            $pdf->SetFont('Times', 'B', 11);
            $pdf->Cell(60, 13, 'Total');
            $pdf->SetFont('Times', '', 11);
            $pdf->Cell(100, 13, count($streetLightings) . ' Street lighting(s)');
            $pdf->Ln(25);
            // Table
            $pdf->SetFillColor(255,0,0);
            $pdf->SetTextColor(255);
            $pdf->SetDrawColor(255, 255, 255);
            $pdf->SetLineWidth(.2);
            $pdf->SetFont('Times','B', 11);
            $pdf->Cell(27, 40, 'No.', 'LTR', 0, 'C', true);
            $pdf->Cell(120, 40, 'Survey Date', 'LTR', 0, 'C', true);
            $pdf->Cell(90, 40, 'Latitude', 'LTR', 0, 'C', true);
            $pdf->Cell(90, 40, 'Langitude', 'LTR', 0, 'C', true);
            $pdf->Cell(450, 20, 'Street Lighting Lamp', 'LTR', 0, 'C', true);
            $pdf->Ln(20);
            $pdf->SetX(355);
            $pdf->Cell(100, 20, 'Code', 'LTR', 0, 'C', true);
            $pdf->Cell(100, 20, 'Type', 'LTR', 0, 'C', true);
            $pdf->Cell(50, 20, 'Power', 'LTR', 0, 'C', true);
            $pdf->Cell(200, 20, 'Description', 'LTR', 0, 'C', true);
            $pdf->Ln(25);
            $pdf->SetFillColor(224,235,255);
            $pdf->SetTextColor(0);
            $pdf->SetFont('Times','', 11);
            $fill = false;
            $i = 1;
            $j = 1;
            foreach($streetLightings as $streetLighting) {
                $markers .= '&markers=label:' . $i . '|' . $streetLighting->latitude . ',' . $streetLighting->longitude;
                $pdf->Cell(27,6,$i,'LR',0,'L');
                $carbon = new Carbon($streetLighting->created_at);
                $pdf->Cell(120,6,$carbon->format('d/m/Y'),'LR',0,'L');
                $pdf->Cell(90,6,$streetLighting->latitude,'LR',0,'R');
                $pdf->Cell(90,6,$streetLighting->longitude,'LR',0,'R');
                $lamps = $this->getLampsByStreetLighting($streetLighting->id);
                foreach($lamps as $lamp) {
                    if($j > 1) {
                        $pdf->setX(355);
                    }
                    $pdf->Cell(100,6,$lamp->code,'LR',0,'L');
                    $pdf->Cell(100,6,$lamp->type,'LR',0,'L');
                    $pdf->Cell(50,6,number_format($lamp->power),'LR',0,'R');
                    $pdf->Cell(200,6,$lamp->remark,'LR',0,'L');
                    $pdf->Ln(15);
                    $j++;
                }
                $i++;
                $j = 1;
                $fill = !$fill;
            }
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetLineWidth(.2);
            $pdf->Cell(777,0,'','T');
            
            $pdf->AddPage();
            $pdf->SetFont('Times', 'B', 11);
            $pdf->Cell(40, 13, 'Map Visualization');
            $pdf->Ln(25);
            $pdf->Image('https://maps.googleapis.com/maps/api/staticmap?key=AIzaSyAFIGWd-ZciDro507btwASHQi-sasDBvBo&size=500x300&scale=2' . $markers,
                    45,$pdf->GetY(),0,0,'PNG');
        }
        
        $pdf->Output('D', 'SLLR'. date('Ymd') .'.pdf');
    }
    
    /**
     * 
     * @return type
     */
    private function getCustomers(array $params) {
        $customers = Customer::orderBy('created_at', 'asc')
                ->get();
        
        return $customers;
    }
    
    /**
     * 
     * @param type $customer
     * @return type
     */
    private function getStreetLightingsByCustomer($customer) {
        $streetLightings = StreetLighting::where('customer_id', $customer)
                ->where('status', 1)
                ->orderBy('status', 'desc')
                ->orderBy('created_at', 'asc')
                ->get();
        
        return $streetLightings;
    }
    
    /**
     * 
     * @param type $streetLighting
     * @return type
     */
    private function getLampsByStreetLighting($streetLighting) {
        $lamps = Lamp::where('street_lighting_id', $streetLighting)
                ->where('status', 1)
                ->orderBy('created_at', 'asc')
                ->get();
        
        return $lamps;
    }
}