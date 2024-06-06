<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Product;
use App\Models\Billitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller{
    
    //dashboard view
    public function dashboard(){
        $data = array(
            'title' => 'Dashboard',
        );
        return view('index',$data);
}
    public function index(){
        // $data = Product::all();
        // dd($data->ToArray());
        return view('inventory/products');
    }
    public function edit(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);
        return response()->json($product);
    }
    
    public function update(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);
        $existing = Product::where('name',$request->name)->whereNull('deleted_at')->first();
        
        if ($existing != Null) {
            $message = "Product Already Existed";
            return response()->json(['status' => false , 'message' =>  $message]);
        }
        else{
            $product->update([
                'name' => $request->name,
                'measurment' => $request->measurement,
            ]);
            return response()->json([
                'status' => true,
            ]);
        }
    }
    //insert Prodcust
    public function insert(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'measurement'=> 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->all()]);
        }
        $existingProduct = Product::where('name', $request->name)
            ->whereNull('deleted_at')
            ->first();
        
        if ($existingProduct !== null) {
            $message = "The name has already been taken.";
            return response()->json(['status' => false, 'message' => $message], 409);
        } else {
            $product = new Product();
            $product->name = $request->name;
            $product->measurment = $request->measurement; // Corrected the spelling to 'measurement' assuming it's a typo
            $product->save();
        
            $message = "Product successfully added.";
            return response()->json(['status' => true , 'message' => $message], 200);
        }
        
    }

    //show data
    public function show(){
    //    $data =  DB::table('products')->get();
    $data = Product::get();    
    return response()->json(['data' => $data]);
    }

    //delete dat
    // public function status(Request $request) {
    //     $id = $request->id;
    //     $currentStatus = DB::table('products')->where('id', $id)->value('status');
    
    //     $updatedStatus = $currentStatus == 0 ? 1 : 0;

    //     DB::table('products')->where('id', $id)->update(['status' => $updatedStatus]);
    
    //     return response()->json([
    //         'status' => true,
    //         'updatedStatus' => $updatedStatus
    //     ]);
    // }
    public function delete(Request $request){
        $id = $request->id;
        Product::find($id)->delete();
        Billitem::where('product_id',$id)->delete();
        return response()->json([
            'status' => true,                    
            'message' => 'Product deleted successfully.'
        ]);
    }


}