<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    public function createProduct(Request $request)
    {
        // Validate request data
        if ($request->isMethod('post')) {
            //dd($request->all());
            $request->validate([
                'name' => 'required|string',
                'price' => 'required',
                'quantity' => 'required',
                'sold_in' => 'required',
                'manufacturer' => 'required',
                'manufacture_date' => 'required',
                'expiry_date' => 'required',
                'weight' => 'required',
                // Add other validation rules as needed
            ]);
            // Create product
            $product = new Product();
            $product->admin_id = Auth::guard('admin')->user()->id;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->sold_in = $request->sold_in;
            $product->weight = $request->weight;
            $product->manufacture_date = $request->manufacture_date;
            $product->expiry_date = $request->expiry_date;
            $product->manufacturer = $request->manufacturer;
            // Set other product attributes
            $generetdeBarcode = "";
            if ($request->sold_in == "PACKS") {


                // $generetdeBarcode = $this->barcodeService->generateBarcode($this->generateRandomBarcodeValue());
                // Save the barcode to the product
                $barcodeGeneratedValue = $this->generateRandomBarcodeValue();
                // $checkDigit = $this->calculateEAN13CheckDigit($barcodeGeneratedValue);
                // $barcodeGeneratedValue .= $checkDigit;
                $product->barcode = $barcodeGeneratedValue;
            }

            $createdProduct = $product->save();
            if ($createdProduct && $request->sold_in == "PACKS") {
                echo '<script>window.open("' . route('print_barcode', ['barcodeValue' => $barcodeGeneratedValue]) . '","_blank");</script>';
                //return redirect()->back();
                // return redirect()->route('print.barcode', ['barcodeValue'=>$this->generateRandomBarcodeValue()]);
                // return view('admin.product.create_product', compact('product', 'generetdeBarcode'));
                //return response()->json(['message' => 'Product created successfully'], 201);
            } elseif ($createdProduct && $request->sold_in == "UNITS") {

                return redirect()->route('product_create')->with('success_message', 'Product created successfully!');
            } else {
                return redirect()->route('product_create')->with('error_message', 'Failed to create the product. Kindly contact Admin');
            }
        }
        return view('admin.product.create_product');
    }

    public function generateRandomBarcodeValue()
    {
        $dateTime = now();
        // mt_rand(1000, 9999)
        $barcodeValue = $dateTime->format('ymdHis');

        $randomDigits = mt_rand(0, 999999); // Generate 6 random digits
        $barcodeValue .= str_pad($randomDigits, 6, '0', STR_PAD_LEFT); // Ensure 6 digits with leading zeros if necessary

        // Ensure that the barcode value is exactly 12 digits long
        $barcodeValue = substr($barcodeValue, 0, 12);
        $checkDigit = $this->calculateEAN13CheckDigit($barcodeValue);
        $barcodeValue .= $checkDigit;
        return $barcodeValue;
    }

    function calculateEAN13CheckDigit($barcodeData)
    {
        // Ensure that the input consists of exactly 12 digits

        if (strlen($barcodeData) !== 12 || !ctype_digit($barcodeData)) {
            return redirect()->route('Product_create')->with('error_message', 'An error occured!');
        }

        // Calculate the check digit
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int)$barcodeData[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        $checkDigit = (10 - ($sum % 10)) % 10;

        return $checkDigit;
    }

    //     public function printBarcode($barcodeValue){
    //        $htmlCode = $this->generateEAN13Barcode(substr($barcodeValue, 0, 12), substr($barcodeValue, -1));

    //          //$barcodeHTML = base64_encode($barcodeHTML23);
    //        // dd($barcodeHTML,$barcodeValue);
    //        $productDetails = Product::where('barcode',$barcodeValue)->first();

    //      /* $htmlCode= " <div style='text-align:center'>
    //  <img alt='barcode' src='https://barcode.tec-it.com/barcode.ashx?data={$barcodeValue}&code=EAN13&translate-esc=on'/> 
    //  </div>"; */
    //  $numBarcodes = $productDetails->quantity;
    //  $barcodePerRow = 3;
    //  $numRows = ceil($numBarcodes/ $barcodePerRow);
    //       $htmlCode .= '<p style="text-align:left;  margin-top:-1px;margin-left:-5px;">' . htmlspecialchars($barcodeValue) . '</p>';
    //     return view('admin.product.print_barcode', compact('htmlCode','numBarcodes','barcodePerRow','numRows','barcodeValue'));
    //     }

    public function printBarcode($barcodeValue)
    {
        $productDetails = Product::where('barcode', $barcodeValue)->first();
        $numBarcodes = $productDetails->quantity;
        $barcodePerRow = 3;
        $numRows = ceil($numBarcodes / $barcodePerRow);

        return view('admin.product.print_barcode', compact('barcodeValue', 'numBarcodes', 'barcodePerRow', 'numRows'));
    }

    public function generateEAN13Barcode($gtin, $checkinDigit)
    {

        try { // Ensure that the GTIN is exactly 12 digits long
            if (strlen($gtin) !== 12 || !is_numeric($gtin)) {
                return "Invalid GTIN format. Must be a 12-digit numeric value.";
            }

            // Calculate the check digit
            // $checkDigit = $this->calculateEAN13CheckDigit($gtin);

            // Generate the EAN-13 barcode
            $barcodeValue = $gtin . $checkinDigit;
            //dd($barcodeValue)
            $barcodeClass = new DNS1D();
            return $barcodeClass->getBarcodeHTML($barcodeValue, "EAN13", 1);
            //return $barcodeClass->getBarcodeHTML($barcodeValue, 'EAN13',1.1,50);


        } catch (\Exception $e) {
            // Log the error
            Log::error("Error generating barcode: " . $e->getMessage());

            // Return a default error message or handle the error as needed
            return "Error generating barcode. Please try again.";
        }
    }


    public function searchByName($name)
    {

        //    // $products = Product::where('name', 'like', "%{$name}%")->first();
        //     $products = Product::where('name', $name,)->where('sold_in','UNITS')->where('quantity', '>', 0)->first();
        //     if (!$products) {
        //         return response()->json(['message' => 'No products found'], 404);
        //     }
        $products = Product::where('name', 'like', "%$name%")->where('sold_in', 'UNITS')->where('quantity', '>', 0)->where('status', 'ACTIVE')->get();
        if (!$products) {
            return response()->json(['message' => 'No products found'], 404);
        }
        return response()->json($products);
        // Return the products in a JSON response


    }

    public function findByBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->where('quantity', '>', 0)->where('status', 'ACTIVE')->first();
        // dd($product);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json($product);
    }


    public function getProductById($id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json($product);
    }



    public function viewAllProducts()
    {
        $products = Product::orderBy('quantity', 'ASC')->paginate(10);
        return view('admin.product.view_product')->with(compact('products'));
    }

    public function viewProductById($id)
    {
        $productDetails = Product::find($id);

        return view('admin.product.edit_product')->with(compact('productDetails'));
    }

    public function deleteProduct($id)
    {

        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->route('product_view')->with('success_message', 'Product successfully deleted.');
        } catch (\Exception $e) {
            return redirect()->route('product_view')->with('error_message', $e->getMessage());
        }
    }


    public function editProduct(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
                'name' => 'required',
                'price' => 'required|numeric',
            ];
            $customMessages = [
                'name.required' => 'Product name  is required',
                'price.required' => 'Product price must be entered',
                'price.numeric' => 'Product price must be a numeric figure',
                'quantity.required' => 'Product quantity must be greater than zero',
                'quantity.numeric' => 'Product quantity must be a numeric figure',
                'weight.required' => 'Weight(ML/ML) is required',
                'manufacture_date.required' => 'Manufacture date is required',
                'expiry_date.required' => 'Epiry date is required',
                'manufacturer.required' => 'Manufacturer is required',
                'status.required' => 'Status is required',

            ];
            $this->validate($request, $rules, $customMessages);

            $updateProduct = Product::where('id', $request->id)->update([
                'name' => $request->name, 'price' => $request->price, 'quantity' => $request->quantity, 'weight' => $request->weight, 'manufacture_date' => $request->manufacture_date, 'expiry_date' => $request->expiry_date, 'manufacturer' => $request->manufacturer, 'status' => $request->status
            ]);

            if ($updateProduct) {
                return redirect()->route('product_view')->with('success_message', 'Product edited successfully');
            } else {
                return redirect()->back()->with('error_message', 'Oops! an error occured. Kindly check your entries');
            }
        }
    }

    public function viewProductsWithLowQuantity()
    {
        $lowQantityProducts = Product::where('quantity', '<', 20)->orderBy('quantity', 'ASC')->paginate(10);
        return view('admin.product.product_lowQuantity', compact('lowQantityProducts'));
    }

    public function viewExpiredOrExpiringProducts()
    {
        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);
        $getExpiringOrExpiredProducts = Product::where('expiry_date', '<', $today)
            ->orWhereBetween('expiry_date', [$today, $thirtyDaysFromNow])
            ->paginate(10);
        return view('admin.product.product_expiredOrExpiring', compact('getExpiringOrExpiredProducts'));
    }

    //ProductExcelUpload
    public function ProductExcelUpload(Request $request)
    {

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);


        try {
        $path = $request->file('excel_file')->getRealPath();

        $path = $request->file('excel_file')->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $header = array_shift($rows); // Remove the first row and use it as a header
        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            $validator = Validator::make($data, [
                'quantity' => 'required|integer',
                'expiry_date' => 'required|date',
                'manufacture_date' => 'required|date',
                'price' => 'required|numeric',
                'sold_in' => 'required|string',
                'name' => 'required|string',
                'weight' => 'required|string',
                'manufacturer' => 'required|string',   
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $product = new Product();
            $product->name = $data['name'];
            $product->price = $data['price'];
            $product->quantity = $data['quantity'];
            $product->weight = $data['weight'];
            $product->manufacture_date = $data['manufacture_date'];
            $product->expiry_date = $data['expiry_date'];
            $product->manufacturer = $data['manufacturer'];
            $product->sold_in = $data['sold_in'];
$product->admin_id = Auth::guard('admin')->user()->id;
            // Generate barcode if sold in packs
            if ($data['sold_in'] == 'PACKS') {
                $product->barcode = $this->generateRandomBarcodeValue();
            }

            $product->save();
        }

        return redirect()->back()->with('success_message', 'Products uploaded successfully');
    } catch ( \Exception $e) {
        // Log the exception
        Log::error('Error importing products: ' . $e->getMessage());
        // Redirect back with an error message
        return redirect()->back()->with('error_message', 'An error occurred while uploading the products. Please try again.'. $e->getMessage());
    }
    }

}
