<?php

namespace Chord\Domain\Address\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * @package Chord\Domain\Address\Models
 */
class Address extends Model
{
    public function print()
    {
        $fields = [
            trim($this->district),
            trim($this->locality),
            trim($this->street),
            trim($this->site),
            trim($this->site_number),
            trim($this->site_description),
            trim($this->site_subdescription)
        ];
        $fields = array_filter($fields);
        return implode(', ', $fields);

    }

    private function isFieldEmpty(string $field): string
    {
        return $this->{$field} ?? '';
    }
}