<?php
use Illuminate\Support\Facades\Cache;
use App\Exceptions\BadRequestExcept;
use App\Exceptions\ValidationExcept;
use Illuminate\Support\Facades\Validator;


// class ApiResponse{
//     public static function success($data = null, $message = "Success", $code = 200){
//         return response()->json([
//             'error' => false,
//             'status' => 'success',
//             'message' => $message,
//             'data' => $data
//         ], $code);
//     }

//     public static function error(int $code,string $status,?string $message = "something went wrong"){
//         return response()->json([
//             'error' => true,
//             'status' => $status,
//             'message' => $message,
//             'data' => null
//         ], $code);
//     }

//     public static function flex($object = null, $message = null, $statusCode = null)
//     {
//         // Determine status code
//         $statusCode = $statusCode
//             ?? $object->statusCode
//             ?? $object?->data?->statusCode
//             ?? 200;

//         // Extract data
//         $data = $object->data ?? $object;

//         // Remove statusCode from data
//         if (is_object($data) && isset($data->statusCode)) unset($data->statusCode);
//         if (is_array($data) && isset($data['statusCode'])) unset($data['statusCode']);

//         // Map HTTP status to status text
//         $statusText = match(true) {
//             $statusCode >= 200 && $statusCode < 300 => 'OK',
//             $statusCode >= 400 && $statusCode < 500 => 'Client Error',
//             $statusCode >= 500 => 'Server Error',
//             default => 'Unknown'
//         };

//         // Build response
//         $response = [
//             'error' => $object->error ?? false,
//             'status' => $statusText,
//             'message' => $message ?? ($object->message ?? null),
//             'data' => $data
//         ];

//         return response()->json($response, $statusCode);
//     }

//     public static function paginate(AbstractPaginationDTO $pagination, $message = null, $statusCode = 200)
//     {
//         $statusText = match(true) {
//             $statusCode >= 200 && $statusCode < 300 => 'OK',
//             $statusCode >= 400 && $statusCode < 500 => 'Client Error',
//             $statusCode >= 500 => 'Server Error',
//             default => 'Unknown'
//         };

//         $response = [
//             'error' => false,
//             'status' => $statusText,
//             'message' => $message,
//             'pagination' => [
//                 'perPage' => $pagination->perPage,
//                 'total' => $pagination->total,
//                 'totalPage' => $pagination->totalPage,
//                 'pageNo' => $pagination->pageNo,
//             ],
//             'data' => $pagination->data,
//         ];

//         return response()->json($response, $statusCode);
//     }



//     static function Duplicated($message=null,$errors=[]){
//         return response()->json([
//             'error' => true,
//             'status' => 'Conflict',
//             'errors' => $errors,
//             'message' => $message,
//         ],409);
//     }

//     static function Unauthorized($err_msg='Unauthorized',$errors=[]){
//         return response()->json([
//             'error' => true,
//             'status' => 'Unauthorized',
//             'errors' => $errors,
//             'message' => $err_msg
//         ],401);
//     }

//     static function ManyRequest($err_msg = 'Too many requests', $errors = [])
//     {
//         return response()->json([
//             'error'   => true,
//             'status'  => 'Too Many Requests', // <-- correct label
//             'errors'  => $errors,
//             'message' => $err_msg,
//         ], 429);
//     }
// }



// class PaginationHelper
// {
//     /**
//      * Paginate a query with optional transformation.
//      * Handles large datasets efficiently.
//      *
//      * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
//      * @param array $filter ['page' => int, 'perPage' => int]
//      * @param string $sort
//      * @param array $additionalFields
//      * @param int $limit
//      * @param callable|null $transformCallback
//      * @param array $select
//      * @param bool $withTotal
//      * @param int|null $cacheMin
//      * @param string $pageKey
//      * @param array $cacheTags
//      * @return PaginationDTO
//      */
//     public static function paginate(
//         $query,
//         array $filter = [],
//         string $sort = '',
//         array $additionalFields = [],
//         int $limit = 100,
//         ?callable $transformCallback = null,
//         array $select = ['*'],
//         bool $withTotal = false,
//         ?int $cacheMin = null,
//         string $pageKey = 'page',
//         array $cacheTags = []
//     ): PaginationDTO {
//         $page = max(1, (int)($filter[$pageKey] ?? 1));
//         $perPage = min($limit, (int)($filter['perPage'] ?? $limit));

