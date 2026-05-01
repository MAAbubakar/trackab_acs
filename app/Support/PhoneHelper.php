<?php

namespace App\Support;

class PhoneHelper
{
    /**
     * Normalize Nigerian phone numbers to 234XXXXXXXXXX format.
     *
     * Examples:
     * 08031234567    -> 2348031234567
     * +2348031234567 -> 2348031234567
     * 2348031234567  -> 2348031234567
     * 8031234567     -> 2348031234567
     */
    public static function normalizeNigeria(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (!$digits) {
            return null;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return '234' . substr($digits, 1);
        }

        if (strlen($digits) === 10 && preg_match('/^[789][01]\d{8}$/', $digits)) {
            return '234' . $digits;
        }

        if (strlen($digits) === 13 && str_starts_with($digits, '234')) {
            return $digits;
        }

        if (str_starts_with($digits, '00234')) {
            $candidate = substr($digits, 2);
            if (strlen($candidate) === 13 && str_starts_with($candidate, '234')) {
                return $candidate;
            }
        }

        return null;
    }

    public static function isValidNigeria(?string $phone): bool
    {
        $normalized = self::normalizeNigeria($phone);

        return (bool) preg_match('/^234[789][01]\d{8}$/', $normalized ?? '');
    }

    public static function cleanText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    public static function cleanEmail(?string $email): ?string
    {
        $email = self::cleanText($email);

        return $email ? strtolower($email) : null;
    }
}
