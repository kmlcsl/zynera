<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'code',
        'parent_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'village_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'village_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeKabupaten($query)
    {
        return $query->where('type', 'kabupaten');
    }

    public function scopeKecamatan($query)
    {
        return $query->where('type', 'kecamatan');
    }

    public function scopeDesa($query)
    {
        return $query->where('type', 'desa');
    }

    public function scopeByParent($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    // Methods
    public function getFullPathAttribute()
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->join(', ');
    }

    public function getDistrictAttribute()
    {
        if ($this->type === 'desa' && $this->parent) {
            return $this->parent->name;
        }

        if ($this->type === 'kecamatan') {
            return $this->name;
        }

        return null;
    }

    public function getKabupatenAttribute()
    {
        $current = $this;

        while ($current && $current->type !== 'kabupaten') {
            $current = $current->parent;
        }

        return $current ? $current->name : null;
    }
}
