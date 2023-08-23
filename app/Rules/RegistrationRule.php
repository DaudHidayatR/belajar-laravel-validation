<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class RegistrationRule implements ValidationRule, DataAwareRule, ValidatorAwareRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    private array $data;
    private Validator $validator;

    public function setData(array $data):RegistrationRule
    {
        // TODO: Implement setData() method.
        $this->data = $data;
        return $this;
    }
    public function setValidator(Validator $validator):RegistrationRule
    {
        // TODO: Implement setValidator() method.
        $this->validator = $validator;
        return $this;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $password = $value;
        $username = $this->data['username'];
        if($password === $username){
            $fail("$attribute must not be the same as username");
        }
    }



}
