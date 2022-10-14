<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    public function getTable()
    {
        return config('iprosoftware-sync.tables.contact_types');
    }
}
