<?php namespace X10WeaponStatsApi\Http\Controllers\Admin;

use X10WeaponStatsApi\Http\Requests;
use X10WeaponStatsApi\Http\Controllers\Controller;

use Illuminate\Http\Request;

use X10WeaponStatsApi\Models\Person;


class PeopleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
public function index()
	{
		$people = Person::all()->sortBy('id', SORT_NUMERIC);
		
		return view('admin.people.index', ['people' => $people]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$person = new Person;
        
        return view('admin.people.form', ['person' => $person]); 
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$person = Person::create($request->all());
        
        return \Redirect::route('admin.people.show', [$person->id]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Person $person
	 * @return Response
	 */
	public function show(Person $person)
	{
		return view('admin.people.show', ['person' => $person]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Person $person
	 * @return Response
	 */
	public function edit(Person $person)
	{
		return view('admin.people.form', ['person' => $person]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Person $person
	 * @return Response
	 */
	public function update(Person $person, Request $request)
	{
		$person->fill($request->all())->save();
        
        return \Redirect::route('admin.people.show', ['person' => $person->id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy(Person $person)
	{
		$person->delete();
		
		$people = Person::all()->sortBy('id', SORT_NUMERIC);
		
		return \Redirect::route('admin.people.index', ['people' => $people]);
	}

}
