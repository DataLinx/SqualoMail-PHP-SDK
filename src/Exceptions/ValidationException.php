<?php

namespace DataLinx\SqualoMail\Exceptions;

use Exception;
use Throwable;

/**
 * Request validation exception
 */
class ValidationException extends Exception {

	const CODE_ATTR_REQUIRED = 1;
    const CODE_ATTR_EITHER_REQUIRED = 2;

	public $attribute;

	/**
	 * @param string|array $attribute Request attribute(s)
	 * @param int $code Exception code (see class constants)
	 * @param Throwable|null $previous
	 */
	public function __construct($attribute, int $code, Throwable $previous = NULL)
	{
		parent::__construct($this->prepMessage($attribute, $code), $code, $previous);

		$this->attribute = $attribute;
	}

	/**
	 * Create the message based on the exception code
	 *
	 * @param string|array $attribute
	 * @param string $code
	 * @return string
	 */
	private function prepMessage($attribute, string $code): string
	{
		switch ($code) {
            case self::CODE_ATTR_EITHER_REQUIRED:
                if (is_array($attribute) AND count($attribute) == 2) {
                    return sprintf('Either the "%s" or "%s" attribute is required', $attribute[0], $attribute[1]);
                }
                break;
		}

        return "Attribute \"$attribute\" is required";
	}
}