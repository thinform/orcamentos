<?php

if (!function_exists('format_currency')) {
    function format_currency($value)
    {
        if (is_null($value)) {
            return 'R$ 0,00';
        }

        if (is_string($value)) {
            // Remove R$ e qualquer caractere que não seja número ou ponto
            $value = preg_replace('/[^0-9.]/', '', $value);
        }

        return 'R$ ' . number_format((float) $value, 2, ',', '.');
    }
} 