<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportExcelController extends Controller{

    public function index(){
        $data = DB::table('assets')->orderBy('asset_number', 'DESC')
            ->get();
        
        return view('assets.index', compact('data'));
    }

    public function import(Request $request){
        $this ->validate($request, [
            'select_file' => 'required|mimes:xls, xlsx, csv|max:2048'

        ]);

        $path = $request->file('select_file')->getRealPath();

        $data = Excel::load($path)->get();

        if($data->count() > 0){

            foreach($data->toArray() as $key => $value){

                foreach($value as $row){

                    $insert_data[] = array(
                        'Make' => $row['make'],
                        'Model' => $row['model'],
                        'Serial Number' => $row['serial_number'],
                        'Asset Number' => $row['asset_number'],
                        'Category' => $row['category_name'],
                        'Current User' => $row['name'],
                        'Date' => $row['date'],
                        'Previous User' => $row['name'],
                        'Vendor' => $row['vendor_name'],
                        'Status' => $row['status'],
                    );
                }
            }
            if(!empty($insert_data)){
                DB::table('tbl_assets')->insert($insert_data);
            }
        }
        return back()->with('success', 'Excel data imported successfully');
    }
    
}