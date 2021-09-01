<?php

namespace App\Console\Commands;

use App\FuelCardTran;
use App\FuelPrice;
use Illuminate\Console\Command;
use App\AlertTransaction as Suspect;

class CsvImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV from BRI to DB fuelCard transaction';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $price   = FuelPrice::all();
        $aMember = \App\Member::pluck('fc_number')->toArray();
        $Quota   = \App\Member::pluck('quota', 'fc_number')->toArray();
        $solar   = $bensin  = $premium = $petamax = 0;
        foreach ($price as $key => $value) {
            if ($value->fuel_type == 'Solar') {
                $solar = $value->price;
            }
//            ${$value->fuel_type} = $value->price;
        }
//        var_dump($solar);

        $path = '/tmp/fuelcard/REPORT_BRIZZI_PERTAMINA'.date('dmY').'.csv';
        if (file_exists($path)) {
            $file        = new \SplFileObject($path);
            $file->setFlags(
                \SplFileObject::DROP_NEW_LINE |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::READ_CSV
            );
            $it          = new \LimitIterator($file, 1);
            $numberTrace = 1;
            $volume      = 0;
            foreach ($it as $key => $value) {
                //Textbox10,RGDESC,MBDESC,nama_merchant,MID,TID,Cardno1,Tgl_Stl,
                //Tgl_trans,Amount1,Status_Settlement1,KETERANGAN,Proccode,RefNo1
                list($no, $rgdesc, $mbdesc, $spbu, $mid, $tid, $fcNumber, $settleDate,
                    $trxDate, $amount, $settleStatus, $fuelType, $procode, $reffNum)
                    = $value;
//                print_r('NUmber : ' . $numberTrace . PHP_EOL);
                if (strstr(strtolower($fuelType), 'solar')) {
                    $rp     = str_replace(',', '', substr($amount, 0, -3));
                    $volume = floor(intval($rp) / intval($solar));
                }
//                var_dump($volume);
                $tranId = uniqid();
                FuelCardTran::create([
                    'transaction_id' => $tranId,
                    'fc_number' => $fcNumber,
                    'tid' => $tid,
                    'mid' => $mid,
                    'spbu_name' => $spbu,
                    'trx_date' => $trxDate,
                    'settle_date' => $settleDate,
                    'amount' => str_replace(',', '', substr($amount, 0, -3)),
                    'settlement_status' => $settleStatus,
                    'reff_num' => $reffNum,
                    'fuel_type' => $fuelType,
                    'rg_desc' => $rgdesc,
                    'mb_desc' => $mbdesc,
                    'volume' => $volume
                ]);
                if (!in_array($fcNumber, $aMember)) {
                    Suspect::create([
                        'fc_number' => $fcNumber,
                        'transact_id' => $tranId,
                        'trx_date' => $trxDate,
                        'desc' => 'Kartu tidak terdaftar Di Member '
                    ]);
                } else if ($volume > $Quota[$fcNumber]) {
                    Suspect::create([
                        'fc_number' => $fcNumber,
                        'transact_id' => $tranId,
                        'trx_date' => $trxDate,
                        'desc' => 'Transaksi Over Quota'
                    ]);
                }
                $numberTrace++;
//                print_r($numberTrace);
            }
        } else {
            print_r('File Not Found');
        }
        print_r('Import Done');
    }
}