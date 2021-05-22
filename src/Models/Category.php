<?php


namespace Zelvad\MyWarehouse\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_warehouse',
        'parent_id',
        'name',
        'archived'
    ];
}
