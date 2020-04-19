<?php

namespace Tests\Feature;

use App\AccountValidators\AccountValidator;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ParameterValidationTest extends TestCase
{
    /**
     * Validate sort code is 6 digits
     * @return void
     */
    public function testInvalidSortCode1()
    {
        $response = $this->get('/api/is-valid/07011/12224141');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::SORT_CODE_INVALID_MESSAGE, $content['message']);
    }

    /**
     * Validate sort code is numeric
     * @return void
     */
    public function testInvalidSortCode2()
    {
        $response = $this->get('/api/is-valid/0701x1/12224141');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::SORT_CODE_INVALID_MESSAGE, $content['message']);
    }

    /**
     * Validate sort code is not too long
     * @return void
     */
    public function testInvalidSortCode3()
    {
        $response = $this->get('/api/is-valid/0701122/14134141');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::SORT_CODE_INVALID_MESSAGE, $content['message']);
    }
    /**
     * Validate sort code is found in the EISCD table
     * @return void
     */
    public function testInvalidSortCode4()
    {
        $response = $this->get('/api/is-valid/000999/12222141');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::SORT_CODE_NOT_FOUND_MESSAGE, $content['message']);
    }

    /**
     * Validate account numbers is a minimum length of 6
     * @return void
     */
    public function testAccountNumber1()
    {
        $response = $this->get('/api/is-valid/070116/11283');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::ACCOUNT_NUMBER_INVALID_MESSAGE, $content['message']);
    }

    /**
     * Validate account numbers is a maximum length of 10
     * @return void
     */
    public function testAccountNumber2()
    {
        $response = $this->get('/api/is-valid/070116/12345687671');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::ACCOUNT_NUMBER_INVALID_MESSAGE, $content['message']);
    }

    /**
     * Validate account number is numeric
     * @return void
     */
    public function testAccountNumber3()
    {
        $response = $this->get('/api/is-valid/070116/1112355x');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::ACCOUNT_NUMBER_INVALID_MESSAGE, $content['message']);
    }

    /**
     * Validate account number is valid at 8 digits
     * @return void
     */
    public function testAccountNumber4()
    {
        $response = $this->get('/api/is-valid/089999/66374958');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::PASS_MESSAGE, $content['message']);
    }

    /**
     * Validate account number is valid at 6 digits, even if the test fails
     * @return void
     */
    public function testAccountNumber5()
    {
        $response = $this->get('/api/is-valid/089999/663749');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::FAIL_MESSAGE, $content['message']);
        $this->assertEquals('00663749', $content['calculation-account-number']);
    }

    /**
     * Validate account number is valid at 7 digits, even if the test fails
     * @return void
     */
    public function testAccountNumber6()
    {
        $response = $this->get('/api/is-valid/089999/6637497');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::FAIL_MESSAGE, $content['message']);
        $this->assertEquals('06637497', $content['calculation-account-number']);
    }

    /**
     * Validate account number is valid at 9 digits, even if the test fails
     * @return void
     */
    public function testAccountNumber7()
    {
        $response = $this->get('/api/is-valid/089999/663897497');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::FAIL_MESSAGE, $content['message']);
        $this->assertEquals('089999', $content['original-sortcode']);
        $this->assertEquals('089996', $content['calculation-sortcode']);
        $this->assertEquals('663897497', $content['original-account-number']);
        $this->assertEquals('63897497', $content['calculation-account-number']);
    }

    /**
     * Validate account number is valid at 10 digits, even if the test fails
     * @return void
     */
    public function testAccountNumber8()
    {
        $response = $this->get('/api/is-valid/089999/6638974970');
        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertEquals(AccountValidator::FAIL_MESSAGE, $content['message']);
        $this->assertEquals('089999', $content['original-sortcode']);
        $this->assertEquals('089999', $content['calculation-sortcode']);
        $this->assertEquals('6638974970', $content['original-account-number']);
        $this->assertEquals('38974970', $content['calculation-account-number']);
    }
}
