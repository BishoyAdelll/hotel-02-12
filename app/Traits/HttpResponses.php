<?php

namespace App\Traits;


trait HttpResponses{

    public function success($data, $message=null , $code=200)
    {
        return response()->json([
            'status' => 'request was successfull',
            'data' => $data,
            'message' => $message
        ],$code);
    }
    public function error($data, $message=null , $code)
    {
        return response()->json([
            'status' => 'error has occurred',
            'data' => $data,
            'message' => $message
        ],$code);
    }

}
