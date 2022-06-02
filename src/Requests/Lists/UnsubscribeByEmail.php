<?php

namespace DataLinx\SqualoMail\Requests\Lists;

use DataLinx\SqualoMail\Exceptions\APIException;
use DataLinx\SqualoMail\Exceptions\ValidationException;
use DataLinx\SqualoMail\Requests\AbstractRequest;
use DataLinx\SqualoMail\Responses\CommonResponse;

class UnsubscribeByEmail extends AbstractRequest
{
    /**
     * @var string Recipient's email
     */
    public string $email;

    /**
     * @var int[] List IDs to subscribe to
     */
    public array $list_ids;

    /**
     * Set the "accept" parameter, which is not set by default, but is configured in SqualoMail settings. However, you can (mostly) force a value here.<br/>
     * The "accept" attribute on the recipient is updated considering how you configured it in SqualoMail settings (Slovenian: "Po kliku na povezavo za odjavo naj prejemnika odjavi od"). You can send a boolean here to force a change, but it will be ignored when the setting is set to the third option (Slovenian: "Prejemanja katerihkoli sporočil v prihodnje").
     *
     * @var bool|null
     */
    public ?bool $accept;

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $this->validateAttributes([
            'email',
            'list_ids',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $data = $this->readAttributes([
            'email',
            'list_ids' => 'listIds',
        ]);

        if (isset($this->accept)) {
            $data['accept'] = (int)$this->accept;
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return 'unsubscribe-recipient-by-email';
    }

    /**
     * @return CommonResponse
     * @throws ValidationException
     * @throws APIException
     */
    public function send(): CommonResponse
    {
        return new CommonResponse($this->sendRequest(true), $this);
    }
}