<?php

namespace App;

use App\Traits\HasUuid;

class ClientInfo extends BaseModel
{
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'dui',
        'address',
        'city_id',
        'email',
        'alternative_number_phone',
    ];

    /**
     * Get client belongs to client info.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_uuid');
    }
}
