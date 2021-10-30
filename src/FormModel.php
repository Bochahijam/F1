<?php

declare(strict_types=1);

namespace Yiisoft\Form;

use Closure;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionNamedType;
use Stringable;
use Yiisoft\Strings\Inflector;
use Yiisoft\Strings\StringHelper;
use Yiisoft\Validator\PostValidationHookInterface;
use Yiisoft\Validator\ResultSet;
use Yiisoft\Validator\RulesProviderInterface;

use function array_key_exists;
use function array_merge;
use function explode;
use function is_subclass_of;
use function reset;
use function strpos;

/**
 * Form model represents an HTML form: its data, validation and presentation.
 */
abstract class FormModel implements FormModelInterface, PostValidationHookInterface, RulesProviderInterface
{
    /**
     * @psalm-param array<string, array<array-key, string>>
     */
    private const TYPE_MAP = [
        'boolean' => 'bool',
        'integer' => 'int',
        'double'  => 'float',
        'string'  => 'string',
        'array'   => 'array',
        'object'  => 'object',
        'NULL'    => 'null',
    ];

    /**
     * @psalm-param array<string, array<array-key, int>>
     */
    private const SCALAR_TYPE_MAP = [
        FILTER_VALIDATE_BOOL  => 'bool',
        FILTER_VALIDATE_INT   => 'int',
        FILTER_VALIDATE_FLOAT => 'float',
        FILTER_DEFAULT        => 'string',
    ];

    private array $attributes;
    /** @psalm-var array<string, array<array-key, string>> */
    private array $attributesErrors = [];
    private ?Inflector $inflector = null;
    private bool $validated = false;

    public function __construct()
    {
        $this->attributes = $this->collectAttributes();
    }

    public function getAttributeHint(string $attribute): string
    {
        $attributeHints = $this->getAttributeHints();
        $hint = $attributeHints[$attribute] ?? '';
        $nestedAttributeHint = $this->getNestedAttributeValue('getAttributeHint', $attribute);

        return $nestedAttributeHint !== '' ? $nestedAttributeHint : $hint;
    }

    /**
     * @return string[]
     */
    public function getAttributeHints(): array
    {
        return [];
    }

    public function getAttributeLabel(string $attribute): string
    {
        $label = $this->generateAttributeLabel($attribute);
        $labels = $this->getAttributeLabels();

        if (array_key_exists($attribute, $labels)) {
            $label = $labels[$attribute];
        }

        $nestedAttributeLabel = $this->getNestedAttributeValue('getAttributeLabel', $attribute);

        return $nestedAttributeLabel !== '' ? $nestedAttributeLabel : $label;
    }

    /**
     * @return string[]
     */
    public function getAttributeLabels(): array
    {
        return [];
    }

    public function getAttributePlaceholder(string $attribute): string
    {
        $attributePlaceHolders = $this->getAttributePlaceholders();
        $placeholder = $attributePlaceHolders[$attribute] ?? '';
        $nestedAttributePlaceholder = $this->getNestedAttributeValue('getAttributePlaceholder', $attribute);

        return $nestedAttributePlaceholder !== '' ? $nestedAttributePlaceholder : $placeholder;
    }

    /**
     * @return string[]
     */
    public function getAttributePlaceholders(): array
    {
        return [];
    }

    /**
     * @return iterable|object|scalar|Stringable|null
     */
    public function getAttributeValue(string $attribute)
    {
        return $this->readProperty($attribute);
    }

    /**
     * @return string Returns classname without a namespace part or empty string when class is anonymous
     */
    public function getFormName(): string
    {
        if (strpos(static::class, '@anonymous') !== false) {
            return '';
        }

        $className = strrchr(static::class, '\\');
        if ($className === false) {
            return static::class;
        }

        return substr($className, 1);
    }

    public function hasAttribute(string $attribute): bool
    {
        return array_key_exists($attribute, $this->attributes);
    }

    /**
     * @return string[]
     */
    public function getError(string $attribute): array
    {
        return $this->attributesErrors[$attribute] ?? [];
    }

    /**
     * @return string[][]
     *
     * @psalm-return array<string, array<string>>
     */
    public function getErrors(): array
    {
        return $this->attributesErrors;
    }

