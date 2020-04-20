<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

use App\AccountValidatorManager;
use App\AccountValidators\AccountValidator;
use App\Weight;

class ParameterValidationTest extends TestCase
{
    /**
     * Validate sort code is 6 digits
     * @return void
     */
    public function testInvalidSortCode1()
    {
        $response = $this->get('/api/is-valid/07011/12224141');
        $this->checkResult($response, 200, AccountValidatorManager::SORT_CODE_INVALID_MESSAGE);
    }

    /**
     * Validate sort code is numeric
     * @return void
     */
    public function testInvalidSortCode2()
    {
        $response = $this->get('/api/is-valid/0701x1/12224141');
        $this->checkResult($response, 200, AccountValidatorManager::SORT_CODE_INVALID_MESSAGE);
    }

    /**
     * Validate sort code is not too long
     * @return void
     */
    public function testInvalidSortCode3()
    {
        $response = $this->get('/api/is-valid/0701122/14134141');
        $this->checkResult($response, 200, AccountValidatorManager::SORT_CODE_INVALID_MESSAGE);
    }
    /**
     * Validate sort code is found in the EISCD table
     * @return void
     */
    public function testInvalidSortCode4()
    {
        $response = $this->get('/api/is-valid/000999/12222141');
        $this->checkResult($response, 200, AccountValidatorManager::SORT_CODE_NOT_FOUND_MESSAGE);
    }

    /**
     * Validate account numbers is a minimum length of 6
     * @return void
     */
    public function testAccountNumber1()
    {
        $response = $this->get('/api/is-valid/070116/11283');
        $this->checkResult($response, 200, AccountValidatorManager::ACCOUNT_NUMBER_INVALID_MESSAGE);
    }

    /**
     * Validate account numbers is a maximum length of 10
     * @return void
     */
    public function testAccountNumber2()
    {
        $response = $this->get('/api/is-valid/070116/12345687671');
        $this->checkResult($response, 200, AccountValidatorManager::ACCOUNT_NUMBER_INVALID_MESSAGE);
    }

    /**
     * Validate account number is numeric
     * @return void
     */
    public function testAccountNumber3()
    {
        $response = $this->get('/api/is-valid/070116/1112355x');
        $this->checkResult($response, 200, AccountValidatorManager::ACCOUNT_NUMBER_INVALID_MESSAGE);
    }

    /**
     * Validate account number is valid at 8 digits
     * @return void
     */
    public function testAccountNumber4()
    {
        $response = $this->get('/api/is-valid/089999/66374958');
        $this->checkResult($response, 200, AccountValidatorManager::PASS_MESSAGE);
    }

    /**
     * Validate account number is valid at 6 digits, even if the test fails
     * @return void
     */
    public function testAccountNumber5()
    {
        $response = $this->get('/api/is-valid/089999/663749');
        $this->checkResult($response, 200, AccountValidatorManager::FAIL_MESSAGE);

        $content = json_decode($response->content(), true);

        $this->assertEquals('00663749', $content['calculation-account-number']);
    }

    /**
     * Validate account number is valid at 7 digits, even if the test fails
     * @return void
     */
    public function testAccountNumber6()
    {
        $response = $this->get('/api/is-valid/089999/6637497');
        $this->checkResult($response, 200, AccountValidatorManager::FAIL_MESSAGE);

        $content = json_decode($response->content(), true);

        $this->assertEquals('06637497', $content['calculation-account-number']);
    }

    /**
     * Validate account number is valid at 9 digits, even if the test fails
     * @return void
     */
    public function testAccountNumber7()
    {
        $response = $this->get('/api/is-valid/089999/663897497');
        $this->checkResult($response, 200, AccountValidatorManager::FAIL_MESSAGE);

        $content = json_decode($response->content(), true);

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
        $this->checkResult($response, 200, AccountValidatorManager::FAIL_MESSAGE);

        $content = json_decode($response->content(), true);

        $this->assertEquals('089999', $content['original-sortcode']);
        $this->assertEquals('089999', $content['calculation-sortcode']);
        $this->assertEquals('6638974970', $content['original-account-number']);
        $this->assertEquals('38974970', $content['calculation-account-number']);
    }

    /**
     * Validate account number where it is a non-standard account number of 10
     * @return void
     */
    public function testAccountNumber9()
    {
        $sortCode = '089999';
        // Ten digits
        $accountNumber = '6638974970';

        $accountValidator = new AccountValidator(new Weight(), [], $sortCode, $accountNumber);
        $result = $accountValidator->checkAccountNumber();
        $this->assertTrue($result);
        $this->assertEquals('38974970', $accountValidator->accountNumber);

        $accountValidator = new AccountValidator(new Weight(), [], $sortCode, $accountNumber);
        $result = $accountValidator->checkAccountNumber(false);
        $this->assertTrue($result);
        $this->assertEquals('66389749', $accountValidator->accountNumber);
    }

    /**
     * Validate account number where it is a non-standard account number of 6
     * @return void
     */
    public function testAccountNumber10()
    {
        $sortCode = '089999';
        // 6 digits
        $accountNumber = '663897';

        $accountValidator = new AccountValidator(new Weight(), [], $sortCode, $accountNumber);
        $result = $accountValidator->checkAccountNumber();
        $this->assertTrue($result);
        $this->assertEquals('00663897', $accountValidator->accountNumber);
    }

    /**
     * Validate account number where it is a non-standard account number of 7
     * @return void
     */
    public function testAccountNumber11()
    {
        $sortCode = '089999';
        // 7 digits
        $accountNumber = '6638974';

        $accountValidator = new AccountValidator(new Weight(), [], $sortCode, $accountNumber);
        $result = $accountValidator->checkAccountNumber();
        $this->assertTrue($result);
        $this->assertEquals('06638974', $accountValidator->accountNumber);
    }

    /**
     * Validate account number where it is a non-standard account number of 9
     * @return void
     */
    public function testAccountNumber12()
    {
        $sortCode = '089999';
        // 9 digits
        $accountNumber = '663897445';

        $accountValidator = new AccountValidator(new Weight(), [], $sortCode, $accountNumber);
        $result = $accountValidator->checkAccountNumber();
        $this->assertTrue($result);
        $this->assertEquals('089996', $accountValidator->sortCode);
        $this->assertEquals('63897445', $accountValidator->accountNumber);
    }

    /**
     * Check the result of a test
     *
     * @param TestResponse $response
     * @param int $statusCode
     * @param string $message
     */
    private function checkResult($response, $statusCode, $message)
    {
        $response->assertStatus($statusCode);

        $content = json_decode($response->content(), true);

        $this->assertEquals($message, $content['message']);
    }
}
