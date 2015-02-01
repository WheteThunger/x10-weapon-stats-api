<?php namespace X10WeaponStatsApi\Http\Controllers\Admin;

use X10WeaponStatsApi\Http\Requests;
use X10WeaponStatsApi\Http\Controllers\Controller;

use Illuminate\Http\Request;

use X10WeaponStatsApi\Models\Attribute;

class AttributesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$attributes = Attribute::all()->sortBy('defindex', SORT_NUMERIC);
		
		return view('admin.attributes.index', ['attributes' => $attributes]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$attribute = new Attribute;
        
        return view('admin.attributes.form', ['attribute' => $attribute]); 
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$attribute = Attribute::create($request->all());
        
        return \Redirect::route('admin.attributes.show', [$attribute->defindex]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Attribute $attribute
	 * @return Response
	 */
	public function show(Attribute $attribute)
	{
		return view('admin.attributes.show', ['attribute' => $attribute]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Attribute $attribute
	 * @return Response
	 */
	public function edit(Attribute $attribute)
	{
		return view('admin.attributes.form', ['attribute' => $attribute]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Attribute $attribute
	 * @return Response
	 */
	public function update(Attribute $attribute, Request $request)
	{
		$attribute->fill($request->all())->save();
        
        return \Redirect::route('admin.attributes.show', ['attribute' => $attribute->defindex]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy(Attribute $attribute)
	{
		$attribute->delete();
		
		$attributes = Attribute::all()->sortBy('defindex', SORT_NUMERIC);
		
		return \Redirect::route('admin.attributes.index', ['attributes' => $attributes]);
	}

}
