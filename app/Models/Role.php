<?php

namespace App\Models;

use App\Exceptions\RoleNotFoundException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /** @var string */
    protected $table = 'roles';

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $keyType = 'string';

    /** @var array */
    protected $fillable = [
        'role'
    ];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @param string $role
     * 
     * @return Role
     * 
     * @throws RoleNotFoundException
     */
    public static function findByRole(string $role): Role
    {
        $roleFound = self::where('role', $role)->first();

        if (empty($roleFound)) {
            throw RoleNotFoundException::withRole($role);
        }

        return $roleFound;
    }
}
