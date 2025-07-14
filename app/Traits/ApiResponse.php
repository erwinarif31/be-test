<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/
trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed|null  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @param LengthAwarePaginator<array-key,mixed> $pagination
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data = null, LengthAwarePaginator $pagination = null, string $message = 'OK', int $statusCode = 200): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($pagination !== null && $pagination instanceof LengthAwarePaginator) {
            $response['pagination'] = [
                "total" => $pagination->total(),
                "per_page" => $pagination->perPage(),
                "current_page" => $pagination->currentPage(),
                "last_page" => $pagination->lastPage(),
                "current_page_url" => $pagination->url($pagination->currentPage()),
                "first_page_url" => $pagination->url(1),
                "last_page_url" => $pagination->url($pagination->lastPage()),
                "next_page_url" => $pagination->nextPageUrl(),
                "prev_page_url" => $pagination->previousPageUrl(),
                "path" => $pagination->path(),
                "from" => $pagination->firstItem(),
                "to" => $pagination->lastItem(),
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  mixed|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message, int $statusCode, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
