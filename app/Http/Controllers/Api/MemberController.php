<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class MemberController extends Controller
{

    public function saveMember(Request $request)
    {
        if (empty($request->json()->all())) {
            return response()->json(['rc' => '14', 'msg' => 'INVALID FORMAT REQUEST'], 400);
        }
        $Company = new \App\Company();
        $Member = new \App\Member();
        $jsonData = $request->json()->all();
        $dCompany = [];
        $aCompany = [];
        $aVehicle = [];
        $aResp = [];
        $success = 0;
        $failed = 0;
        if (!empty($jsonData['data'])) {
            foreach ($jsonData['data'] as $key => $value) {
                if (!in_array($value['company_id'], $dCompany)) {
                    $eCompany = \App\Company::where('company_id', '=', $value['company_id']);
                    if (!$eCompany) {
                        $aCompany[] = [
                            'company_id' => $value['company_id'],
                            'company_name' => $value['company_name'],
                            'company_address' => $value['company_address']
                        ];
                    }
                    $dCompany[] = $value['company_id'];
                }
                $eVehicle = \App\Member::where('fc_number', '=', $value['fc_number'])
                        ->orWhere('police_number', '=', $value['police_no'])->first();
                if (!$eVehicle) {
                    $rVehicle = \App\Member::insertOrIgnore([
                            'fc_number' => $value['fc_number'],
                            'company_id' => $value['company_id'],
                            'police_number' => $value['police_no'],
                            'tire' => $value['tire'],
                            'quota' => $value['quota'],
                            'created_at' => date('YmdHis')
                    ]);
                    if ($rVehicle) {
                        $status = 'success';
                        $msg = 'success';
                        $success++;
                    } else {
                        $status = 'failed';
                        $msg = 'Failed Insert';
                        $failed++;
                    }
                } else {
                    $status = 'failed';
                    $msg = 'Duplicate Police or card number';
                    $failed++;
                }
                $value['status'] = $status;
                $value['msg'] = $msg;
                $aResp[] = $value;
            }
        }
        if ($success > 0) {
            $aResult['rc'] = '00';
            $aResult['success'] = $success;
            $aResult['failed'] = $failed;
            $aResult['data'] = $aResp;
            return response()->json($aResult, 200);
        }

        return response()->json(['rc' => '05', 'msg' => 'insert Failed'], 417);
    }
}
