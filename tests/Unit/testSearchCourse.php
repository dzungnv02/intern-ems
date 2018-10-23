<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Courses;

class testSearchCourse extends TestCase
{
	// use RefreshDatabase;
    /**
     * A basic test example.
     * @dataProvider additionProvider
     * @return void
     */
    // public function testExample()
    // {
    //     $this->assertTrue(true);
    // }
    public function testSearchCourse($keyword,$resultExpected)
    {

   //  	$courseDataSample = factory(Courses::class,1)->create(
   //  		[
   //           'id'=>2,
	  //   		'name'=>'Lap trinh NodeJS',
		 //    	'code'=>'NJS002',
		 //    	'duration'=>60,
		 //    	'fee'=>2000000,
		 //    	'curriculum'=>'Tai lieu NodeJS',
		 //    	'level'=>2,
	  //   	]
		 // );
    	$course = new Courses();
    	$resultSearch = $course->getResultSearch($keyword)->toArray();
    	// dd($resultSearch['data']);
 
    	// dd($arrays);
    	// dd($resultExpected);
	    $this->assertEquals($resultSearch['data'],$resultExpected);
  		
    }
    public function additionProvider()
    {
    	return [
    		[
    			'Laravel',
	    		[
	    			'id'=>1,
		    		'name'=>'Lap trinh Laravel',
			    	'code'=>'LR002',
			    	'duration'=>50,
			    	'fee'=>1000000,
			    	'curriculum'=>'Tai lieu Laravel',
			    	'level'=>2,
			    	'create_at'=> '2018-08-29 08:09:40',
			    	'update_at'=> '2018-08-29 08:09:40'
	    		]
	    	]
    	];
    }
}


 //   	foreach($resultSearch as $object)
		// {
		//     $arrays[] = $object->toArray();