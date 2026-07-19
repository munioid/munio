<?php

namespace App\Traits;

use App\Models\Membership\Attribute;

trait HasMemberAttributes
{
    protected function syncAttributes(): void
    {
        $values = data_get($this->data, 'attributes', []);

        if (empty($values)) {
            return;
        }

        $attributes = Attribute::query()
            ->whereIn('fieldname', array_keys($values))
            ->get()
            ->keyBy('fieldname');

        $sync = [];
        foreach ($values as $fieldname => $value) {

            $attribute = $attributes->get($fieldname);

            if (! $attribute) {
                continue;
            }

            $sync[$attribute->id] = [
                'value' => $value,
            ];
        }

        $this->record->attributes()->sync($sync);

        $this->record->generateNumber();
    }
}
