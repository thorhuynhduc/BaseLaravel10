<?php


namespace Core\Services;


use Core\Enums\AttributeDataType;
use Core\Enums\AttributeRuleValue;

class AttributePackageFeature
{
    private mixed $value = null;
    private ?string $type = null;

    public function __construct(
        private array $attribute
    ) {
        $this->setValue();
        $this->setType();
    }

    private function setValue()
    {
        $value = AttributeDataType::transformAttributes([$this->attribute]);

        if ($value) {
            $this->value = array_shift($value);
        }
    }

    /**
     * @return void
     */
    private function setType(): void
    {
        $this->type = AttributeDataType::getKey($this->attribute['data_type']);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function isType(int $type): bool
    {
        return $this->attribute['data_type'] === $type;
    }

    public function isEnable(): bool
    {
        return !$this->isDisable();
    }

    public function isDisable(): bool
    {
        $isBooleanType = $this->isType(AttributeDataType::BOOLEAN);
        $isIntegerType = $this->isType(AttributeDataType::INTEGER);

        return (int) $this->value === AttributeRuleValue::DISABLE && ($isBooleanType || $isIntegerType);
    }

    public function isUnlimited(): bool
    {
        return (int) $this->value === AttributeRuleValue::UNLIMITED;
    }
}