# Vendor_ExtendedOrderGrid

This is an example module that adds Customer Lifetime Value to the order grid.

## How it works
The module itself adds a convenient pool pattern to add new calculated fields via di. The `LifetimeValue` field itself
simply uses a `sum(grand_total)` SQL query.

## Adding a new field

```xml
<!-- etc/di.xml -->
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <type name="Vendor\ExtendedOrderGrid\Model\Field\FieldPool">
        <arguments>
            <argument name="fields" xsi:type="array">
                <item name="field_name" xsi:type="object">Vendor\Module\Model\Field\FieldName</item>
            </argument>
        </arguments>
    </type>
</config>
```
```php
<?php
// app/code/Vendor/Module/Model/Field/FieldName.php
namespace Vendor\Module\Model\Field;

class FieldName implements FieldInterface
{
    public const CODE = 'field_name';

    public function __construct() { }

    public function getCode(): string
    {
        return self::CODE;
    }

    public function calculate(array $orderRow): float
    {
        // ..
        return 42;
    }
}
```
