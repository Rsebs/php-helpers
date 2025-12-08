<?php

namespace PhpHelpers\Http\Controllers;

use PhpHelpers\Helpers\ApiResponse;

class ControllerBase
{
  use ApiResponse;

  protected $model;

  /**
   * Display a listing of the resource.
   *
   * @param  \Closure  $callback - Must return a collection
   * @return \Illuminate\Http\Response
   */
  public function index(?callable $callback = null)
  {
    try {
      $dataCallback = null;
      if (!is_null($callback)) $dataCallback = $callback($this->model);

      $dataModel = $dataCallback ?? $this->model::all();
      return $this->successResponse($dataModel);
    } catch (\Throwable $th) {
      return $this->errorResponse($th->getMessage());
    }
  }
}
