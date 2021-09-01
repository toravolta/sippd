<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Member;
use App\FuelCardTran;

class TransactionController extends Controller
{

    public function getListTrx(Request $request)
    {
        try {
            $filter = $request->query();

            $Query = FuelCardTran::leftJoin('fc_member_data', function ($join) {
                    $join->on('fc_member_data.fc_number', '=', 'fc_transaction_data.fc_number');
                })->where('fc_member_data.client_id', $filter['cid']);
            // Police NUmber
            if (isset($filter['police_number'])) {
                $Query = $Query->where('fc_member_data.police_number', $filter['police_number']);
            }
            // fc_number
            if (isset($filter['fc_number'])) {
                $Query = $Query->where('fc_member_data.fc_number', $filter['fc_number']);
            }
            // trx_date
            if (isset($filter['start_date'])) {
                $start = date('Y-m-d H:i:s', strtotime($filter['start_date'] . ' 00:00:00'));
                $end = date('Y-m-d H:i:s', strtotime($filter['start_date'] . ' 23:56:56'));
                if (isset($filter['end_date'])) {
                    $end = date('Y-m-d H:i:s', strtotime($filter['end_date'] . ' 23:56:56'));
                }
                $Query = $Query->whereBetween('fc_transaction_data.trx_date', [$start, $end]);
            }
            $data = $Query->get();
            if ($data->first()) {
                $Result['rc'] = '0000';
                $Result['msg'] = 'success';
                foreach ($data as $key => $value) {
                    $Result[] = [
                        'reff_num' => $value->reff_num,
                        'fuelcard' => $value->fc_number,
                        "police_number" => $value->members['police_number'],
                        "tire" => $value->members['tire'],
                        "quota" => $value->members['quota'],
                        "spbu_name" => $value->spbu_name,
                        "trx_date" => $value->trx_date,
                        "amount" => $value->amount,
                        "volume" => $value->volume,
                        "fuel_type" => $value->fuel_type,
                    ];
                }
                return response()->json($Result, 200);
            }
            return response()->json(['rc' => '0014', 'msg' => 'Data not found!'], 200);
        } catch (\Exception $e) {
            return response()->json(['rc' => '0005', 'msg' => 'error something please contact helpdesk'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
