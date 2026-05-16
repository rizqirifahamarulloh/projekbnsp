<?php

namespace App\Traits;

/**
 * Trait untuk standarisasi format respons API
 * Format: { success, message, data, meta }
 * [BNSP: Membuat Kode Program Aplikasi]
 */
trait ApiResponse
{
    /**
     * Response sukses
     */
    protected function successResponse($data = null, string $message = 'Berhasil', int $code = 200, $meta = null)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if ($meta) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Response error
     */
    protected function errorResponse(string $message = 'Terjadi kesalahan', int $code = 400, $data = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Response dengan pagination meta
     */
    protected function paginatedResponse($paginator, $data, string $message = 'Berhasil')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
        ]);
    }
}
