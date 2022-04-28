<?php

namespace DataLinx\SqualoMail\Responses;

/**
 * Interface that each API response should implement
 */
interface ResponseInterface {

	/**
	 * Was the request successful?
	 *
	 * @return bool
	 */
	public function isSuccessful(): bool;

    /**
     * Get error code
     *
     * @return int|null
     */
    public function getErrorCode(): ?int;

    /**
     * Get error message, if set
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string;

}