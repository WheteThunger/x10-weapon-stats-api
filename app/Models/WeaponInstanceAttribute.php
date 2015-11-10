<?php

namespace X10WeaponStatsApi\Models;


class WeaponInstanceAttribute extends BaseModel
{
    
    protected $fillable = [
        'weapon_instance_id',
        'attribute_defindex',
        'attribute_value',
        
    ];

    // If you don't define accessor methods for each property in here an exception is thrown whenever you call
    // one of `toArray` or `toJson`. Its too bad it doesn't check if the method exists....fucking casuals
//    protected $appends = [
//        'attribute_name',
//        'attribute_class',
//        'description_string',
//        'description_format',
//        'effect_type',
//        'hidden',
//    ];
    
    public function weaponInstance()
    {
        return $this->belongsTo('X10WeaponStatsApi\Models\WeaponInstance');
    }

    public function attribute()
    {
        return $this->belongsTo('X10WeaponStatsApi\Models\Attribute', 'attribute_defindex');
    }
    
    protected function cachedAttributesCollection() {
        static $attributesCollection = null;
        
        if($attributesCollection === null) {
            $attributesCollection = Attribute::all();
        }
        
        return $attributesCollection;
    }
    
    protected function cachedAttribute() {
        $this->cachedAttributesCollection()->where('defindex', $this->attribute_defindex);
        
        static $thing = null;
        
        if($thing === null) {
            $thing = $this->attribute()->first();
        }
        
        return $thing;
        
        
        return $this
            ->cachedAttributesCollection()
            ->where('defindex', $this->attribute_defindex)
            ->first()
        ;
    }
    
    public function getAttributeNameAttribute() {
        return $this->cachedAttribute()->name;
    }
    
    public function getAttributeClassAttribute() {
        return $this->cachedAttribute()->attribute_class;
    }
    
    public function getDescriptionStringAttribute() {
        return $this->cachedAttribute()->description_string;
    }
    
    public function getDescriptionFormatAttribute() {
        return $this->cachedAttribute()->description_format;
    }
    
    public function getEffectTypeAttribute() {
        return $this->cachedAttribute()->effect_type;
    }
    
    public function getHiddenAttribute() {
        return $this->cachedAttribute()->hidden;
    }
}