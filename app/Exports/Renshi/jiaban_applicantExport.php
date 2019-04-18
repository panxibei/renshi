<?php

namespace App\Exports\Renshi;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Support\Collection;

class jiaban_applicantExport implements FromCollection, WithStrictNullComparison
{
	
	public function __construct($data){
		$this->data = $data;
	}
	
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
		
        return new Collection($this->data);
		
        // $cellData = [
            // ['学号','姓名','成绩'],
            // ['101','AAAAA', $this->id],
            // ['102','BBBBB','92'],
            // ['103','CCCCC','95'],
            // ['104','DDDDD','89'],
            // ['105','EEEEE','96'],
        // ];
		
        // return new Collection($cellData);
    }
}