//         // Apply sorting if provided
//         if ($sort) {
//             $query->orderByRaw($sort);
//         }

//         // Optionally calculate total efficiently
//         $total = null;
//         if ($withTotal) {
//             $total = $query->count();
//             if ($limit && $total > $limit) {
//                 $total = $limit; // cap total to limit
//             }
//         }
//         $totalPage = $withTotal && $total !== null ? (int) ceil($total / $perPage) : null;

//         // Calculate offset
//         $offset = ($page - 1) * $perPage;

//         // Chunking for memory efficiency
//         $rows = $query->offset($offset)
//                       ->limit($perPage)
//                       ->get($select)
//                       ->map(function ($row) use ($transformCallback) {
//                           return $transformCallback ? $transformCallback($row) : $row;
//                       })
//                       ->toArray();

//         return new PaginationDTO($rows, $perPage, $total, $totalPage, $page);
//     }
// }



// class DataResponse //extends Model
// {
//     // use HasFactory;
//     static function ValidateFail($message = null,$errors = [])
//     {
//         return (object)[
//             'statusCode' => 422,
//             'error' => true,
//             'status' => 'Unprocessable',
//             'errors' => $errors,
//             'message' => $message,
//         ];
//     }
//     static function BadRequest($message = "Bad Request",$errors = [])
//     {
//         return (object)[
//             'statusCode' => 400,
//             'error' => true,
//             'status' => 'Bad Request',
//             'errors' => $errors,
//             'message' => $message,
//         ];
//     }

//     static function Duplicated($message, $errors = [])
//     {
//         return (object)[
//             'statusCode' => 409,
//             'error' => true,
//             'status' => 'Conflict',
//             'errors' => $errors,
//             'message' => $message,
//         ];
//     }

//     static function Unauthorized($err_msg = 'Unauthorized')
//     {
//         return (object)[
//             'statusCode' => 401,
//             'error' => true,
//             'status' => 'Unauthorized',
//             'message' => $err_msg
//         ];
//     }

//     static function JsonResult($data, $error = false, $message = null,$errors=[],$statusCode=200,$status="OK",$additionalKeys=[])
//     {

//         $obj = (object)[
//             'error' => $error,
//             'status' => $status,
//             'message' => $message,
//             'statusCode' => $statusCode,
//             'errors' => $errors,
//             'data' => $data,
//         ];
//         if (is_array($additionalKeys) || is_object($additionalKeys)) {
//             foreach ($additionalKeys as $key => $value) {
//                 $obj->$key = $value;
//             }
//         }
//         return $obj;
//     }

//     static function JsonRaw($json, $status = null)
//     {
//         $jsonRes = (object)[];
//         foreach($json as $key=>$j){
//             $jsonRes->{$key} = $j;
//         }
//         return $jsonRes;
//     }

//     static function NotFound($message)
//     {
//         return (object)[
//             'statusCode' => 404,
//             'error' => true,
//             'status' => 'Not Found',
//             'message' => $message,
//             'errors' => []
//         ];
//     }

//     static function Error($message,$errors=[])
//     {
//         return (object)[
//             'statusCode' => 500,
//             'error' => true,
//             'status' => 'Error',
//             'message' => $message,
//             'data' => null,
//             'errors' => $errors
//         ];
//     }

