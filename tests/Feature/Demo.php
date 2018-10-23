<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Demo extends TestCase
{
    /**
     * A basic test example.
     * @dataProvider dataTestAdd
     * @return void
     */
    use RefreshDatabase;
    public function testAdd($a, $b, $expected)
	{
		$c = $a.$b;
	    $this->assertEquals($expected,$c);
	}
	public function dataTestAdd(){
		return [
			['Bang','Te','BangTe97']
		];
	}
}
