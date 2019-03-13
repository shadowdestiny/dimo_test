<?php

namespace App;

use App\Traits\HasUuid;

class Setting extends BaseModel
{
    use HasUuid;

    const LATE_FEE              = "TASA_MORA";
    const ANNUAL_RATE           = "TASA_ANUAL";
    const PERIODS               = "PERIODOS";
    const MAXIMUM_TO_APPLY      = "MAXIMO_A_APLICAR";
    const MINIMUN_TO_APPLY      = "MINUMO_A_APLICAR";
    const MAXIMUM_NEXT_LEVEL    = "MAXIMO_SIGUIENTE_NIVEL";
    const PAY_NEXT              = "PAGOS_SIGUIENTE_NIVEL";

    const SETTING_KEYS = [
        "LATE_FEE"              => "TASA_MORA",
        "ANNUAL_RATE"           => "TASA_ANUAL",
        "PERIODS"               => "PERIODOS",
        "MAXIMUM_TO_APPLY"      => "MAXIMO_A_APLICAR",
        "MINIMUN_TO_APPLY"      => "MINUMO_A_APLICAR",
        "MAXIMUM_NEXT_LEVEL"    => "MAXIMO_SIGUIENTE_NIVEL",
        "PAY_NEXT"              => "PAGOS_SIGUIENTE_NIVEL",
    ];

    protected $fillable = ['key', 'description', 'value'];

    /**
     * Return value by key.
     *
     * @param string    const  $ke = '';
     *
     * @return float
     */
    public function value(string $key)
    {
        return $this->key($key)->first()->value;
    }

    /**
     * Key setting.
     *
     * @param string $key
     */
    public function scopeKey($query, string $key)
    {
        return $query->where('key', $key);
    }
}
