<?php

/**
 * Assert
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace Assert;

use LogicException;

/**
 * Chaining builder for lazy assertions.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 *
 * @method LazyAssertion alnum(string|callable $message = null, string $propertyPath = null) Assert that value is alphanumeric.
 * @method LazyAssertion between(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater or equal than a lower limit, and less than or equal to an upper limit.
 * @method LazyAssertion betweenExclusive(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater than a lower limit, and less than an upper limit.
 * @method LazyAssertion betweenLength(int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min,max lengths.
 * @method LazyAssertion boolean(string|callable $message = null, string $propertyPath = null) Assert that value is php boolean.
 * @method LazyAssertion choice(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices.
 * @method LazyAssertion choicesNotEmpty(array $choices, string|callable $message = null, string $propertyPath = null) Determines if the values array has every choice as key and that this choice has content.
 * @method LazyAssertion classExists(string|callable $message = null, string $propertyPath = null) Assert that the class exists.
 * @method LazyAssertion contains(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string contains a sequence of chars.
 * @method LazyAssertion count(array|\Countable $count, string $message = null, string $propertyPath = null) Assert that the count of countable is equal to count.
 * @method LazyAssertion date(string $format, string|callable $message = null, string $propertyPath = null) Assert that date is valid and corresponds to the given format.
 * @method LazyAssertion defined(string|callable $message = null, string $propertyPath = null) Assert that a constant is defined.
 * @method LazyAssertion digit(string|callable $message = null, string $propertyPath = null) Validates if an integer or integerish is a digit.
 * @method LazyAssertion directory(string|callable $message = null, string $propertyPath = null) Assert that a directory exists.
 * @method LazyAssertion e164(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid E164 Phone Number.
 * @method LazyAssertion email(string|callable $message = null, string $propertyPath = null) Assert that value is an email adress (using input_filter/FILTER_VALIDATE_EMAIL).
 * @method LazyAssertion endsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string ends with a sequence of chars.
 * @method LazyAssertion eq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are equal (using == ).
 * @method LazyAssertion extensionLoaded(string|callable $message = null, string $propertyPath = null) Assert that extension is loaded.
 * @method LazyAssertion extensionVersion(string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded and a specific version is installed.
 * @method LazyAssertion false(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean False.
 * @method LazyAssertion file(string|callable $message = null, string $propertyPath = null) Assert that a file exists.
 * @method LazyAssertion float(string|callable $message = null, string $propertyPath = null) Assert that value is a php float.
 * @method LazyAssertion greaterOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater or equal than given limit.
 * @method LazyAssertion greaterThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater than given limit.
 * @method LazyAssertion implementsInterface(string $interfaceName, string|callable $message = null, string $propertyPath = null) Assert that the class implements the interface.
 * @method LazyAssertion inArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices. This is an alias of Assertion::choice().
 * @method LazyAssertion integer(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer.
 * @method LazyAssertion integerish(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer'ish.
 * @method LazyAssertion interfaceExists(string|callable $message = null, string $propertyPath = null) Assert that the interface exists.
 * @method LazyAssertion ip(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 or IPv6 address.
 * @method LazyAssertion ipv4(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 address.
 * @method LazyAssertion ipv6(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv6 address.
 * @method LazyAssertion isArray(string|callable $message = null, string $propertyPath = null) Assert that value is an array.
 * @method LazyAssertion isArrayAccessible(string|callable $message = null, string $propertyPath = null) Assert that value is an array or an array-accessible object.
 * @method LazyAssertion isCallable(string|callable $message = null, string $propertyPath = null) Determines that the provided value is callable.
 * @method LazyAssertion isInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is instance of given class-name.
 * @method LazyAssertion isJsonString(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid json string.
 * @method LazyAssertion isObject(string|callable $message = null, string $propertyPath = null) Determines that the provided value is an object.
 * @method LazyAssertion isTraversable(string|callable $message = null, string $propertyPath = null) Assert that value is an array or a traversable object.
 * @method LazyAssertion keyExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array.
 * @method LazyAssertion keyIsset(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object using isset().
 * @method LazyAssertion keyNotExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key does not exist in an array.
 * @method LazyAssertion length(int $length, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string has a given length.
 * @method LazyAssertion lessOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less or than given limit.
 * @method LazyAssertion lessThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less than given limit.
 * @method LazyAssertion max(mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that a number is smaller as a given limit.
 * @method LazyAssertion maxLength(int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string value is not longer than $maxLength chars.
 * @method LazyAssertion methodExists(mixed $object, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object.
 * @method LazyAssertion min(mixed $minValue, string|callable $message = null, string $propertyPath = null) Assert that a value is at least as big as a given limit.
 * @method LazyAssertion minLength(int $minLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that a string is at least $minLength chars long.
 * @method LazyAssertion noContent(string|callable $message = null, string $propertyPath = null) Assert that value is empty.
 * @method LazyAssertion notBlank(string|callable $message = null, string $propertyPath = null) Assert that value is not blank.
 * @method LazyAssertion notEmpty(string|callable $message = null, string $propertyPath = null) Assert that value is not empty.
 * @method LazyAssertion notEmptyKey(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object and its value is not empty.
 * @method LazyAssertion notEq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not equal (using == ).
 * @method LazyAssertion notInArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is not in array of choices.
 * @method LazyAssertion notIsInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is not instance of given class-name.
 * @method LazyAssertion notNull(string|callable $message = null, string $propertyPath = null) Assert that value is not null.
 * @method LazyAssertion notSame(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not the same (using === ).
 * @method LazyAssertion null(string|callable $message = null, string $propertyPath = null) Assert that value is null.
 * @method LazyAssertion numeric(string|callable $message = null, string $propertyPath = null) Assert that value is numeric.
 * @method LazyAssertion phpVersion(mixed $version, string|callable $message = null, string $propertyPath = null) Assert on PHP version.
 * @method LazyAssertion range(mixed $minValue, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that value is in range of numbers.
 * @method LazyAssertion readable(string|callable $message = null, string $propertyPath = null) Assert that the value is something readable.
 * @method LazyAssertion regex(string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value matches a regex.
 * @method LazyAssertion same(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are the same (using ===).
 * @method LazyAssertion satisfy(callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback.
 * @method LazyAssertion scalar(string|callable $message = null, string $propertyPath = null) Assert that value is a PHP scalar.
 * @method LazyAssertion startsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string starts with a sequence of chars.
 * @method LazyAssertion string(string|callable $message = null, string $propertyPath = null) Assert that value is a string.
 * @method LazyAssertion subclassOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is subclass of given class-name.
 * @method LazyAssertion true(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean True.
 * @method LazyAssertion url(string|callable $message = null, string $propertyPath = null) Assert that value is an URL.
 * @method LazyAssertion uuid(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid UUID.
 * @method LazyAssertion version(string $operator, string $version2, string|callable $message = null, string $propertyPath = null) Assert comparison of two versions.
 * @method LazyAssertion writeable(string|callable $message = null, string $propertyPath = null) Assert that the value is something writeable.
 * @method LazyAssertion all() Switch chain into validation mode for an array of values.
 * @method LazyAssertion nullOr() Switch chain into mode allowing nulls, ignoring further assertions.
 */