    public function getErrorSummary(bool $showAllErrors): array
    {
        $lines = [];
        $errors = $showAllErrors ? $this->getErrors() : [$this->getFirstErrors()];

        foreach ($errors as $error) {
            $lines = array_merge($lines, $error);
        }

        return $lines;
    }

    public function getFirstError(string $attribute): string
    {
        if (empty($this->attributesErrors[$attribute])) {
            return '';
        }

        return reset($this->attributesErrors[$attribute]);
    }

    public function getFirstErrors(): array
    {
        if (empty($this->attributesErrors)) {
            return [];
        }

        $errors = [];

        foreach ($this->attributesErrors as $name => $es) {
            if (!empty($es)) {
                $errors[$name] = reset($es);
            }
        }

        return $errors;
    }

    public function hasErrors(?string $attribute = null): bool
    {
        return $attribute === null ? !empty($this->attributesErrors) : isset($this->attributesErrors[$attribute]);
    }

    /**
     * @param array $data
     * @param string|null $formName
     *
     * @return bool
     */
    public function load(array $data, ?string $formName = null): bool
    {
        $scope = $formName ?? $this->getFormName();

        /**
         * @psalm-var array<string, scalar|Stringable|null>
         */
        $values = [];

        if ($scope === '' && !empty($data)) {
            $values = $data;
        } elseif (isset($data[$scope])) {
            /** @var mixed */
            $values = $data[$scope];
        }

        /** @var array<string, scalar|Stringable|null> $values */
        foreach ($values as $name => $value) {
            $this->setAttribute($name, $value);
        }

        return $values !== [];
    }

