<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pos;
use App\Models\Billitem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use DB;
class billGenerate extends Controller
{
    public function index() {
    $data = array('title' => 'Bill');
     $details = Pos::with('billItems.product')->get();
    //  dd($details->ToArray());
     return view('inventory.BillDetail', ['details' => $details, 'data' => $data]);
 }
      public function search(Request $request){$validator = Validator::make($request->all(),[
        'date' => 'required',
        'searchBy'=> 'required'
    ]);
    if ($validator->fails()) {
        return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
    }
    $searchBy = $request->searchBy; // Ensure this matches the 'name' attribute in your select element
    $date = $request->date;

    if ($searchBy == 'date') {
        $data = DB::table('pos')->whereDate('date', $date)->get();
        return response()->json(['status' => true, 'data' => $data]);
        
    } else if ($searchBy == 'created_at') {
        $formattedDate = date('Y-m-d', strtotime($date));

        $data = DB::table('pos')->whereDate('created_at', $formattedDate)->get();
        return response()->json(['status' => true, 'data' => $data]);
    }
    return response()->json(['status' => false, 'message' => 'Invalid search criteria']);
}

 public function delete(Request $request){
    $id = $request->id;
    Pos::find($id)->delete();
    Billitem::where('product_id',$id)->delete();
    return response()->json([
        'status' => true,
        'message' => 'Product deleted successfully.'
    ]);
}
public function billDetails($id){

        $pos = Pos::with('billItems.product')->findOrFail($id);
        // dd($pos->ToArray());
        return response()->json($pos);
    }

    public function editmy($id)
    {
        $bill = Pos::with('billItems.product')->find($id);

        // Fetch all products
        $allProducts = Product::all();

        // Return the view with the fetched data
        return view('inventory.update', [
            'bill' => $bill,
            'allProducts' => $allProducts
        ]);
    }
    public function insert(Request $request)
{
    // dd($request->ToArray());
    $id = $request->id;
    $request->validate([
        'supplier' => 'required|string',
        'date' => 'required|date',
        'products.*.product_id' => 'required',
        'products.*.price' => 'required|numeric',
        'products.*.qty' => 'required|integer',
        'products.*.unit' => 'required|string',
    ]);
    
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

        foreach ($request->products as $key => $product) {
            $price = $product['price'];
            $qty = $product['qty'];
            $subTotal = $price * $qty;
            $total += $subTotal;

           DB::table('billitems')->where('id', $id)->update([
               'qty' => $unit,
               'price' => $price,
                'sub_total' => $subTotal,
                'pos_id' => $pos,
                'product_id' => $product['product_id'][$key],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update the total in the pos table
        DB::table('pos')->where('id', $pos)->update(['total' => $total]);

        DB::commit();

        return response()->json(['success' => true, 'message' => 'Data successfully inserted']);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}
}
