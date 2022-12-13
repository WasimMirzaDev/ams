<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Building;
use Validator;
class UnitController extends Controller
{
  /**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
public function index()
{

  $list = Unit::all();
  $buildings = Building::all();
  $next_number = Unit::max('number')+1;
    return view('units', get_defined_vars());
}

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
public function create()
{
    //
}

/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
      'name' => 'required',
    //   'number' => 'required|unique:units,number,' . $request->id,
      'building_id' => 'required',
    ]);

    if ($validator->passes()) {
      $unit   =   unit::updateOrCreate(
                      [
                          'id' => $request->id
                      ],
                      [
                        'name'           => $request->name,
                        'number'         => $request->number,
                        'weekly_rent'    => empty($request->weekly_rent)  ? 0 : $request->weekly_rent,
                        'monthly_rent'   => empty($request->monthly_rent) ? 0 : $request->monthly_rent,
                        'yearly_rent'    => empty($request->yearly_rent)  ? 0 : $request->yearly_rent,
                        'description'    => $request->description,
                        'building_id'    => $request->building_id,
                      ]);
      if($unit)
      {
        $b = array_merge($request->all(), ['building_name' => $unit->building->name]);
        $b['id'] = $unit->id;
        return response()->json(['success'=>1, 'msg'=>'Saved Successfully!', 'data' => $b]);
      }
    }
    return response()->json(['success' => 0, 'msg'=>$validator->errors()->all()]);
}

/**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function show($id)
{
    //
}

/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function edit($id)
{
  $Unit = Unit::find($id);
  return response()->json($Unit);
}

/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, $id)
{
    //
}

/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function destroy($id)
{
    $deleted = Unit::find($id)->delete();
    if($deleted)
    {
      return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
    }
    return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
}
}
