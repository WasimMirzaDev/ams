<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeHead;
class ExtrasController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $fh = FeeHead::latest()->get();
       // dd($extras);
       $extra = [];
       return view('extras',compact(['fh', 'extra']));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       return view('extras.create');
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
           'fh_name' => 'required',
           'fh_amount' => 'required',
       ]);

       $request->fh_active = empty($request->fh_active) ? 0 : 1;

      FeeHead::updateOrCreate(['fh_id' => $request->id],
       [
         'fh_name' => $request->fh_name,
         'fh_amount' => $request->fh_amount,
         'fh_active' => $request->fh_active,
     ]
   );
       return redirect()->route('extras.show')
                       ->with('success','Extras created successfully.');
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Product  $extra
    * @return \Illuminate\Http\Response
    */
   public function show(Product $extra)
   {
       return view('extras.show',compact('extra'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $extra
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
     $fh = FeeHead::latest()->get();
      $extra = FeeHead::where('fh_id', $id)->first();
       return view('extras',get_defined_vars());
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
                       ->with('success','Product updated successfully');
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
                       ->with('success','Product deleted successfully');
   }
}
