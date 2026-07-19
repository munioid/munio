<?php

namespace App\Services\Membership;

use App\Models\Membership\Package;
use App\Models\NumberSequence;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

class MemberNumberService
{
    public static function generateNumber(Package $package, array $data)
    {
        $tenant = $package->organization;
        Filament::setTenant($tenant, true);

        $format = $package->format;

        // matching all format
        preg_match_all('/{{(.*?)}}/', $format, $matches);
        foreach (end($matches) as $value) {
            if (str_starts_with($value, 'index')) {
                $prefix = strstr($format, '{{index', true);
                $format = self::replaceNumberingIndex($value, $format, $prefix);
                continue;
            }

            if ($value) {
                $format = self::replaceAttributeValue($value, $format, $data);
            }
        }

        return $format;
    }

    protected static function replaceNumberingIndex(string $placeholder, string $format, string $prefix): string
    {
        // index:number
        preg_match('/^index(?::(\d+))?$/', $placeholder, $matches);

        $length = (int) ($matches[1] ?? 0);

        // ambil nomor urut berikutnya
        $index = '';
        if ($length > 0) {
            $sequence = DB::transaction(function () use ($prefix) {
                $sequence = NumberSequence::lockForUpdate()
                    ->firstOrCreate(
                        ['prefix' => $prefix],
                        ['index' => 0]
                    );

                $sequence->increment('index');

                return $sequence->index;
            });

            $index = str_pad((string) $sequence, $length, '0', STR_PAD_LEFT);
        }

        return str_replace(
            '{{' . $placeholder . '}}',
            $index,
            $format
        );
    }

    protected static function replaceAttributeValue(string $placeholder, string $format, array $data)
    {
        $value = data_get($data, $placeholder);

        return str_replace(
            '{{' . $placeholder . '}}',
            $value,
            $format
        );
    }
}
