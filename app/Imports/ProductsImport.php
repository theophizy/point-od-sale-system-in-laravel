@php
// app/Imports/ProductsImport.php
namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;

class ProductsImport implements ToModel, WithHeadingRow
{
protected $productController;

public function __construct()
{
$this->productController = new \App\Http\Controllers\Admin\ProductController;
}

public function model(array $row)
{
// Validate necessary fields
$requiredFields = ['name', 'price', 'quantity','weight', 'expiry_date', 'manufacture_date','expiry_date','manufacturer', 'sold_in'];
foreach ($requiredFields as $field) {
if (!isset($row[$field]) || empty($row[$field])) {
throw ValidationException::withMessages(["$field" => "The $field field is required in the Excel file."]);
}
}

$product = new Product();
$product->name = $row['name'];
$product->price = $row['price'];

$product->quantity = $row['quantity'];
$product->weight = $row['weight'];

$product->manufacture_date = $row['manufacture_date'];
$product->expiry_date = $row['expiry_date'];
$product->manufacturer = $row['manufacturer'];

// Use existing barcode generation logic

if ($row['sold_in'] == "PACKS") {
    $product->barcode = $this->productController->generateRandomBarcodeValue();
}
$product->save();
}
}
@endphp