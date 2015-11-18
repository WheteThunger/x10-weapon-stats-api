<?php namespace X10WeaponStatsApi\Http\Controllers\Admin;

use X10WeaponStatsApi\Http\Requests;
use X10WeaponStatsApi\Http\Controllers\Controller;

use Illuminate\Http\Request;
use X10WeaponStatsApi\Models\Config;

class ConfigsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $configs = Config::all()->sortBy('id', SORT_NUMERIC);

        return view('admin.configs.index', ['configs' => $configs]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $config = new Config();

        return view('admin.configs.form', ['config' => $config]);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $config = Config::create($request->all());

        return \Redirect::route('admin.configs.show', [$config->id]);
	}

    /**
     * Display the specified resource.
     *
     * @param Config $config
     * @return Response
     */
	public function show(Config $config)
	{
        $t = 1;

        return view('admin.configs.show', ['config' => $config]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param Config $config
     * @return Response
     */
	public function edit(Config $config)
	{
        return view('admin.configs.form', ['config' => $config]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Config $config
     * @param Request $request
	 * @return Response
	 */
	public function update(Config $config, Request $request)
    {
        $config->fill($request->all())->save();

        return \Redirect::route('admin.configs.show', ['config' => $config->id]);
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Config $config
	 * @return Response
	 */
    public function destroy(Config $config)
    {
        $config->delete();

        $configs = Config::all()->sortBy('defindex', SORT_NUMERIC);

        return \Redirect::route('admin.configs.index', ['configs' => $configs]);
    }

}