//     static function Pagination($data, $filter = null, $message = "get list",$additionalKey=[],$limit=1000)
//     {
//         $filter = (object)$filter;
//         $perPage = isset($filter->per_page) ? ($filter->per_page == 0 ? 1:$filter->per_page) : 10;
//         $currentPage = isset($filter->page_no) ? $filter->page_no : 1;
//         $skip_row = $perPage * ($currentPage - 1);
//         $totalCount = $data->count();
//         $count = $totalCount > $limit ? $limit : $totalCount;
//         if(isset($filter->search_value) || isset($filter->search)){
//             $skip_row = 0;
//             $perPage = $count > 0 ? $count : 1;
//         }
//         $limitation = $data->slice($skip_row,$perPage);
//         $total_page = ceil($count/$perPage);
//         $obj = (object)[
//             'status' => "OK",
//             'statusCode' => 200,
//             'error' => false,
//             'message'=> $message,
//             'data' => $limitation->values(),
//             'per_page' => (int)$perPage,
//             'total' => (int)$count,
//             'total_page' => (int) $total_page,
//             'page_no' => (int) $currentPage,
//             'errors'=>[],
//         ];
//         foreach ((object)$additionalKey as $key => $value) {
//             $obj->$key = $value;
//         }
//         return $obj;
//     }

//     static function Forbidden($message='You has no permmision to access or do the action')
//     {
//         return (object)[
//             'statusCode' => 403,
//             'error' => true,
//             'status' => 'Forbidden',
//             'message' => $message,
//             'errors' => []
//         ];
//     }

//     static function PaginationV1($query, $filter = null, $message = null, $additionalKey = [], $limit = 1000, ? callable $transformCallback = null,$select=['*'],bool $reverse = false, $cache = null)
//     {
//         $filter = (object)$filter;
//         $perPage = isset($filter->per_page) ? ($filter->per_page == 0 ? 1 : min($filter->per_page, $limit)) : min(10, $limit);
//         $currentPage = isset($filter->page_no) ? $filter->page_no : 1;

//         // Build cache key based on filter parameters
//         $cacheKey = 'pagination_' . md5(json_encode($filter));

//         // Cache logic: check if data is already cached
//         if ($cache && $cache > 0) {
//             $cachedData = Cache::get($cacheKey);
//             if ($cachedData) {
//                 return $cachedData; // Return cached data if available
//             }
//         }

//         // Execute pagination on the query
//         $data = $query->paginate($perPage, $select, 'page', $currentPage);

//         // Apply transformation if provided
//         if ($transformCallback) {
//             $data->getCollection()->transform($transformCallback);
//         }

//         // Build response object
//         $obj = (object)[
//             'status' => "OK",
//             'error' => false,
//             'message' => $message,
//             'data' => $reverse ? array_reverse($data->items()) : $data->items(),
//             'per_page' => (int) $data->perPage(),
//             'total' => (int) $data->total(),
//             'total_page' => (int) $data->lastPage(),
//             'page_no' => (int) $data->currentPage(),
//             'errors' => [],
//         ];

//         // Add additional custom data if provided
//         foreach ((object)$additionalKey as $key => $value) {
//             $obj->{$key} = $value;
//         }

//         // Cache the result if caching is enabled
//         if ($cache !== null) {
//             $cacheTime = is_numeric($cache) ? $cache : 300; // Default cache time of 300 seconds (5 minutes)
//             Cache::put($cacheKey, $obj, $cacheTime); // Store the response in the cache
//         }

//         return $obj;
//     }
// }

// class MediaHelper{

//     static function getImageUrl($fileName, $bucket, $dirName, $subDir = null)
//     {
//         // Primary file path
//         $relativeFilePath = 'uploads/images/' . $bucket . '/' . $dirName . '/' . $fileName;
//         $filePath = public_path($relativeFilePath);

//         // Check if the file exists in the main directory
//         if (file_exists($filePath) && $fileName) {
//             return asset($relativeFilePath);
//         }

//         // If not found and a subdirectory is provided, check there
//         if ($subDir) {
//             $relativeFilePath = 'uploads/images/' . $bucket . '/' .$dirName.'/'. $subDir . '/' . $fileName;
//             $filePath = public_path($relativeFilePath);
//             if (file_exists($filePath)) {
//                 return asset($relativeFilePath);
//             }
//         }

//         return null; // Adjust with a default placeholder if needed
//     }

//     static function ensureBase64Prefix($base64String, $imageType = 'png')
//     {
//         // Define a pattern to match the existing base64 prefix
//         $prefixPattern = '/^data:image\/(\w+);base64,/';

