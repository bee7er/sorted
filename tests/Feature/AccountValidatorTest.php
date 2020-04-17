<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountValidatorTest extends TestCase
{
//    /**
//     * 1. Pass modulus 10 check
//     * @return void
//     */
//    public function testExample1()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/089999/66374958');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }
//
//    /**
//     * 2. Pass modulus 11 check
//     * @return void
//     */
//    public function testExample2()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/107999/88837491');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }
//
//    /**
//     * 3. Pass modulus 11 and double alternate checks
//     * @return void
//     */
//    public function testExample3()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/202959/63748472');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

//    /**
//     * 8. Exception 3, and the sorting code is the start of a range. As c=6 the second check should be ignored
//     * @return void
//     */
//    public function testExample8()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/820000/73688637');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        // The combination should pass and there should only be one test
//        $this->assertEquals($content['numberOfTests'], 1);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

    /**
     * 11. Exception 4 where the remainder is equal to the checkdigit
     * @return void
     */
    public function testExample11()
    {
        // A valid sort code / account number combination
        $response = $this->get('/api/is-valid/134020/63849203');

        $response->assertStatus(200);

        $content = json_decode($response->content(), true);

        $this->assertTrue($content['valid']);
        $this->assertTrue($content['eiscd-sortcode']);
        $this->assertEquals($content['message'], "Sort code is valid");
    }

//    /**
//     * 12. Exception 1 – ensures that 27 has been added to the accumulated total and passes double alternate modulus check
//     * @return void
//     */
//    public function testExample12()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/118765/64371389');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

//    /**
//     * 19. Exception 2 & 9 where the first check passes
//     * @return void
//     */
//    public function testExample19()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/309070/02355688');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        // The combination should pass and there should only be one test
//        $this->assertEquals($content['numberOfTests'], 1);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

    /**
     * 20. Exception 2 & 9 where the first check fails and second check passes with substitution
     *
     * @return void
     */
//    public function testExample20()
//    {
//        //NB This test fails and it should not
//
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/309070/12345668');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        // The combination should pass and there should have been two tests
//        $this->assertEquals($content['numberOfTests'], 2);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

    /**
     * 21. Exception 2 & 9 where a≠0 and g≠9 and passes
     * @return void
     */
//    public function testExample21()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/309070/12345677');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

    /**
     * 22. Exception 2 & 9 where a≠0 and g=9 and passes
     * @return void
     */
//    public function testException2and9Override2Passes()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/309070/99345694');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

    /**
     * 26. Exception 1 where it fails double alternate check
     * @return void
     */
//    public function testExample26()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/118765/64371388');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertFalse($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "The account number failed the modulus check");
//    }

//    /**
//     * 31. Exception 12/13 where passes modulus 11 check (in this example, modulus 10 check fails, however,
//     * there is no need for it to be performed as the first check passed)
//     * @return void
//     */
//    public function testExample31()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/074456/12345112');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }

//    /**
//     * 32. Exception 12/13 where passes the modulus 11 check (in this example, modulus 10 check passes
//     * as well, however, there is no need for it to be performed as the first check passed)
//     * @return void
//     */
//    public function testExample32()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/070116/34012583');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }
//
//    /**
//     * 33. Exception 12/13 where fails the modulus 11 check, but passes the modulus 10 check
//     * @return void
//     */
//    public function testExample33()
//    {
//        // A valid sort code / account number combination
//        $response = $this->get('/api/is-valid/074456/11104102');
//
//        $response->assertStatus(200);
//
//        $content = json_decode($response->content(), true);
//
//        $this->assertTrue($content['valid']);
//        $this->assertTrue($content['eiscd-sortcode']);
//        // The combination should pass and there should have been two tests
//        $this->assertEquals($content['numberOfTests'], 2);
//        $this->assertEquals($content['message'], "Sort code is valid");
//    }
}
