<?php

namespace App\Imports;

use App\Card;
use App\CardLedger;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CardImport implements ToModel, WithHeadingRow
{
    private $rows = 0;
    public $ledgerid;
    private $bundle;

    public function __construct()
    {
        $ledger         = CardLedger::create(['card_in' => '0']);
        $this->ledgerid = $ledger->id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $fc_number = str_replace(' ', '', $row['nomor_kartu_brizzi_fuel_card']);
        if ($this->rows % 100 == 0) {
            $this->bundle = $fc_number;
        }
//        dd($this->rows.' '.$bundle);
        ++$this->rows;
        return new Card([
            'fc_number' => $fc_number,
            'status' => '1',
            'bundle_id' => $this->bundle,
            'info' => 'in stock',
            'ledger_in_id' => $this->ledgerid
        ]);
    }

    public function rowsCount()
    {
        return $this->rows;
    }

    public function updateLegder()
    {
        $stock     = CardLedger::where('card_available', '>', 0)->latest()->first();
        $stokAkhir = $stock->card_available + $this->rows;
        CardLedger::where('id', $this->ledgerid)->update(['card_in' => $this->rows,
            'card_available' => $stokAkhir]);
    }
}