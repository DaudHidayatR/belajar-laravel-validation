<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testValidator(): void
    {
        $data = [
            'username' => 'daud',
            'password' => '123456',
        ];
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        self::assertTrue($validator->passes());
        self::assertFalse($validator->fails());
    }
    public function testValidatorInvalid(): void
    {
        $data = [
            'username' => '',
            'password' => '',
        ];
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());
    }
    public function testValidatorErrorMessage(): void
    {
        $data = [
            'username' => '',
            'password' => '',
        ];
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }
}
