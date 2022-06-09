<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;


class MasterAndCa implements FromArray, WithHeadings//,WithMapping

{
      protected $masterSheet;
      protected $subjects_headers;
      protected $subjectCount;

    public function __construct(array $masterSheet, array $subjects_headers,$subjectCount)
    {
        $this->masterSheet = $masterSheet;
       $this->subjects_headers = $subjects_headers;
       $this->subjectCount=$subjectCount;
    }
    public function array():array
    {
        return $this->masterSheet;
    }

    // public function map($masterSheet):array
    // {


    //     return
    //         $this->masterSheet;


    // }


    public function headings(): array

    {

            $headers=collect(['S/N'=>'S/N','STUDENT ID'=>'STUDENT ID','NAME'=>'NAMES','ARM'=>'ARM']);
            for($i=0;$i<$this->subjectCount;++$i){
              $headers=$headers->put($i.'t1','CA1');
              $headers=$headers->put($i.'t2','CA2');
              //$headers=$headers->put($i.'t3','CA3');
              $headers=$headers->put($i.'Exam','Exam');

            }

        return[ $this->subjects_headers,
                  $headers->toArray()

    ];
        //$collection=collect($this->masterSheet);
        // return [
        //    ['Subjects', ' ','Mathematics','','','English Language'],
        //    ['StudentId','Names','1st Term', '2nd Term','3rd Term','1st Term','2nd Term','3rd Term'],
        // ];
    }
}
