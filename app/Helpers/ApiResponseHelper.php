<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;
use Validator;

trait ApiResponseHelper
{
    protected Request $request;
    protected array $body;

    public function setData($data)
    {
        $this->body['data'] = $data;
        return $this;
    }


    public function setError($error)
    {
        $this->body['status'] = 'error';
        $this->body['message'] = $error;
        return $this;
    }

    public function setSuccess($message)
    {
        $this->body['status'] = 'success';
        $this->body['message'] = $message;
        return $this;
    }
    public function setMessage($message)
    {
        $this->body['message'] = $message;
        return $this;
    }

    public function setCode($code)
    {
        $this->body['code'] = $code;
        return $this;
    }


    public function send()
    {
        return response()->json($this->body, $this->body['code']);
    }

    public function json($code, $data, $message = '')
    {
        $this->setCode($code);
        $this->setData($data);
        $this->setMessage($message);
        return response()->json($this->body, $this->body['code']);
    }

    public function message($code, $message)
    {
        $this->setCode($code);
        $this->setMessage($message);
        return response()->json($this->body, $this->body['code']);
    }

    public function error($code, $data)
    {
        $this->setCode($code);
        $this->setError($data);
        return response()->json($this->body, $this->body['code']);
    }

    public function sendCollection($collection, $code): JsonResponse
    {

        return response()->json($collection, 200);
    }
}
