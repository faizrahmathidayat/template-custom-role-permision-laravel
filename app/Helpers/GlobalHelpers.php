<?php

namespace App\Helpers;

use App\Models\CounterCode;
use App\Models\Master\ProductBrand;
use App\Models\Master\ProductCategory;
use App\Models\Master\ProductDivisi;
use App\Models\Master\ProductSubCategory;
use App\Models\Master\ProductType;
use App\Models\Master\Unit;
use App\Traits\Permissions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class GlobalHelpers
{    
    use Permissions;

    public static function generateCode($column,$year,$month)
    {
        $code = CounterCode::where(['year' => $year, 'month' => $month])->first();
        if($code) {
            CounterCode::where(['year' => $year, 'month' => $month])
            ->update([
                $column => DB::raw($column.'+1'),
            ]);
            
            return $code->refresh()->$column;
        } else {
            return null;
        }
    }
    
    public static function getProductType($id = null)
    {
        if(!is_null($id)) {
            $data = ProductType::where(['is_active' => true, 'id' => $id])->first();
        } else {
            $data = ProductType::where(['is_active' => true])->get();
        }
        return $data;
    }

    public static function getProductBrand($id = null)
    {
        if(!is_null($id)) {
            $data = ProductBrand::where(['is_active' => true, 'id' => $id])->first();
        } else {
            $data = ProductBrand::where(['is_active' => true])->get();
        }
        return $data;
    }

    public static function getProductDivisi($id = null)
    {
        if(!is_null($id)) {
            $data = ProductDivisi::where(['is_active' => true, 'id' => $id])->first();
        } else {
            $data = ProductDivisi::where(['is_active' => true])->get();
        }
        return $data;
    }

    public static function getProductCategory($id = null)
    {
        if(!is_null($id)) {
            $data = ProductCategory::where(['is_active' => true, 'id' => $id])->first();
        } else {
            $data = ProductCategory::where(['is_active' => true])->get();
        }
        return $data;
    }

    public static function getProductSubCategory($id = null, $category_id = null)
    {
        if(!is_null($id)) {
            $data = ProductSubCategory::where(['is_active' => true, 'id' => $id])->first();
        } else {
            if(!is_null($category_id)) {
                $data = ProductSubCategory::where(['is_active' => true,'category_id' => $category_id])->get();
            } else {
                $data = ProductSubCategory::where(['is_active' => true])->get();
            }
            
        }
        return $data;
    }

    public static function getStockUnit($id = null)
    {
        if(!is_null($id)) {
            $data = Unit::where(['is_active' => true, 'id' => $id])->first();
        } else {
            $data = Unit::where(['is_active' => true])->get();
        }
        return $data;
    }

    public static function UploadFile($file, $folder_name, $filename = null)
    {
        $path = uploadPath($folder_name);
        // Check Directory
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
            chmod($path, 0777);
        }

        if (!is_null($filename)) {
            $filename = $filename . '.' . $file->getClientOriginalExtension();
        } else {
            $filename = $file->getClientOriginalName();
        }

        $fullPath = $path . '/' . $filename;
        $PathFile = $folder_name . '/' . $filename;

        if (!in_array($file->getClientMimeType(), array("image/png", "image/jpeg", "image/jpg"))) {
            $file->move($path, $filename);
        } else {
            Image::make(realpath($file))->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
            })->save($fullPath);
            chmod($fullPath, 0777);
        }

        return $PathFile;
    }

    public static function deleteFile($file_path)
    {
        if (file_exists(uploadPath($file_path))) {
            unlink(uploadPath($file_path));
        }
    }
}