class LazyAssertion
{
    private $currentChainFailed = false;
    private $alwaysTryAll = false;
    private $thisChainTryAll = false;
    private $currentChain;
    private $errors = array();

    /** @var string|LazyAssertionException The class to use for exceptions */
    private $exceptionClass = 'Assert\LazyAssertionException';

    public function that($value, $propertyPath, $defaultMessage = null)
    {
        $this->currentChainFailed = false;
        $this->thisChainTryAll = false;
        $this->currentChain = Assert::that($value, $defaultMessage, $propertyPath);

        return $this;
    }

    public function tryAll()
    {
        if (!$this->currentChain) {
            $this->alwaysTryAll = true;
        }

        $this->thisChainTryAll = true;

        return $this;
    }

    public function __call($method, $args)
    {
        if ($this->alwaysTryAll === false
            && $this->thisChainTryAll === false
            && $this->currentChainFailed === true
        ) {
            return $this;
        }

        try {
            call_user_func_array(array($this->currentChain, $method), $args);
        } catch (AssertionFailedException $e) {
            $this->errors[] = $e;
            $this->currentChainFailed = true;
        }

        return $this;
    }

    /**
     * @throws \Assert\LazyAssertionException
     *
     * @return bool
     */
    public function verifyNow()
    {
        if ($this->errors) {
            throw call_user_func(array($this->exceptionClass, 'fromErrors'), $this->errors);
        }

        return true;
    }

    /**
     * @param string $className
     *
     * @return $this
     */
    public function setExceptionClass($className)
    {
        if (!is_string($className)) {
            throw new LogicException('Exception class name must be passed as a string');
        }

        if ($className !== 'Assert\LazyAssertionException' && !is_subclass_of($className, 'Assert\LazyAssertionException')) {
            throw new LogicException($className . ' is not (a subclass of) Assert\LazyAssertionException');
        }

        $this->exceptionClass = $className;

        return $this;
    }
}
