<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Print Barcode</title>
    <style>
        @media print {
            /* Ensure printable section displays correctly */
            #printable-section {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                margin-bottom: 20px;
            }
        }

        .barcode-container {
            flex: 1;
            margin: 5px;
        }

        .barcode {
            width: 100%;
            max-width: 200px;
            margin: 10px;
        }
    </style>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script> -->
    <script src="{{ asset('admin/js/JsBarcode.all.min.js') }}"></script>
  </head>

<body>
    <div id="printable-section">
        @for ($i = 0; $i < $numRows; $i++)
            <div style="display: flex; justify-content: space-between;">
                @for ($j = 0; $j < $barcodePerRow; $j++)
                    @if (($i * $barcodePerRow) + $j < $numBarcodes)
                        <div class="barcode-container">
                            <svg class="barcode" jsbarcode-value="{{ $barcodeValue }}"></svg>
                           
                        </div>
                    @endif
                @endfor
            </div><br><br>
        @endfor
    </div>

    <script>
        window.onload = function() {
            // Generate barcodes using JsBarcode
            document.querySelectorAll('.barcode').forEach(function(element) {
                JsBarcode(element, element.getAttribute('jsbarcode-value'), {
                    format: "EAN13",
                    displayValue: true
                });
            });

            // Print the page and close after printing
            window.print();
            setTimeout(function() {
                window.close();
            }, 60000); // Close the window after 60 seconds
        }
    </script>
</body>

</html>