//         // Check if the base64 string has a prefix
//         if (preg_match($prefixPattern, $base64String, $matches)) {
//             // If it has a prefix, but the image type is different, replace it with the correct one
//             $existingType = $matches[1];
//             if ($existingType !== $imageType) {
//                 $base64String = preg_replace($prefixPattern, "data:image/{$imageType};base64,", $base64String);
//             }
//         } else {
//             // If no prefix is found, add the correct one
//             $base64String = "data:image/{$imageType};base64," . $base64String;
//         }

//         return $base64String;
//     }

//     /**
//      * Summary of base64ToImageFile
//      * @param mixed $base64String
//      * @param mixed $bucket
//      * @param mixed $dirName
//      * @return object
//      * Note* folder structure => public/uploads/images/bucket/dirname
//      */
//     static function base64ToImageFile($base64String, $bucket, $dirName,$subDir=null,$ext=null): object
//     {
//         $base64String = self::ensureBase64Prefix($base64String);

//         if(!self::isValidBase64Image($base64String)) return (object)[
//             'filename' => null
//         ];
//         // Construct the base directory path
//         $baseFolder = public_path('uploads/images/' . $bucket . '/' . $dirName);
//         if ($subDir) {
//             $baseFolder .= '/' . $subDir;
//         }

//         // Check if the directory exists, if not, create it
//         if (!file_exists($baseFolder)) {
//             if (!mkdir($baseFolder, 0755, true)) {
//                 // throw new Exception('Failed to create directory: ' . $baseFolder);
//                 return (object)[
//                     'filename' => null
//                 ];
//             }
//         }

//         // Split the base64 string to get the format and the data
//         if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
//             $fileExtension = $matches[1]; // e.g., png, jpg, jpeg
//             list(, $imageData) = explode(';base64,', $base64String);
//             $imageData = base64_decode($imageData);

//             if ($imageData === false) {
//                 // throw new Exception('Base64 decode failed.');
//                 return (object)[
//                     'filename' => null
//                 ];
//             }
//             $fileExtension = $ext ? $ext : $fileExtension;
//             // Generate a unique file name
//             // => company_id+YMdHis+uniqid+extension
//             $fileName = $bucket.date('YmdHis').uniqid() . '.' . $fileExtension;

//             // Save the image file
//             $filePath = $baseFolder . '/' . $fileName;
//             if (file_put_contents($filePath, $imageData) === false) {
//                 // throw new Exception('Failed to save file to path: ' . $filePath);
//                 return (object)[
//                     'filename' => null
//                 ];
//             }
//             // Return the file name
//             return (object)[
//                 'filename' => $fileName,
//             ];
//         } else {
//             // throw new Exception('Invalid base64 string.');
//             return (object)[
//                     'filename' => null
//                 ];

//         }
//     }

//     public static function saveImageFileOrBase64($imageOrBase64, $bucket, $dirName = 'images',$subDir=null){
//         if (self::isValidBase64Image($imageOrBase64)) {
//             return self::base64ToImageFile($imageOrBase64, $bucket, $dirName,$subDir);
//         } elseif ($imageOrBase64 instanceof UploadedFile) {
//             return self::saveImageFile($imageOrBase64, $bucket, $dirName,$subDir);
//         } else {
//             throw new \Exception("Invalid image format.");
//         }
//     }

//     public static function saveImageFile(UploadedFile $image, $bucket, $dirName = 'images',$subDir=null)
//     {
//         // Validate the image type
//         $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/heic', 'image/heif', 'image/webp','application/octet-stream'];
//         if (!in_array($image->getClientMimeType(), $validMimeTypes)) {
//             return (object)[
//                 'filename' => null,
//                 'ext' => null
//             ];
//         }

//         $originalFilename = $image->getClientOriginalName();
//         $extension = $image->getClientOriginalExtension();
//         // Generate a unique filename based on the current timestamp
//         $filename = $bucket.date('YmdHis').uniqid() . $originalFilename;

