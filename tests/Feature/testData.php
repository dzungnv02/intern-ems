<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Student;

class testData extends TestCase
{	
	/**
     * @dataProvider additionProvider
     */
    public function testSearch($keyword, $expected)
    {	
    	$result = new Student();
    	$res = $result->search($keyword)->toArray();
        $this->assertTrue(in_array($expected,$res));
    }
    public function additionProvider()
    {
		return [
			['anh','anh']
		];
    }
}
