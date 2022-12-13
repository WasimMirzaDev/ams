<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Expensetype;

class ExpenseController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $exp = Expense::latest()->get();
       // dd($exp);
       // dd($expenses);
       $expense = [];
       $et = Expensetype::where('et_active', 1)->get();
       return view('expenses', get_defined_vars());
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       return view('expenses.create');
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
           'expensetype_id' => 'required',
           'expense_amount' => 'required',
           'expense_date' => 'required'
       ]);

       $request->e_active = empty($request->e_active) ? 0 : 1;
       $request->expense_date = date('Y-m-d', strtotime($request->expense_date));
      Expense::updateOrCreate(['id' => $request->id],
       [
         'expensetype_id' => $request->expensetype_id,
         'expense_amount' => $request->expense_amount,
         'expense_date' => $request->expense_date,
         'expense_description' => $request->expense_description
     ]
   );
       return redirect()->route('expenses.show')
                       ->with('success','Extras created successfully.');
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Product  $expense
    * @return \Illuminate\Http\Response
    */
   public function show(Product $expense)
   {
       return view('expenses.show',compact('expense'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $expense
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
     $e = Expense::latest()->get();
      $expense = Expense::where('id', $id)->first();
      $et = Expensetype::where('et_active', 1)->get();
       return view('expenses',get_defined_vars());
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
                       ->with('success','Expense updated successfully');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
     $deleted = Expense::find($id)->delete();
     if($deleted)
     {
       return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
     }
     return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
   }
}
