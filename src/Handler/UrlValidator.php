<?php

namespace EnderLab\Handler;

use EnderLab\ValidatorInterface;

class UrlValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $template = 'Url ":value" is not valid';

    /**
     * @var array
     */
    private $templateVar = [];

    /**
     * @var string
     */
    private $error = '';

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $fieldName;
    /**
     * @var array
     */
    private $flags;

    /**
     * @param string      $fieldName
     * @param mixed       $value
     * @param int|null    $flags
     * @param null|string $customTemplate
     */
    public function __construct(string $fieldName, $value, ?int $flags = null, ?string $customTemplate = null)
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->flags = $flags;
        $this->template = null === $customTemplate ? $this->template : $customTemplate;
        $this->templateVar = [
            ':value'     => $value,
            ':fieldname' => $fieldName
        ];
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (false === filter_var(
            $this->value,
            FILTER_VALIDATE_URL,
            (null === $this->flags ? [] : ['flags' => $this->flags])
        )) {
            $this->buildError();

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    private function buildError(): void
    {
        $this->error = str_replace(
            $this->templateVar,
            [$this->value, $this->fieldName],
            $this->template
        );
    }
}
