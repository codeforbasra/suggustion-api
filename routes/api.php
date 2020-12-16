<?php

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
 'throttle:20,1',
], function ($router) {

 // Suggestions Routes
 Route::apiResource('suggestions', 'SuggestionController');

});

// Not Found
Route::fallback(function () {
 return response()->json(['message' => 'Not Found.'], 404);
});
