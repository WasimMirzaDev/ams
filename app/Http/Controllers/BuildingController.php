<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Building;
class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $list = Building::all();
        return view('buildings', get_defined_vars());
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
          'address' => 'required',
        ]);
        if ($validator->passes()) {
          $building   =   building::updateOrCreate(
                          [
                              'id' => $request->id
                          ],
                          [
                              'name'    => $request->name,
                              'address' => $request->address,
                              'units'    => empty($request->units) ? 0 : $request->units,
                          ]);
          if($building)
          {
            $r = $request->all();
            $r['id'] = $building->id;
            return response()->json(['success'=>1, 'msg'=>'Saved Successfully!', 'data' => $r]);
          }
        }
        return response()->json(['success' => 0, 'msg'=>$validator->errors()->all()]);
    }

    public function save_building(request $request)
    {
      $request->validate([
              'address_name' => 'required'
           ],
           [
              'address_name.required' => 'Address Name is required'
          ]);
          $address =   Building::create(
                          [
                            'name'           => $request->address_name,
                            'address'           => $request->address_name,
                          ]
                        );
           return redirect()->route('units.show')
                           ->with(['success'=>'Address Added successfully.', 'selected_address' => $address->id]);
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
      $Building = Building::find($id);
      return response()->json($Building);
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
        $deleted = Building::find($id)->delete();
        if($deleted)
        {
          return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
        }
        return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
    }
}
