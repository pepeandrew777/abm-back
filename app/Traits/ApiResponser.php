<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
	private function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['codigo' => $code, 'mensaje' => $message], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		return $this->successResponse(['data' => $collection], $code);
	}

	protected function showOne(Model $instance, $code = 200)
	{
		return $this->successResponse(['data' => $instance], $code);
	}

	protected function showMessage($message, $code = 200)
	{
		return response()->json([
			'codigo' => $code,
			'mensaje'=> $message,
		], $code);
	}
}