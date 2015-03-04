<?php
namespace X10WeaponStatsApi\Http\Controllers\Admin;

use X10WeaponStatsApi\Http\Requests;
use X10WeaponStatsApi\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use X10WeaponStatsApi\Models\Config;
use X10WeaponStatsApi\Models\Person;
use X10WeaponStatsApi\Models\Weapon;
use X10WeaponStatsApi\Models\Attribute;
use X10WeaponStatsApi\Models\WeaponInstance;
use X10WeaponStatsApi\Models\WeaponInstanceAttribute;

/**
 * Class WeaponInstancesController
 * @package X10WeaponStatsApi\Http\Controllers\Admin
 */
class WeaponInstancesController extends Controller
{

    public function __construct()
    {
        \HTML::macro('displayField', function($label = null, $value = null)
        {

            return <<<DOC
<div class="field">
    <div class="field-name">
        $label
    </div>
    <div class="field-value">
        $value
    </div>
</div>
DOC;

        });
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $config_id
     * @param int $person_id
     * @return Response
     */
    public function index($config_id = 1, $person_id = 1)
    {
        $weaponInstances = WeaponInstance::all([
            'config_id' => $config_id,
            'person_id' => $person_id
        ]);
        
        return view('admin.weaponInstance.index', ['weaponInstances' => $weaponInstances]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $weaponInstance = new WeaponInstance();
        
        $configSelect = $this->getSelect('config', Config::all(), 'id', 'name');
        $personSelect = $this->getSelect('person', Person::all(), 'id', 'name');
        $weaponSelect = $this->getSelect('weapon', Weapon::all(), 'defindex', 'name');
        
        $attributeSelectArr = [];
        $attributeValueArr = [];
        
        for ($i = 0; $i < 10; $i ++) {
            $attributeSelectArr[] = $this->getSelect("attributes[$i][defindex]", Attribute::all(), 'defindex', 'name') . '<label>Attribute Value<input type="text" name="attributes[' . $i . '][attribute_value]"></label>';
        }
        
        return view('admin.weaponInstance.create', [
            'weaponInstance' => $weaponInstance,
            'configSelect' => $configSelect,
            'personSelect' => $personSelect,
            'weaponSelect' => $weaponSelect,
            'attributeSelectArr' => $attributeSelectArr,
            'attributeValueArr' => $attributeValueArr
        ]);
    }

    protected function getSelect($name, $collection, $valueProp, $textProp, $selected_id)
    {
        $options = [
            'nothing' => 'nothing'
        ];
        
        foreach ($collection as $item) {
            $options[$item[$valueProp]] = $item[$textProp];
        }
        
        return \Form::select($name, $options, $selected_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $params = $request->all();
        
        if ($params['config'] === 'nothing' || $params['person'] === 'nothing' || $params['weapon'] === 'nothing') {
            throw new \Exception('You must select a config, a person, and a weapon');
        }
        
        $attributes = $params['attributes'];

        $config = Config::find($params['config']);

        $weaponInstance = new WeaponInstance();

        $weaponInstance->config()->associate(Config::find($params['config']));
        $weaponInstance->person()->associate(Person::find($params['person']));
        $weaponInstance->weapon()->associate(Weapon::find($params['weapon']));


        $attributeCollection = [];

        foreach ($attributes as $key => $attribute) {
            if ($attribute['defindex'] !== 'nothing') {

                $attributeCollection[] = new WeaponInstanceAttribute([
                    'attribute_defindex' => $attribute['defindex'],
                    'attribute_value'    => $attribute['attribute_value']
                ]);
            }
        }

        $weaponInstance->save();
        $weaponInstance->attributes()->saveMany($attributeCollection);

        return \Redirect::route('admin.weapon-instance.show', [$weaponInstance->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id            
     * @return Response
     */
    public function show($id)
    {
        return view('admin.weaponInstance.show', ['weaponInstance' => WeaponInstance::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id            
     * @return Response
     */
    public function edit($id)
    {
        $weaponInstance = WeaponInstance::find($id);
        $configSelect = $this->getSelect('config', Config::all(), 'id', 'name', $weaponInstance->config->id);
        $personSelect = $this->getSelect('person', Person::all(), 'id', 'name', $weaponInstance->person->id);
        $weaponSelect = $this->getSelect('weapon', Weapon::all(), 'defindex', 'name', $weaponInstance->weapon->defindex);

        return view('admin.weaponInstance.edit', [
            'weaponInstance' => $weaponInstance,
            'configSelect' => $configSelect,
            'personSelect' => $personSelect,
            'weaponSelect' => $weaponSelect
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id            
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id            
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
