<?php

class Validator
{
    /**
     * Is form valid;
     *
     * @var bool
     */
    protected static $isValid = true;
    /**
     * List of errors, Object with error messages one per fieldName
     *
     * @var array
     */
    protected static $errors = [];

    /**
     * Default string error messages one per fieldName
     *
     * @var string
     */
    protected static $strMsg = "Enter valid string.";

    /**
     * Default number error messages one per fieldName
     *
     * @var string
     */
    protected static $numberMsg = "Enter valid number.";

    /**
     * Default email error messages one per fieldName
     *
     * @var string
     */
    protected static $emailMsg = "Enter valid email.";

    /**
     * Default required error messages one per fieldName
     *
     * @var string
     */
    protected static $requiredMsg = "This field is required.";

    /**
     * Check if form is valid
     *
     * @return bool
     */
    protected static function isValid(): bool
    {
        return self::$isValid;
    }

    protected static function validateRequired(array $rule, array $payload, $message)
    {
        if (
            true === $rule['required']
            && !isset($payload[$rule['fieldName']])
            && empty($payload[$rule['fieldName']])
        ) {
            self::$isValid = false;
            self::$errors[$rule['fieldName']] = count($message) > 0 ? $message[$rule['fieldName']] : self::$requiredMsg;
            return false;
        }

        return true;
    }

    protected static function validateString($rule, $payload, $message)
    {
        if (!preg_match('/<\s?[^\>]*\/?\s?>/i', $payload[$rule['fieldName']])) {
            self::$isValid = false;
            self::$errors[$rule['fieldName']] = count($message) > 0 ? $message[$rule['fieldName']] : self::$strMsg;
            return false;
        }
        return true;
    }

    protected static function validateEmail($rule, $payload, $message)
    {
        if (!filter_var($payload[$rule['fieldName']], FILTER_VALIDATE_EMAIL) !== false) {
            self::$isValid = false;
            self::$errors[$rule['fieldName']] = count($message) > 0 ? $message[$rule['fieldName']] : self::$emailMsg;
            return false;
        }
        return true;
    }

    protected static function validateIntger($rule, $payload, $message)
    {
        if (!is_numeric($payload[$rule['fieldName']])) {
            self::$isValid = false;
            self::$errors[$rule['fieldName']] = count($message) > 0 ? $message[$rule['fieldName']] : self::$numberMsg;
            return false;
        }
        return true;
    }

    protected static function check_min_length($rule, $payload, $message)
    {
        if (isset($rule['minlength']) && strlen($payload[$rule['fieldName']]) < intval($rule['minlength'])) {
            $num = $rule['minlength'];
            self::$isValid = false;
            self::$errors[$rule['fieldName']] = count($message) > 0 && isset($message[$rule['fieldName']]) ? $message[$rule['fieldName']] : "Enter minimum $num characters.";
            return false;
        }
        return true;
    }

    protected static function check_max_length($rule, $payload, $message)
    {
        if (isset($rule['maxlength']) && strlen($payload[$rule['fieldName']]) > intval($rule['maxlength'])) {
            $num = $rule['maxlength'];
            self::$isValid = false;
            self::$errors[$rule['fieldName']] = count($message) > 0 && isset($message[$rule['fieldName']]) ? $message[$rule['fieldName']] : "Enter maximum $num characters.";
            return false;
        }
        return true;
    }
    /**
     * @param array $rules list of rules
     * @param array $payload list of form parameters
     * @param array $message list of form custom error message
     * @return bool Return validation result, same as isValid
     */
    protected static function validate(array $rules, array $payload, array $message = [])
    {
        foreach ($rules as $rule) {
            if (!Validator::validateRequired($rule, $payload, $message)) {
                continue;
            }
            // check min length
            if (!Validator::check_min_length($rule, $payload, $message)) {
                continue;
            }
            // check max length
            if (!Validator::check_max_length($rule, $payload, $message)) {
                continue;
            }
            switch ($rule['type']) {
                case 'string':
                    Validator::validateString($rule, $payload, $message);
                    break;
                case 'email':
                    Validator::validateEmail($rule, $payload, $message);
                    break;
                case 'number':
                    Validator::validateIntger($rule, $payload, $message);
                    break;
            }
        }
        return Validator::isValid();
    }
}
