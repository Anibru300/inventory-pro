<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Generar etiqueta para producto (formato DYMO compatible)
     */
    public function generateProductLabel(Request $request, Product $product)
    {
        $validated = $request->validate([
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'quantity' => 'integer|min:1|max:100',
            'label_size' => 'in:small,medium,large',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $labelSize = $validated['label_size'] ?? 'medium';
        
        // Obtener información del almacén si se especifica
        $warehouse = null;
        $stockInWarehouse = null;
        
        if (!empty($validated['warehouse_id'])) {
            $warehouse = Warehouse::find($validated['warehouse_id']);
            $stockLevel = $product->stockLevels()
                ->where('warehouse_id', $validated['warehouse_id'])
                ->first();
            $stockInWarehouse = $stockLevel?->quantity ?? 0;
        }

        // Generar código de barras (usar SKU o generar código EAN-13)
        $barcode = $product->barcode ?: $this->generateBarcode($product->sku);

        $labels = [];
        for ($i = 0; $i < $quantity; $i++) {
            $labels[] = [
                'product_name' => $this->truncate($product->name, 25),
                'product_sku' => $product->sku,
                'barcode' => $barcode,
                'barcode_type' => $this->detectBarcodeType($barcode),
                'price' => $product->selling_price,
                'price_formatted' => '$' . number_format($product->selling_price, 2),
                'warehouse' => $warehouse?->name,
                'stock' => $stockInWarehouse,
                'date' => now()->format('d/m/Y'),
                'company' => auth()->user()->tenant->name,
            ];
        }

        // Dimensiones según tamaño de etiqueta
        $dimensions = $this->getLabelDimensions($labelSize);

        return response()->json([
            'labels' => $labels,
            'dimensions' => $dimensions,
            'label_size' => $labelSize,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
            ],
            'dymo_xml' => $this->generateDymoXML($labels[0], $dimensions),
        ]);
    }

    /**
     * Generar etiquetas en lote para múltiples productos
     */
    public function generateBatchLabels(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'integer|min:1|max:20',
            'label_size' => 'in:small,medium,large',
        ]);

        $labelSize = $validated['label_size'] ?? 'medium';
        $dimensions = $this->getLabelDimensions($labelSize);
        $allLabels = [];

        foreach ($validated['products'] as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'] ?? 1;
            
            $barcode = $product->barcode ?: $this->generateBarcode($product->sku);
            
            for ($i = 0; $i < $quantity; $i++) {
                $allLabels[] = [
                    'product_name' => $this->truncate($product->name, 25),
                    'product_sku' => $product->sku,
                    'barcode' => $barcode,
                    'barcode_type' => $this->detectBarcodeType($barcode),
                    'price' => $product->selling_price,
                    'price_formatted' => '$' . number_format($product->selling_price, 2),
                    'date' => now()->format('d/m/Y'),
                    'company' => auth()->user()->tenant->name,
                ];
            }
        }

        return response()->json([
            'labels' => $allLabels,
            'dimensions' => $dimensions,
            'label_size' => $labelSize,
            'total_labels' => count($allLabels),
        ]);
    }

    /**
     * Obtener plantillas de etiquetas disponibles
     */
    public function getLabelTemplates()
    {
        return response()->json([
            'templates' => [
                [
                    'id' => 'dymo_30252',
                    'name' => 'DYMO 30252 - Dirección (1-1/8" x 3-1/2")',
                    'size' => 'medium',
                    'width_mm' => 89,
                    'height_mm' => 28,
                    'description' => 'Etiqueta estándar de dirección, ideal para productos',
                ],
                [
                    'id' => 'dymo_30256',
                    'name' => 'DYMO 30256 - Grande (2-5/16" x 4")',
                    'size' => 'large',
                    'width_mm' => 102,
                    'height_mm' => 59,
                    'description' => 'Etiqueta grande con más espacio para información',
                ],
                [
                    'id' => 'dymo_30336',
                    'name' => 'DYMO 30336 - Pequeña (1" x 2-1/8")',
                    'size' => 'small',
                    'width_mm' => 54,
                    'height_mm' => 25,
                    'description' => 'Etiqueta compacta para productos pequeños',
                ],
                [
                    'id' => 'dymo_99012',
                    'name' => 'DYMO 99012 - Compatible (36mm x 89mm)',
                    'size' => 'medium',
                    'width_mm' => 89,
                    'height_mm' => 36,
                    'description' => 'Etiqueta compatible Brother/DYMO',
                ],
            ],
        ]);
    }

    /**
     * Vista previa de etiqueta en HTML
     */
    public function previewLabel(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'label_size' => 'in:small,medium,large',
        ]);

        $product = Product::find($validated['product_id']);
        $labelSize = $validated['label_size'] ?? 'medium';
        $dimensions = $this->getLabelDimensions($labelSize);
        
        $barcode = $product->barcode ?: $this->generateBarcode($product->sku);

        $label = [
            'product_name' => $this->truncate($product->name, 25),
            'product_sku' => $product->sku,
            'barcode' => $barcode,
            'price_formatted' => '$' . number_format($product->selling_price, 2),
            'company' => auth()->user()->tenant->name,
        ];

        return response()->json([
            'html' => $this->generateLabelHTML($label, $dimensions),
            'dimensions' => $dimensions,
        ]);
    }

    /**
     * Generar código de barras si no existe
     */
    private function generateBarcode(string $sku): string
    {
        // Si el SKU ya es numérico y tiene 12-13 dígitos, usarlo
        if (preg_match('/^\d{12,13}$/', $sku)) {
            return $sku;
        }
        
        // Generar código EAN-13 basado en SKU
        $prefix = '200'; // Prefijo para productos internos
        $skuNumeric = preg_replace('/\D/', '', $sku);
        $skuPart = substr($skuNumeric, 0, 9);
        $skuPart = str_pad($skuPart, 9, '0', STR_PAD_LEFT);
        
        $base = $prefix . $skuPart;
        $checksum = $this->calculateEAN13Checksum($base);
        
        return $base . $checksum;
    }

    /**
     * Calcular checksum EAN-13
     */
    private function calculateEAN13Checksum(string $barcode): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $barcode[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        $mod = $sum % 10;
        return ($mod === 0) ? 0 : 10 - $mod;
    }

    /**
     * Detectar tipo de código de barras
     */
    private function detectBarcodeType(string $barcode): string
    {
        if (preg_match('/^\d{13}$/', $barcode)) {
            return 'EAN13';
        }
        if (preg_match('/^\d{12}$/', $barcode)) {
            return 'UPCA';
        }
        if (preg_match('/^\d{8}$/', $barcode)) {
            return 'EAN8';
        }
        return 'CODE128';
    }

    /**
     * Obtener dimensiones según tamaño
     */
    private function getLabelDimensions(string $size): array
    {
        return match($size) {
            'small' => [
                'width_mm' => 54,
                'height_mm' => 25,
                'width_px' => 204,
                'height_px' => 94,
            ],
            'large' => [
                'width_mm' => 102,
                'height_mm' => 59,
                'width_px' => 386,
                'height_px' => 223,
            ],
            default => [ // medium
                'width_mm' => 89,
                'height_mm' => 28,
                'width_px' => 336,
                'height_px' => 106,
            ],
        };
    }

    /**
     * Truncar texto
     */
    private function truncate(string $text, int $length): string
    {
        return strlen($text) > $length ? substr($text, 0, $length - 3) . '...' : $text;
    }

    /**
     * Generar XML compatible con DYMO Label Software
     */
    private function generateDymoXML(array $label, array $dimensions): string
    {
        $width = $dimensions['width_mm'] * 100; // Convertir a décimas de mm
        $height = $dimensions['height_mm'] * 100;

        return '<?xml version="1.0" encoding="utf-8"?>
<DieCutLabel Version="8.0" Units="twips">
  <PaperOrientation>Landscape</PaperOrientation>
  <Id>Address</Id>
  <PaperName>30252 Address</PaperName>
  <DrawCommands>
    <RoundRectangle X="0" Y="0" Width="' . $width . '" Height="' . $height . '" Radius="0"/>
  </DrawCommands>
  <ObjectInfo>
    <TextObject>
      <Name>PRODUCT_NAME</Name>
      <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
      <BackColor Alpha="0" Red="255" Green="255" Blue="255"/>
      <LinkedObjectName></LinkedObjectName>
      <Rotation>Rotation0</Rotation>
      <IsMirrored>False</IsMirrored>
      <IsVariable>False</IsVariable>
      <HorizontalAlignment>Left</HorizontalAlignment>
      <VerticalAlignment>Middle</VerticalAlignment>
      <TextFitMode>ShrinkToFit</TextFitMode>
      <UseFullFontHeight>True</UseFullFontHeight>
      <Verticalized>False</Verticalized>
      <StyledText>
        <Element>
          <String>' . htmlspecialchars($label['product_name']) . '</String>
          <Attributes>
            <Font Family="Arial" Size="10" Bold="True"/>
          </Attributes>
        </Element>
      </StyledText>
    </TextObject>
    <Bounds X="100" Y="100" Width="' . ($width - 200) . '" Height="400"/>
  </ObjectInfo>
  <ObjectInfo>
    <BarcodeObject>
      <Name>BARCODE</Name>
      <ForeColor Alpha="255" Red="0" Green="0" Blue="0"/>
      <BackColor Alpha="0" Red="255" Green="255" Blue="255"/>
      <LinkedObjectName></LinkedObjectName>
      <Rotation>Rotation0</Rotation>
      <IsMirrored>False</IsMirrored>
      <IsVariable>False</IsVariable>
      <Type>' . $label['barcode_type'] . '</Type>
      <Size>Small</Size>
      <TextPosition>Bottom</TextPosition>
      <TextFont Family="Arial" Size="8"/>
      <Data>' . $label['barcode'] . '</Data>
    </BarcodeObject>
    <Bounds X="100" Y="550" Width="' . ($width - 200) . '" Height="400"/>
  </ObjectInfo>
  <ObjectInfo>
    <TextObject>
      <Name>PRICE</Name>
      <ForeColor Alpha="255" Red="0" Green="100" Blue="0"/>
      <BackColor Alpha="0" Red="255" Green="255" Blue="255"/>
      <LinkedObjectName></LinkedObjectName>
      <Rotation>Rotation0</Rotation>
      <IsMirrored>False</IsMirrored>
      <IsVariable>False</IsVariable>
      <HorizontalAlignment>Right</HorizontalAlignment>
      <VerticalAlignment>Middle</VerticalAlignment>
      <TextFitMode>ShrinkToFit</TextFitMode>
      <UseFullFontHeight>True</UseFullFontHeight>
      <Verticalized>False</Verticalized>
      <StyledText>
        <Element>
          <String>' . $label['price_formatted'] . '</String>
          <Attributes>
            <Font Family="Arial" Size="12" Bold="True"/>
          </Attributes>
        </Element>
      </StyledText>
    </TextObject>
    <Bounds X="' . ($width - 1200) . '" Y="100" Width="1100" Height="300"/>
  </ObjectInfo>
</DieCutLabel>';
    }

    /**
     * Generar HTML para vista previa
     */
    private function generateLabelHTML(array $label, array $dimensions): string
    {
        $width = $dimensions['width_px'];
        $height = $dimensions['height_px'];
        
        return '<div style="
            width: ' . $width . 'px; 
            height: ' . $height . 'px; 
            border: 1px solid #000; 
            padding: 8px; 
            font-family: Arial, sans-serif;
            background: white;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        ">
            <div style="font-weight: bold; font-size: 12px; line-height: 1.2;">' . htmlspecialchars($label['product_name']) . '</div>
            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div style="font-size: 10px;">SKU: ' . $label['product_sku'] . '</div>
                <div style="font-weight: bold; font-size: 14px; color: green;">' . $label['price_formatted'] . '</div>
            </div>
            <div style="text-align: center; font-size: 9px; font-family: monospace; letter-spacing: 2px;">
                *' . $label['barcode'] . '*
            </div>
        </div>';
    }
}
