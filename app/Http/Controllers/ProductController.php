<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function list()
    {
        $products = Product::where('id', '!=', 1)
        ->orderBy('id', 'desc')
        ->get();
        return response()->json($products);
    }

    public function store(Request $request){

        $product = new Product();
        $product->sku = $request->sku;
        $product->product = $request->product;
        //$product->image = $request->image;
        $product->summary = $request->summary;
        $product->stock = $request->stock;
        $product->stock_notification = $request->stock_notification;
        $product->price = $request->price;
        $product->points = $request->points;
        $product->orden = $request->orden;
        $product->status = $request->status;
        $product->weight = $request->weight;

        $product->save();

        // image upload
        if($request->image){
            $name_new = Carbon::now()->timestamp."_".$product->id;
            $folder_path = "/uploads/products/";

            $img = $this->getB64Image($request->image);
            // Obtener la extensión de la Imagen
            $img_extension = $request->imageType;

            // Crear un nombre aleatorio para la imagen
            $img_name = $name_new .'.' . $img_extension;

            Storage::disk('images_base64')->put($img_name, $img);

            $product->image = $folder_path.$img_name;
            $product->update();
        }
        
        return response()->json($product);
    }

    public function show($id){
        $product = Product::find($id);
        return $product;
    }

    public function update($id, Request $request){
        $product = Product::findOrFail($id);
        $product->sku = $request->sku;
        $product->product = $request->product;
        $product->summary = $request->summary;
        $product->stock = $request->stock;
        $product->stock_notification = $request->stock_notification;
        $product->price = $request->price;
        $product->points = $request->points;
        $product->orden = $request->orden;
        $product->status = $request->status;
        $product->weight = $request->weight;

       // image upload
        if($request->image){
            if($product){
                if ($product->image) {
                    $path_file = base_path().'/public/'.$product->image;

                    if(file_exists($path_file)){
                        unlink($path_file);
                    }
                }
            }
            $name_new = Carbon::now()->timestamp."_".$product->id;
            $folder_path = "/uploads/products/";

            $img = $this->getB64Image($request->image);
            // Obtener la extensión de la Imagen
            $img_extension = $request->imageType;

            // Crear un nombre aleatorio para la imagen
            $img_name = $name_new .'.' . $img_extension;

            Storage::disk('images_base64')->put($img_name, $img);

            $product->image = $folder_path.$img_name;
        }

        $product->save();

        return response()->json($product);
    }

    public function getB64Image($base64_image){
        // Obtener el String base-64 de los datos
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        // Decodificar ese string y devolver los datos de la imagen
        $image = base64_decode($image_service_str);
        // Retornamos el string decodificado
        return $image;
    }

    public function destroy($id){
        $product = Product::find($id);
        if($product){
            $path_file = base_path().'/public/'.$product->image;

            if(file_exists($path_file)){
                unlink($path_file);
            }
            $product->delete();
        }
        return $product;
    }

    //For Others Microservices: Users-Shops
    public function index()
    {
        $products = Product::where('status', 'A')
                    ->where('id', '!=', 1)
                    ->orderBy('orden','asc')
                    ->get();
        //return $products;
        return response()->json($products);
    }

    public function product_affiliation()
    {
        $product_affiliation = Product::select('price','points')
                    ->where('status', 'A')
                    ->where('id', '=', 1)
                    ->get();
        return response()->json($product_affiliation);
    }

    public function stockproduct($id){
        $stockproduct = Product::select(array('id','sku','product','price', 'stock', 'stock_notification', 'points', 'weight'))
                ->where('id', '=', $id)
                ->get();
        
        return response()->json($stockproduct);
    }

    public function update_stockproduct($id, Request $request){
        $product = Product::findOrFail($id);
        $product->stock = $product->stock - $request->quantity;
        $product->update();

        return response()->json($product);
    }


    public function product_name($id)
    {
        $product_name = Product::select('product')
                    ->where('status', 'A')
                    ->where('id', '=', $id)
                    ->get();

        foreach ($product_name as $item) {
            $product = $item["product"];
        }

        return response()->json($product);
    }


    public function crontest(){

        $product = new Product();
        $product->sku = 'CRON001';
        $product->product = 'CRONTEST';
        $product->summary = Carbon::now();
        $product->stock = 1;
        $product->price = 1;
        $product->points = 1;
        $product->status = 1;

        /*
        //$product->image = $request->image;
        $product->summary = $request->summary;
        $product->stock = $request->stock;
        $product->stock_notification = $request->stock_notification;
        $product->price = $request->price;
        $product->points = $request->points;
        $product->orden = $request->orden;
        $product->status = $request->status;
        $product->weight = $request->weight;
        */

        $product->save();

        return response()->json($product);

    }
}
