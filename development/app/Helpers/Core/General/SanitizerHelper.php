<?php


namespace App\Helpers\Core\General;

class SanitizerHelper
{
    /**
     * @param $value
     * @return mixed
     */
    public function filterData($value): mixed
    {

        return filter_var($value, $this->generateSanitizer($value ?: false));
    }

    /**
     * @param $data
     * @return false|int
     */
    public function generateSanitizer($data): bool|int
    {
        return filter_id(gettype($data));
    }
}
