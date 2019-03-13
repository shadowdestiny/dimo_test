<?php

namespace App;

use App\Traits\HasUuid;

class ClientData extends BaseModel
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['number_phone', 'verified', 'level_uuid', 'type_uuid'];
}
