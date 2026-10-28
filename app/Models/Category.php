<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = \Illuminate\Support\Str::slug($category->name, '-');
        });
        
    }

    public static function getParentTree($category) {
        $parents = [];
        
        while ($category->parent) { // Check if the category has a parent
            $category = $category->parent; // Move up the hierarchy
            $parents[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'status'=>$category->status
            ];
        }
        return $parents;
        //return array_reverse($parents); // Ensure higher-level parents come first
    }

    public static function getCategoryTree($categories, $parentId = null, $prefix = '') {
        $tree = [];
    
        // Convert collection to an array if needed
        $categories = is_array($categories) ? $categories : $categories->toArray();
    
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $tree[] = [
                    'id' => $category['id'],
                    'name' => $prefix . $category['name'], // Add prefix for hierarchy
                ];
    
                // Recursively get children with indentation
                $children = self::getCategoryTree($categories, $category['id'], $prefix . '-- ');
                $tree = array_merge($tree, $children);
            }
        }
    
        return $tree;
    }
public function children(): HasMany
    {
        return $this->hasMany(Category::class,'parent_id');
    }
    public static function getAllChildIds($parentId)    {
    $ids = [];

    $children = Category::where('parent_id', $parentId)->pluck('id');

    foreach ($children as $childId) {
        $ids[] = $childId;
        $ids = array_merge($ids, self::getAllChildIds($childId)); // recursion
    }

    return $ids;
}
    

}
