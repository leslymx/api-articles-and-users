<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailDomainValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $domain = explode('@', $value)[1] ?? null;

        if (!$domain) {
            return false;
        }
        if ($domain != 'gmail.com' || $domain != 'outlook.com' || $domain != 'outlook.es') {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute it must be a valid domain [gmail,outlook].';
    }
}
