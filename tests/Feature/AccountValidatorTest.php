<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AccountValidatorTest extends TestCase
{
    /**
     * 0. Check random accounts
     * @return void
     */
    public function testExample0()
    {
        $response = $this->get('/api/is-valid/070116/14139285');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
        $response = $this->get('/api/is-valid/800557/00325541');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 1. Pass modulus 10 check
     * @return void
     */
    public function testExample1()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/089999/66374958');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 2. Pass modulus 11 check
     * @return void
     */
    public function testExample2()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/107999/88837491');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 3. Pass modulus 11 and double alternate checks
     * @return void
     */
    public function testExample3()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/202959/63748472');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * 4. Exception 10 & 11 where first check passes and second check fails
     * @return void
     */
    public function testExample4()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/871427/46238510');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 5. Exception 10 & 11 where first check fails and second check passes
     * @return void
     */
    public function testExample5()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/872427/46238510');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * 6. Exception 10 where in the account number ab=09 and the g=9. The first check passes and second check fails
     * @return void
     */
    public function testExample6()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/871427/09123496');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 7. Exception 10 where in the account number ab=99 and the g=9. The first check passes and the second check fails
     * @return void
     */
    public function testExample7()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/871427/99123496');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 8. Exception 3, and the sorting code is the start of a range. As c=6 the second check should be ignored
     * @return void
     */
    public function testExample8()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/820000/73688637');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 9. Exception 3, and the sorting code is the end of a range. As c=9 the second check should be ignored
     * @return void
     */
    public function testExample9()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/827999/73988638');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 10. Exception 3. As c<>6 or 9 perform both checks pass
     * @return void
     */
    public function testExample10()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/827101/28748352');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * 11. Exception 4 where the remainder is equal to the checkdigit
     * @return void
     */
    public function testExample11()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/134020/63849203');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 12. Exception 1 – ensures that 27 has been added to the accumulated total and passes double alternate modulus check
     * @return void
     */
    public function testExample12()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/118765/64371389');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 13. Exception 6 where the account fails standard check but is a foreign currency account
     * @return void
     */
    public function testExample13()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/200915/41011166');
        $this->checkResult($response, 200, true, true, 0, 'Sort code is valid');
    }

    /**
     * 14. Exception 5 where the check passes
     * @return void
     */
    public function testExample14()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/938611/07806039');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * 15. Exception 5 where the check passes with substitution
     * @return void
     */
    public function testExample15()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/938600/42368003');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * 16. Exception 5 where both checks produce a remainder of 0 and pass
     * @return void
     */
    public function testExample16()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/938063/55065200');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * 17. Exception 7 where passes but would fail the standard check
     * @return void
     */
    public function testExample17()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/772798/99345694');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 18. Exception 8 where the check passes
     * @return void
     */
    public function testExample18()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/086090/06774744');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 19. Exception 2 & 9 where the first check passes
     * @return void
     */
    public function testExample19()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/309070/02355688');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 20. Exception 2 & 9 where the first check fails and second check passes with substitution
     *
     * @return void
     */
    public function testExample20()
    {
        //NB This test fails and it should not

        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/309070/12345668');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * 21. Exception 2 & 9 where a≠0 and g≠9 and passes
     * @return void
     */
    public function testExample21()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/309070/12345677');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 22. Exception 2 & 9 where a≠0 and g=9 and passes
     * @return void
     */
    public function testException2and9Override2Passes()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/309070/99345694');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 23. Exception 5 where the first check digit is correct and the second incorrect
     * @return void
     */
    public function testExample23()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/938063/15764273');
        $this->checkResult($response, 200, false, true, 2, 'The account number failed the modulus check');
    }

    /**
     * 24. Exception 5 where the first check digit is incorrect and the second correct
     * @return void
     */
    public function testExample24()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/938063/15764264');
        $this->checkResult($response, 200, false, true, 1, 'The account number failed the modulus check');
    }

    /**
     * 25. Exception 5 where the first check digit is incorrect with a remainder of 1
     * @return void
     */
    public function testExample25()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/938063/15763217');
        $this->checkResult($response, 200, false, true, 1, 'The account number failed the modulus check');
    }

    /**
     * 26. Exception 1 where it fails double alternate check
     * @return void
     */
    public function testExample26()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/118765/64371388');
        $this->checkResult($response, 200, false, true, 1, 'The account number failed the modulus check');
    }

    /**
     * 27. Pass modulus 11 check and fail double alternate check
     * @return void
     */
    public function testExample27()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/203099/66831036');
        $this->checkResult($response, 200, false, true, 2, 'The account number failed the modulus check');
    }

    /**
     * 28. Fail modulus 11 check and pass double alternate check, but both required
     * @return void
     */
    public function testExample28()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/203099/58716970');
        $this->checkResult($response, 200, false, true, 1, 'The account number failed the modulus check');
    }

    /**
     * 29. Fail modulus 10 check
     * @return void
     */
    public function testExample29()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/089999/66374959');
        $this->checkResult($response, 200, false, true, 1, 'The account number failed the modulus check');
    }

    /**
     * 30. Fail modulus 11 check
     * @return void
     */
    public function testExample30()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/107999/88837493');
        $this->checkResult($response, 200, false, true, 1, 'The account number failed the modulus check');
    }

    /**
     * 31. Exception 12/13 where passes modulus 11 check (in this example, modulus 10 check fails, however,
     * there is no need for it to be performed as the first check passed)
     * @return void
     */
    public function testExample31()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/074456/12345112');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 32. Exception 12/13 where passes the modulus 11 check (in this example, modulus 10 check passes
     * as well, however, there is no need for it to be performed as the first check passed)
     * @return void
     */
    public function testExample32()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/070116/34012583');
        $this->checkResult($response, 200, true, true, 1, 'Sort code is valid');
    }

    /**
     * 33. Exception 12/13 where fails the modulus 11 check, but passes the modulus 10 check
     * @return void
     */
    public function testExample33()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/074456/11104102');
        $this->checkResult($response, 200, true, true, 2, 'Sort code is valid');
    }

    /**
     * Check the result of a test
     *
     * @param TestResponse $response
     * @param int $statusCode
     * @param bool $isValid
     * @param bool $isEiscdSortCode
     * @param int $expectedNumberOfTests
     * @param string $message
     */
    private function checkResult($response, $statusCode, $isValid, $isEiscdSortCode, $expectedNumberOfTests, $message)
    {
        $response->assertStatus($statusCode);

        $content = json_decode($response->content(), true);

        $isValid ? $this->assertTrue($content['valid']) : $this->assertFalse($content['valid']);
        $isEiscdSortCode ? $this->assertTrue($content['eiscd-sortcode']) : $this->assertFalse($content['eiscd-sortcode']);
        $this->assertEquals($content['numberOfTests'], $expectedNumberOfTests);
        $this->assertEquals($content['message'], $message);
    }
}
