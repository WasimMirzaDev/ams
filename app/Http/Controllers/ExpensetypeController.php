<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expensetype;
class ExpensetypeController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $et = Expensetype::latest()->get();
       // dd($expensetypes);
       $expensetype = [];
       return view('expensetypes',compact(['et', 'expensetype']));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       return view('expensetypes.create');
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
       $request->validate([
           'et_name' => 'required'
       ]);

       $request->et_active = empty($request->et_active) ? 0 : 1;

      Expensetype::updateOrCreate(['et_id' => $request->id],
       [
         'et_name' => $request->et_name,
         'et_active' => $request->et_active,
     ]
   );
       return redirect()->route('expensetypes.show')
                       ->with('success','Extras created successfully.');
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Product  $expensetype
    * @return \Illuminate\Http\Response
    */
   public function show(Product $expensetype)
   {
       return view('expensetypes.show',compact('expensetype'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $expensetype
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
     $et = Expensetype::latest()->get();
      $expensetype = Expensetype::where('et_id', $id)->first();
       return view('expensetypes',get_defined_vars());
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Product $product)
   {
       $request->validate([
           'name' => 'required',
           'detail' => 'required',
       ]);

       $product->update($request->all());

       return redirect()->route('products.index')
                       ->with('success','Expense type updated successfully');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function destroy(Product $product)
   {
       $product->delete();

       return redirect()->route('products.index')
                       ->with('success','Expense type deleted successfully');
   }
}
