<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Erro de validação
        if ($exception instanceof ValidationException) { 
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors'  => $exception->errors()
            ], 422);
        }

        // Model não encontrado
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Recurso não encontrado',
                'errors'  => [
                    'id' => ['O recurso solicitado não existe.']
                ]
            ], 404);
        }

        // Outros erros genéricos
        return response()->json([
            'success' => false,
            'message' => 'Erro interno no servidor',
            'errors'  => [
                'exception' => [$exception->getMessage()]
            ]
        ], 500);
    }
}
