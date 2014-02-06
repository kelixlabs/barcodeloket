<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getIndex()
	{
		//return public_path("assets/barcode");
		return View::make('masterLayout');
	}

	public function postIndex(){
		$masuk = Masuk::where('FormNo','=',Input::get('noform'))->first();
		$bod = substr($masuk->BirthDate, 6,2).'-'.substr($masuk->BirthDate, 4,2).'-'.substr($masuk->BirthDate, 0,4);
		switch (intval($masuk->FormTypeXID)){
			case 11:
				$formtype="Baru - Paspor Biasa";
				break;
			case 12:
				$formtype="Baru - Paspor TKI";
				break;
			case 21:
				$formtype="Penggantian - Habis Berlaku";
				break;
			case 22:
				$formtype="Penggantian - Halaman Penuh";
				break;
			case 23:
				$formtype="Penggantian - Hilang";
				break;
			case 24:
				$formtype="Penggantian - Rusak Masih Berlaku krn Kelalaian";
				break;
			case 26:
				$formtype="Penggantian - Hilang Bencana Alam";
				break;
			case 27:
				$formtype="Penggantian - Hilang Kapal Tenggelam";
				break;
			case 28:
				$formtype="Penggantian 24H/48H eks Pemegang 24H/48H/SPLP";
				break;
			case 29:
				$formtype="Penggantian - Rusak Tenggelam/Bencana Alam";
				break;
			case 31:
				$formtype="Perubahan - Nama";
				break;
			case 32:
				$formtype="Perubahan - Alamat Tempat Tinggal";
				break;
			case 33:
				$formtype="Perubahan - Lain-lain";
				break;
			case 201:
				$formtype="Penggantian Hilang Habis Berlaku";
				break;
			case 202:
				$formtype="Penggantian Rusak Habis Berlaku";
				break;
		}
		if (Input::get('layanan') == 'Pemohon'){
			if(Input::get('reprint', 0) == 0){
				$entry = new LoketEntry;
				$entry->formno = Input::get('noform','');
				$entry->antrian = strtoupper(Input::get('antrian', 'a000'));
				$entry->save();
			}
			//return json_encode(array(array('pesan'=>'sukses','cetak'=>'ybs')));
			$data = array(
				'formno'=>Input::get('noform'),
				'nama'=>$masuk->Name,
				'dob'=>$bod,
				'formtype'=>$formtype,
				'kode'=>strtoupper(Input::get('antrian', 'a000')),
			);
				return View::make('printout.printLayout', $data);
		}
		else{
			if(Input::get('reprint', 0) == 0){
				$bu = new BuQueue;
				$bu->formno = Input::get('noform','');
				$bu->kodebu = strtoupper(Input::get('kodebu', 'xxx'));
				$bu->save();
			}
			$data = array(
				'formno'=>Input::get('noform'),
				'nama'=>$masuk->Name,
				'dob'=>$bod,
				'formtype'=>$formtype,
				'kode'=>strtoupper(Input::get('kodebu', 'xxx')),
			);
			return View::make('printout.printLayout', $data);
		}
	}

	public function postPemohon(){
		$masuk = Masuk::where('FormNo','=',Input::get('noform',''));
		$loket = LoketEntry::where('formno','=',Input::get('noform',''));
		$bu = BuQueue::where('formno','=',Input::get('noform',''));
		if ($masuk->count() > 0) {
			if ($loket->count() > 0) {
				return json_encode(array(array('pesan'=>'sukses','loket'=>$loket->get()->toArray(),'data'=>$masuk->get()->toArray())));
			} elseif ($bu->count() > 0) {
				return json_encode(array(array('pesan'=>'sukses','bu'=>$bu->get()->toArray(),'data'=>$masuk->get()->toArray())));
			}

			return json_encode(array(array('pesan'=>'sukses','data'=>$masuk->get()->toArray())));

		} else {
			return json_encode(array(array('pesan'=>'error','data'=>'Data tidak ditemukan di Server SPRI')));
		}
	}

	public function getCetak($formno){
		return View::make('printout.printLayout');
	}

}