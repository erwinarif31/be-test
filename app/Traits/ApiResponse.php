<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

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
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data = null, string $message = 'OK', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            if (method_exists($data, 'items')) {
                $response['data'] = $data->items();
                $response['pagination'] = [
                    "total" => $data->total(),
                    "per_page" => $data->perPage(),
                    "current_page" => $data->currentPage(),
                    "last_page" => $data->lastPage(),
                    "current_page_url" => $data->url($data->currentPage()),
                    "first_page_url" => $data->url(1),
                    "last_page_url" => $data->url($data->lastPage()),
                    "next_page_url" => $data->nextPageUrl(),
                    "prev_page_url" => $data->previousPageUrl(),
                    "path" => $data->path(),
                    "from" => $data->firstItem(),
                    "to" => $data->lastItem(),
                ];
            } else {
                $response['data'] = $data;
            }
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

    /**
 * Return a pagination JSON response from eloquent collection panginate().
    */

    protected function paginate($data, string $message = 'OK', int $statusCode = 200): JsonResponse
    {
        $pagination = [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
        ];

        return $pagination;
    }
}
