<?php namespace X10WeaponStatsApi\Http\Controllers\Admin;

use X10WeaponStatsApi\Http\Requests;
use X10WeaponStatsApi\Http\Controllers\Controller;

use Illuminate\Http\Request;

use X10WeaponStatsApi\Models\Weapon;

class WeaponsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $weapons = Weapon::all()
            ->filter(function($item) {
                return stripos($item->item_class, 'tf_weapon') === 0;
            })
            ->sortBy('defindex', SORT_NUMERIC)
        ;
        
    	return view('admin.weapons.index', ['weapons' => $weapons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $weapon = new Weapon;
        
        return view('admin.weapons.form', ['weapon' => $weapon]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $weapon = Weapon::create($request->all());
        
        return \Redirect::route('admin.weapons.show', [$weapon->defindex]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id            
     * @return Response
     */
    public function show($id)
    {
        return view('admin.weapons.show', ['weapon' => Weapon::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id            
     * @return Response
     */
    public function edit($id)
    {
        $weapon = Weapon::findOrFail($id);
        
        return view('admin.weapons.form', ['weapon' => $weapon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id            
     * @return Response
     */
    public function update($id, Request $request)
    {
        $weapon = Weapon::find($id)->fill($request->all());
        
        $weapon->save();
        
        return \Redirect::route('admin.weapons.show', [$weapon->defindex]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id            
     * @return Response
     */
    public function destroy($id)
    {
        Weapon::findOrFail($id)->delete();
    }
}
