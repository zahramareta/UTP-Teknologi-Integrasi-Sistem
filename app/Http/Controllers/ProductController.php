<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Products",
    description: "API Products"
)]
class ProductController extends Controller
{
    private array $products = [
        ['id' => 1, 'nama' => 'Laptop', 'harga' => 8000000, 'stock' => 5],
        ['id' => 2, 'nama' => 'Mouse', 'harga' => 150000, 'stock' => 10]
    ];

    #[OA\Get(
        path: "/api/products",
        tags: ["Products"],
        summary: "Ambil semua produk",
        operationId: "getProducts"
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data produk"
    )]
    public function index()
    {
        return response()->json($this->products);
    }

    #[OA\Get(
        path: "/api/products/{id}",
        tags: ["Products"],
        summary: "Ambil produk berdasarkan ID",
        operationId: "getProductById"
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID Produk",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Produk ditemukan"
    )]
    #[OA\Response(
        response: 404,
        description: "Produk tidak ditemukan"
    )]
    public function show($id)
    {
        foreach ($this->products as $product) {
            if ($product['id'] == $id) {
                return response()->json($product);
            }
        }

        return response()->json([
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    #[OA\Post(
        path: "/api/products",
        tags: ["Products"],
        summary: "Tambah produk",
        operationId: "createProduct"
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nama", "harga", "stock"],
            properties: [
                new OA\Property(property: "nama", type: "string", example: "Keyboard"),
                new OA\Property(property: "harga", type: "integer", example: 300000),
                new OA\Property(property: "stock", type: "integer", example: 15)
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Produk berhasil ditambahkan"
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'harga' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        $newProduct = [
            'id' => count($this->products) + 1,
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'stock' => $validated['stock']
        ];

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => $newProduct
        ], 201);
    }

    #[OA\Put(
        path: "/api/products/{id}",
        tags: ["Products"],
        summary: "Update semua data produk",
        operationId: "updateProduct"
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID Produk",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nama", "harga", "stock"],
            properties: [
                new OA\Property(property: "nama", type: "string", example: "Monitor"),
                new OA\Property(property: "harga", type: "integer", example: 2000000),
                new OA\Property(property: "stock", type: "integer", example: 7)
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Produk berhasil diupdate"
    )]
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'harga' => 'required|numeric',
            'stock' => 'required|integer'
        ]);

        foreach ($this->products as &$product) {
            if ($product['id'] == $id) {

                $product['nama'] = $validated['nama'];
                $product['harga'] = $validated['harga'];
                $product['stock'] = $validated['stock'];

                return response()->json([
                    'message' => 'Produk berhasil diupdate',
                    'data' => $product
                ]);
            }
        }

        return response()->json([
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    #[OA\Patch(
        path: "/api/products/{id}",
        tags: ["Products"],
        summary: "Update stock produk",
        operationId: "updateProductStock"
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID Produk",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["stock"],
            properties: [
                new OA\Property(property: "stock", type: "integer", example: 20)
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Stock berhasil diupdate"
    )]
    public function updateStock(Request $request, $id)
    {
        $validated = $request->validate([
            'stock' => 'required|integer'
        ]);

        foreach ($this->products as &$product) {
            if ($product['id'] == $id) {

                $product['stock'] = $validated['stock'];

                return response()->json([
                    'message' => 'Stock berhasil diupdate',
                    'data' => $product
                ]);
            }
        }

        return response()->json([
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    #[OA\Delete(
        path: "/api/products/{id}",
        tags: ["Products"],
        summary: "Hapus produk",
        operationId: "deleteProduct"
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID Produk",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Produk berhasil dihapus"
    )]
    public function destroy($id)
    {
        foreach ($this->products as $key => $product) {

            if ($product['id'] == $id) {

                unset($this->products[$key]);

                return response()->json([
                    'message' => "Produk dengan ID $id berhasil dihapus"
                ]);
            }
        }

        return response()->json([
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }
}
