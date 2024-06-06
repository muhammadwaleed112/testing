<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Billitem;
use App\Models\Pos;
use DB;
class createBill extends Controller
{
  public function index(){
    $data = array ('title'=> 'Bill Create');
    $products = Product::all();
    return view('inventory.CreateBill',(['data' => $data ,'products'=>$products]));
  }

  public function getProductName($id)
  {
      $product = Product::find($id);
      if ($product) {
          return response()->json(['name' => $product->measurment]);
      }
      return response()->json(['name' => 'Not found'], 404);
  }

   //calculate of total amount are there
 public function calculateTotal(Request $request)
 {
     $price = $request->input('price');
     $quantity = $request->input('quantity');

     if (is_numeric($price) && is_numeric($quantity)) {
         $total = $price * $quantity;
         return response()->json(['total' => $total]);
     }
      else {
         return response()->json(['error' => 'Invalid input'], 400);
     }
 }



 public function insert(Request $request)
 {
     // dd($request->toArray());
     $validator = Validator::make($request->all(), [
        'supplier' => 'required|string',
        'date' => 'required|date',
        'products.*.product_id' => 'required',
        'products.*.price' => 'required|numeric',
        'products.*.unit' => 'required|string',
        'products.*.qty' => 'required|integer',
    ], [
        'products.*.product_id.required' => 'The product ID field is required.',
        'products.*.price.required' => 'The price field is required.',
        'products.*.price.numeric' => 'The price must be a number.',
        'products.*.unit.required' => 'The unit field is required.',
        'products.*.unit.string' => 'The unit must be a string.',
        'products.*.qty.required' => 'The quantity field is required.',
        'products.*.qty.integer' => 'The quantity must be an integer.',
    ]);
   
    if ($validator->fails()) {
        return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
    }
     DB::beginTransaction();
 
     try {
         // Insert into the pos table
         $pos = DB::table('pos')->insertGetId([
             'supplier' => $request->supplier,
             'date' => $request->date,
             'total' => 0,
             'created_at' => now(),
             'updated_at' => now(),
         ]);
 
         $total = 0;
 
         foreach ($request->products as $product) {
             $unit = $product['unit'];
             $price = $product['price'];
             $qty = $product['qty'];
             $subTotal = $price * $qty;
             $total += $subTotal;
 
             DB::table('billitems')->insert([
                 'unit' => $unit,
                 'price' => $price,
                 'qty' => $qty,
                 'sub_total' => $subTotal,
                 'pos_id' => $pos,
                 'product_id' => $product['product_id'],
                 'created_at' => now(),
                 'updated_at' => now(),
             ]);
         }
 
         // Update the total in the pos table
         DB::table('pos')->where('id', $pos)->update(['total' => $total]);
 
         DB::commit();
 
         return response()->json(['status' => true, 'message' => 'Data successfully inserted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
 

 

 
//  public function show()
//  {
//      $details = Pos::with('billItems.product')->get();
//     //  dd($details->ToArray());
//      return view('inventory.BillDetail', ['details' => $details]);
//  }
 public function getDetails($id)
 {
     $pos = Pos::with('billItems.product')->findOrFail($id);
     // dd($pos->ToArray());
     return response()->json($pos);
 }
}
