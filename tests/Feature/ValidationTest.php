<?php

namespace Tests\Feature;


use App\Rules\RegistrationRule;
use App\Rules\Uppercase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use function Laravel\Prompts\error;
use function PHPUnit\Framework\assertTrue;

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
    public function testValidationException(): void
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

        try {
            $validator->validate();
            self::fail('ValidationException not thrown');

        }catch (ValidationException $exception)
        {
           self::assertNotNull($exception->validator);
           $message = $exception->validator->errors();
           Log::error($message->toJson(JSON_PRETTY_PRINT));
        }


    }
    public function testValidatorMultipleRule(): void
    {
        $data = [
            'username' => 'daud',
            'password' => 'daud',
        ];
        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:20'],
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }
    public function testValidationValidData(): void
    {
        $data = [
            'username' => 'daud28ramadhan@gmail.com',
            'password' => '122334445556',
            'admin' => true,
        ];
        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:20'],
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        try {
            $valid = $validator->validate();
            Log::info(json_encode($valid, JSON_PRETTY_PRINT));

        }catch (ValidationException $exception)
        {
            self::assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        }
    }

    public function testValidatorInlineMessage(): void
    {

        $data = [
            'username' => 'daud',
            'password' => 'daud',
        ];
        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:20'],
        ];
        $massage = [
            'required' => ':attribute harus diisi',
            'email' => ':attribute harus berupa email',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min karakter',

        ];
        $validator = Validator::make($data, $rules, $massage);
        self::assertNotNull($validator);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }
    public function testValidatorAdditionalValidation(): void
    {
        $data = [
            'username' => 'daud28ramadhan@gmail.com',
            'password' => 'daud28ramadhan@gmail.com',
        ];
        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:20'],
        ];
        $validator = Validator::make($data, $rules);
        $validator->after(function (\Illuminate\Validation\Validator $validator) {
            $data = $validator->getData();
            if ($data['username'] === $data['password']) {
                $validator->errors()->add('password', 'Password tidak boleh sama dengan username');
            }
        });
        self::assertNotNull($validator);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }
    public function testValidatorCustomRole(): void
    {
        $data = [
            'username' => 'daud28ramadhan@gmail.com',
            'password' => 'daud28ramadhan@gmail.com',
        ];
        $rules = [
            'username' => ['required','email','max:100',new Uppercase()],
            'password' => ['required', 'min:6', 'max:20', new RegistrationRule()],
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }
    public function testValidatorCustomFunctionRule(): void
    {
        $data = [
            'username' => 'daud28ramadhan@gmail.com',
            'password' => 'daud28ramadhan@gmail.com',
        ];
        $rules = [
            'username' => ['required','email','max:100',function(string $attribute, string $value, \Closure $fail){
                if (strtoupper($value) !== $value) {
                   $fail('the :attribute must be uppercase');
                }
            }],
            'password' => ['required', 'min:6', 'max:20', new RegistrationRule()],
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }
    public function testValidatorRuleClasses(): void
    {
        $data = [
            'username' => 'daud',
            'password' => 'daud28@',
        ];
        $rules = [
            'username' => ['required', new In(['daud'])],
            'password' => ['required', Password::min(6)->letters()->numbers()->symbols()],
        ];
        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        self::assertFalse($validator->fails());
        self::assertTrue($validator->passes());

        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    public function testNestedArray()
    {
        $data = [
            'name' => [
                'first' => 'Daud',
                'last' => 'Ramadhan',
            ],
            'address' => [
                'city' => 'Jakarta',
                'country' => 'Indonesia',
            ]
        ];
        $rules = [
            'name.first' => ['required', 'max:100'],
            'name.last' => ['max:100',],
            'address.city' => ['required', 'max:200'] ,
            'address.country' => ['required', 'max:200'] ,
        ];
        $validator = Validator::make($data, $rules);
        self::assertTrue($validator->passes());
        self::assertFalse($validator->fails());

    }
    public function testNestedIndexedArray()
    {
        $data = [
            'name' => [
                'first' => 'Daud',
                'last' => 'Ramadhan',
            ],
            'address' => [
               [
                   'city' => 'Jakarta',
                   'country' => 'Indonesia',
               ],
                [
                    'city' => 'Bandung',
                    'country' => 'Indonesia',
               ]
            ]
        ];
        $rules = [
            'name.first' => ['required', 'max:100'],
            'name.last' => ['max:100',],
            'address.*.city' => ['required', 'max:200'] ,
            'address.*.country' => ['required', 'max:200'] ,
        ];
        $validator = Validator::make($data, $rules);
        self::assertTrue($validator->passes());
        self::assertFalse($validator->fails());

    }

}
