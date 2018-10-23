<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Examination;



class ExamTest extends TestCase
{
    /**
      *@dataProvider additionProvider 
     */
    // /**
    //  * A basic test example.
    //  *
    //  * @return void
    //  */
    
    // public function testExample()
    // {
    //     $this->assertEquals();
    // }
    public function testSearch($keyword,$rec_per_page,$expected)
    {
        //$result= new Examination();
     //   $expected = ;
        $search = Examination::search($keyword,$rec_per_page);
       // dd($search['data']);
        $this->assertEquals($search['data'],$expected);
    }
    public function additionProvider()
    {
        return [
            ['hoc ky 1',1, [
                0 => [  "id" => 12,
                   "name"=> "hoc ky 10",
                   "start_day"=> "2018-08-30 00:00:00",
                   "duration"=> 100,
                   "note"=> "no",
                   "class_id"=> 2,
                   "created_at" => null,
                   "updated_at" => null,
                    ]
            ]   ],
            ['hoc ky 10',1, [
                0 => [  "id" => 12,
                   "name"=> "hoc ky 10",
                   "start_day"=> "2018-08-30 00:00:00",
                   "duration"=> 100,
                   "note"=> "no",
                   "class_id"=> 2,
                   "created_at" => null,
                   "updated_at" => null,
                    ]
            ]   ],
            ['hoc ky 9', 2,[
                0 => [  "id" => 3,
                   "name"=> "hoc ky 9",
                   "start_day"=> "2018-08-30 00:00:00",
                   "duration"=> 100,
                   "note"=> "no",
                   "class_id"=> 2,
                   "created_at" => null,
                   "updated_at" => null,
                ],
                1 => [  "id" => 4,
                    "name"=> "hoc ky 9",
                    "start_day"=> "2018-08-30 00:00:00",
                    "duration"=> 100,
                    "note"=> "no",
                    "class_id"=> 2,
                    "created_at" => null,
                    "updated_at" => null,
                        ]
                    
            ]   ],
            
           
        ];
    }
}