//         // Define the base folder path
//         $baseFolder = public_path('uploads/images/' . $bucket . '/' . $dirName);
//         if($subDir) $baseFolder .= '/'.$subDir;
//         // Create the directory if it does not exist
//         if (!is_dir($baseFolder)) {
//             mkdir($baseFolder, 0755, true); // Create the directory with the appropriate permissions
//         }

//         // Move the uploaded file to the specified directory
//         $image->move($baseFolder, $filename);

//         // Return the public URL of the stored image
//         return (object)[
//             'filename' => $filename,
//             'ext' => $extension
//         ];
//     }

//     // check full path base 64
//     static function isValidBase64Image($base64String)
//     {
//         // Check if the string has the correct base64 format for an image
//         if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
//             // Extract the base64 data if the prefix is present
//             $imageData = substr($base64String, strpos($base64String, ',') + 1);
//         } else {
//             // If no prefix, assume the entire string is base64 encoded image data
//             $imageData = $base64String;
//         }
//         // Decode the base64 data
//         $imageData = base64_decode($imageData, true);
//         // Ensure that base64_decode did not return false (indicating a decoding failure)
//         if ($imageData === false) {
//             return false;
//         }
//         // Check if the image data is a valid image using GD or Imagick
//         $img = @imagecreatefromstring($imageData);
//         if ($img !== false) {
//             // The image is valid
//             imagedestroy($img);
//             return true;
//         }
//         // If the string does not match the pattern or the image data is invalid, return false
//         return false;
//     }
// }


// if (!class_exists('CustomValidator')) {
//     class CustomValidator
//     {
//         /**
//          * Validate and sanitize data.
//          *
//          * @param array $data Input data
//          * @param array $rules Laravel validation rules
//          * @param array $allowedTagsPerField Allowed HTML tags per field, e.g., ['description' => ['b', 'i']]
//          * @param bool $throwOnDisallowed Throw exception if disallowed HTML exists
//          * @return array Validated and sanitized data
//          * @throws BadRequestExcept
//          */
//         public static function validate(array $data, array $rules, array $allowedTagsPerField = [], bool $throwOnDisallowed = false): array
//         {
//             // 1. Sanitize inputs first
//             $sanitized = self::sanitize($data, $allowedTagsPerField, $throwOnDisallowed);

//             // Log::info($sanitized);
//             // 2. Run Laravel validation on sanitized data
//             $validator = Validator::make($sanitized, $rules);

//             if ($validator->fails()) {
//                 throw new ValidationExcept($validator->errors()->first());
//             }

//             return $validator->validated();
//         }

//         /**
//          * Recursively sanitize input data.
//          *
//          * @param array $data
//          * @param array $allowedTagsPerField
//          * @param bool $throwOnDisallowed
//          * @return array
//          * @throws BadRequestExcept
//          */
//         public static function sanitize(array $data, array $allowedTagsPerField = [], bool $throwOnDisallowed = false): array
//         {
//             $sanitized = [];

//             foreach ($data as $key => $value) {

//                 // Recurse for arrays
//                 if (is_array($value)) {
//                     $sanitized[$key] = self::sanitize($value, $allowedTagsPerField, $throwOnDisallowed);
//                     continue;
//                 }

//                 // Skip non-string values (numbers, dates)
//                 if (!is_string($value)) {
//                     $sanitized[$key] = $value;
//                     continue;
//                 }

//                 $allowedTags = $allowedTagsPerField[$key] ?? [];

//                 // If no tags allowed, just return the value
//                 if (empty($allowedTags)) {
//                     $sanitized[$key] = $value;
//                     continue;
//                 }

//                 $allowedTagsString = '<' . implode('><', $allowedTags) . '>';
//                 $clean = strip_tags($value, $allowedTagsString);
//                 $clean = preg_replace('/(<[^>]+)\s*on[a-z]+\s*=\s*["\'][^"\']*["\']/', '$1', $clean);
//                 $clean = preg_replace('/javascript:/i', '', $clean);

//                 if ($throwOnDisallowed && $clean !== $value) {
//                     throw new BadRequestExcept("Field '{$key}' contains disallowed HTML or scripts.");
//                 }

//                 $sanitized[$key] = $clean;
//             }

//             return $sanitized;
//         }


//     }
// }
