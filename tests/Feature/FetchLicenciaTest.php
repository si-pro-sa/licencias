<?php

namespace tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchLicenciaTest extends TestCase
{
    //use RefreshDatabase;


    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        config(['system.per_page' => [1, 2, 3]]);
        config(['system.default_per_page' => [1]]);
    }


    /**
     * @test
     */
    public function validation_fails_with_empty_request()
    {
        $response = $this->getJson(route('product.fetch'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'errors' => [
                'search' => [__('validation.present', ['attribute' => 'search'])],
                'order_by' => [__('validation.required', ['attribute' => 'order by'])],
                'per_page' => [__('validation.required', ['attribute' => 'per page'])],
                'page' => [__('validation.required', ['attribute' => 'page'])],

            ],
            'message' => 'the given data was invalid.'
        ]);
    }

}
