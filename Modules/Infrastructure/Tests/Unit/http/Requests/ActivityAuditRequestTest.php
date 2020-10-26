<?php

namespace Modules\Infrastructure\Tests\Unit\http\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory;
use Modules\Infrastructure\Http\Requests\ActivityAuditRequest;
use Tests\TestCase;


class CreateTokenRequestTest extends TestCase
{
    use RefreshDatabase;
    /** @var \Modules\Infrastructure\Http\Requests\ActivityAuditRequest */
    private $rules;

    /** @var \Illuminate\Validation\Validator */
    private $validator;

    protected $factoryCreatedUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = app()->get('validator');

        $this->rules = (new ActivityAuditRequest())->rules();
    }

    public function validationProviderforfail()
    {
        /* WithFaker trait doesn't work in the dataProvider */
        $faker = Factory::create(Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_mail_is_provided' => [
                'data' => [
                    'activity' => $faker->word
                ]
            ],
            'request_should_fail_when_no_activity_is_provided' => [
                'data' => [
                    'user' => $faker->email
                ]
            ],
            'request_should_fail_when_email_not_exist_in_database' => [
                'data' => [
                    'user' => $faker->email,
                    'activity' => $faker->word
                ]
            ],
            'request_should_fail_when_email_is_invalid_format' => [
                'data' => [
                    'user' => $faker->word,
                    'activity' => $faker->word
                ]
            ]
        ];
    }

    /**
     * @dataProvider validationProviderforfail
     * @param array $mockedRequestData
     */
    public function test_validation_results_will_fail($mockedRequestData)
    {
        $this->assertEquals(false, $this->validate($mockedRequestData));
    }
    public function test_validation_results_will_success_when_mail_is_exist()
    {
        $this->factoryCreatedUser = factory(\Modules\User\Entities\User::class)->create([
            "role_id" => 2
        ]);
        $mockedRequestData = [
                'user' => $this->factoryCreatedUser->email,
                'activity' => "signIn"
        ];
        $this->assertEquals(true, $this->validate($mockedRequestData));
    }

    protected function validate($mockedRequestData)
    {
        return $this->validator
            ->make($mockedRequestData, $this->rules)
            ->passes();
    }
}