    /**
     *
     * @param mixed $value
     * @param string $types
     * @return mixed
     */
    private static function setType($value, string ...$types)
    {
        foreach ($types as $type) {
            $v = $value;

            if (settype($v, $type)) {
                return $v;
            }
        }

        return $value;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    protected function typeCast(string $name, $value)
    {
        $type = gettype($value);
        $mapped = self::TYPE_MAP[$type] ?? null;
        $types = $this->attributes[$name] ?? null;

        if (empty($types) || in_array($mapped, $types, true)) {
            return $value;
        }

        if (is_scalar($value)) {
            $filters = array_keys(array_intersect(self::SCALAR_TYPE_MAP, $types));

            foreach ($filters as $filter) {
                $val = filter_var($value, $filter, FILTER_NULL_ON_FAILURE);

                if ($val !== null) {
                    return $val;
                }
            }
        } elseif ($value instanceof Stringable && in_array('string', $types, true)) {
            return (string) $value;
        }

        $types = array_intersect($types, self::TYPE_MAP);

        return self::setType($value, ...$types);
    }

    /**
     * @param iterable|object|scalar|Stringable|null $value
     *
     * @psalm-suppress PossiblyInvalidCast
     */
    public function setAttribute(string $name, $value): void
    {
        [$realName] = $this->getNestedAttribute($name);

        if (isset($this->attributes[$realName])) {
            $value = $this->typeCast($realName, $value);
            $this->writeProperty($name, $value);
        }
    }

    public function processValidationResult(ResultSet $resultSet): void
    {
        $this->clearErrors();
        /** @var array<array-key, Resultset> $resultSet */
        foreach ($resultSet as $attribute => $result) {
            if ($result->isValid() === false) {
                /** @psalm-suppress InvalidArgument */
                $this->addErrors([$attribute => $result->getErrors()]);
            }
        }
        $this->validated = true;
    }

    public function addError(string $attribute, string $error): void
    {
        $this->attributesErrors[$attribute][] = $error;
    }

    public function getRules(): array
    {
        return [];
    }

    /**
     * Returns the list of attribute types indexed by attribute names.
     *
     * By default, this method returns all non-static properties of the class.
     *
     * @throws \ReflectionException
     *
     * @return array list of attribute types indexed by attribute names.
     */
    protected function collectAttributes(): array
    {
        $class = new ReflectionClass($this);
        $attributes = [];

        foreach ($class->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            /** @var ReflectionNamedType|ReflectionUnionTypes|null $type */
            $type = $property->getType();
            $name = $property->getName();

            if ($type === null) {
                $attributes[$name] = [];
            } else {
                if ($type instanceof ReflectionNamedType) {
                    $attributes[$name] = [$type->getName()];
                } else {
                    /** @var ReflectionUnionTypes $type */
                    $attributes[$name] = array_map(fn (ReflectionNamedType $type) => $type->getName(), $type->getTypes());
                }

                if ($type->allowsNull()) {
                    $attributes[$name][] = self::TYPE_MAP['NULL'];
                }
            }
        }

        return $attributes;
    }

    /**
     * @psalm-param array<string, array<array-key, string>> $items
     */
    private function addErrors(array $items): void
    {
        foreach ($items as $attribute => $errors) {
            foreach ($errors as $error) {
                $this->attributesErrors[$attribute][] = $error;
            }
        }
    }

    private function clearErrors(): void
    {
        $this->attributesErrors = [];
        $this->validated = false;
    }

    private function getInflector(): Inflector
    {
        if ($this->inflector === null) {
            $this->inflector = new Inflector();
        }
        return $this->inflector;
    }

    /**
     * Generates a user friendly attribute label based on the give attribute name.
     *
     * This is done by replacing underscores, dashes and dots with blanks and changing the first letter of each word to
     * upper case.
     *
     * For example, 'department_name' or 'DepartmentName' will generate 'Department Name'.
     *
     * @param string $name the column name.
     *
     * @return string the attribute label.
     */
    private function generateAttributeLabel(string $name): string
    {
        return StringHelper::uppercaseFirstCharacterInEachWord(
            $this->getInflector()->toWords($name)
        );
    }

    /**
     * @return iterable|scalar|Stringable|null
     *
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MissingClosureReturnType
     */
    private function readProperty(string $attribute)
    {
        $class = static::class;

        [$attribute, $nested] = $this->getNestedAttribute($attribute);

        if (!property_exists($class, $attribute)) {
            throw new InvalidArgumentException("Undefined property: \"$class::$attribute\".");
        }

        /** @psalm-suppress MixedMethodCall */
        $getter = static fn (FormModelInterface $class, string $attribute) => $nested === null
            ? $class->$attribute
            : $class->$attribute->getAttributeValue($nested);

        $getter = Closure::bind($getter, null, $this);

        /** @var Closure $getter */
        return $getter($this, $attribute);
    }

    /**
     * @param string $attribute
     * @param iterable|object|scalar|Stringable|null $value
     *
     * @psalm-suppress MissingClosureReturnType
     */
    private function writeProperty(string $attribute, $value): void
    {
        [$attribute, $nested] = $this->getNestedAttribute($attribute);

        /**
         * @psalm-suppress MissingClosureParamType
         * @psalm-suppress MixedMethodCall
         */
        $setter = static fn (FormModelInterface $class, string $attribute, $value) => $nested === null
            ? $class->$attribute = $value
            : $class->$attribute->setAttribute($nested, $value);

        $setter = Closure::bind($setter, null, $this);

        /** @var Closure $setter */
        $setter($this, $attribute, $value);
    }

    /**
     * @return string[]
     *
     * @psalm-return array{0: string, 1: null|string}
     */
    private function getNestedAttribute(string $attribute): array
    {
        if (strpos($attribute, '.') === false) {
            return [$attribute, null];
        }

        [$attribute, $nested] = explode('.', $attribute, 2);

        /** @var array */
        $attributeNested = $this->attributes[$attribute];
        $classNames = array_diff($attributeNested, self::TYPE_MAP);

        foreach ($classNames as $className) {
            /** @var object|string  $className */
            if (is_subclass_of($className, self::class)) {
                return [$attribute, $nested];
            }
        }

        throw new InvalidArgumentException('Nested attribute can only be of ' . self::class . ' type.');
    }

    private function getNestedAttributeValue(string $method, string $attribute): string
    {
        $result = '';

        [$attribute, $nested] = $this->getNestedAttribute($attribute);

        if ($nested !== null) {
            /** @var FormModelInterface $attributeNestedValue */
            $attributeNestedValue = $this->getAttributeValue($attribute);
            /** @var string */
            $result = $attributeNestedValue->$method($nested);
        }

        return $result;
    }

    public function isValidated(): bool
    {
        return $this->validated;
    }
}
