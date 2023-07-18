<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'is_hidden', 'group', 'additional'];

    protected $casts = [
        'is_hidden' => 'boolean'
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', 0);
    }

    public function getKeyNameAttribute() :string
    {
        return Str::of($this->key)->studly()->replaceFirst('Site', '');
    }

    public function getUrlAttribute() :string
    {
        return uploads($this->value);
    }

    public function getHtmlAttribute() :string
    {
        $attributes = is_null($this->additional)
            ? []
            : json_decode($this->additional, true)['html'] ?? [];

        if (!empty($attributes)){
            $attributes = array_map(fn($name, $value) => sprintf('%s=%s ', $name, $value), array_keys($attributes), array_values($attributes));
        }

        return implode($attributes);
    }


    public function getIsSimpleAttribute() :bool
    {
        return in_array($this->type, ['number', 'text', 'tel', 'email', 'date', 'time', 'datetime-local']);
    }

    public function getIsRadioOrCheckAttribute() :bool
    {
        return in_array($this->type, ['checkbox', 'radio']);
    }

    public function getIsTextAreaAttribute() :bool
    {
        return $this->type === 'textarea';
    }

    public function getIsFileAttribute() :bool
    {
        return $this->type === 'file';
    }
}
