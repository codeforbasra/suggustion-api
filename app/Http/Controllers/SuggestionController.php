<?php

namespace App\Http\Controllers;

use App\Http\Controllers\APIController;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Validator;

class SuggestionController extends APIController
{
 /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index(Suggestion $suggestions)
 {

  try {
   return response()->json($suggestions->latest()->paginate(30), 200);
  } catch (\Exception $e) {
   return $this->responseServerError($e->getMessage());
  }
 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(Request $request, Suggestion $suggestion)
 {

  $validator = Validator::make($request->only('description'),
   [
    'description' => 'required|string',

   ], [
    'description.required' => 'الوصف مطلوب',
   ]
  );

  if ($validator->fails()) {
   return $this->responseUnprocessable($validator->errors()->first());
  }
  try {
   $suggestion = $suggestion->create($request->only('description'));

   return $this->responseResourceCreated('تم ارسال المقترح بنجاح');
  } catch (\Exception $e) {
   return $this->responseServerError($e->getMessage());
  }
 }

 /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
 public function show(Suggestion $suggestion)
 {
  try {
   return response()->json($suggestion, 200);
  } catch (\Exception $e) {
   return $this->responseServerError($e->getMessage());
  }
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
//  public function update(Request $request, Suggestion $suggestion)
 //  {

//   $validator = Validator::make($request->only('description'),
 //    [
 //     'name'        => 'required|string',
 //     'description' => 'required|string',

//    ], [
 //     'name.required'        => 'الاسم مطلوب',
 //     'description.required' => 'الوصف مطلوب',
 //    ]
 //   );

//   if ($validator->fails()) {
 //    return $this->responseUnprocessable($validator->errors()->first());
 //   }
 //   try {
 //    $suggestion->update($request->only('description'));
 //    return $this->responseResourceUpdated();
 //   } catch (\Exception $e) {
 //    return $this->responseServerError($e->getMessage());
 //   }
 //  }

 /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
//  public function destroy(Suggestion $suggestion)
 //  {
 //   try {
 //    $suggestion->delete();
 //    return $this->responseResourceDeleted();
 //   } catch (\Exception $e) {
 //    return $this->responseServerError($e->getMessage());
 //   }
 //  }
}
