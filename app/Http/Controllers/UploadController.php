<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \ConvertApi\ConvertApi;
use Org_Heigl\Ghostscript\Ghostscript;

class UploadController extends Controller
{
    public function upload(){
		return view('upload');
	}

	public function proses_upload(Request $request){
		$this->validate($request, [
			'file' => 'required',
			// 'keterangan' => 'required',
		]);

		// menyimpan data file yang diupload ke variabel $file
		$file = $request->file('file');

      	        // nama file
		echo 'File Name: '.$file->getClientOriginalName();
		echo '<br>';

      	        // ekstensi file
		echo 'File Extension: '.$file->getClientOriginalExtension();
		echo '<br>';

      	        // real path
		echo 'File Real Path: '.$file->getRealPath();
		echo '<br>';

      	        // ukuran file
		echo 'File Size: '.$file->getSize();
		echo '<br>';

      	        // tipe mime
		echo 'File Mime Type: '.$file->getMimeType();

      	        // isi dengan nama folder tempat kemana file diupload
		$tujuan_upload = 'data_file';

                // upload file
		$file->move($tujuan_upload,str_replace(' ', '_', $file->getClientOriginalName()));
        $this->generatePdf($tujuan_upload,str_replace(' ', '_', $file->getClientOriginalName()));
	}

    public function generatePdf($folder, $file_name) {
        ConvertApi::setApiSecret('l1XTEFxLaY0v5CxK');
        $result = ConvertApi::convert('pdf', ['File' => public_path($folder.'/'.$file_name)]);

        # save to file
        $result->getFile()->save(public_path('pdf_file'));

        //path to ghostscript installer
        Ghostscript::setGsPath("C:\Program Files\gs\gs9.54.0\bin\gswin64c.exe");
        $pdf = new \Spatie\PdfToImage\Pdf(public_path('pdf_file/'.pathinfo($file_name, PATHINFO_FILENAME).'.pdf'));
        $pdf->saveImage(public_path('image_file/'.pathinfo($file_name, PATHINFO_FILENAME)));

        echo '<img src='.asset('image_file\\'.pathinfo($file_name, PATHINFO_FILENAME).'.jpeg').'>';
    }


}
