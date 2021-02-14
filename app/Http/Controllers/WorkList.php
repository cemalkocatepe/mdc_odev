<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkList extends Controller
{
    public function index()
    {        
        // Tüm işlerin listesi
        $data = DB::table('work_list')->get()->toarray();
        // Seviye'ye göre Toplam iş süreleri
        $group_total_time = DB::table('work_list')->select("level",DB::raw('SUM(duration) as total_time'))->orderBy('total_time', 'asc')->groupBy('level')->get()->toarray();
        $data_total_time = DB::table('work_list')->select("level",DB::raw('SUM(duration)*60 as total_time'))->orderBy('total_time', 'asc')->groupBy('level')->get()->toarray();

        $total_work_time = null;
        if(count($data_total_time) > 0 && !empty($data_total_time)){
            $dd = array();
            foreach ($data_total_time as $key => $value) {
                $dd[$value->level] = $value;
                //$dd[] = array("level" => $value->level , "total_time" => $value->total_time);
            }
            $data_total_arr = $dd;

            $person_free = array();

            // print_r($data_total_arr);
            foreach ($data_total_arr as $key => $value) {
                switch ($value->level) {
                    case '1':
                        if(!empty($person_free)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free as $item) {
                                if($item == 2 || $item == 3 || $item == 4 || $item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }
                            $value->total_time = $value->total_time / $pp; // Burada mevcut saat 10 ise ve 2 kişi çalışıyorsa 5 saat olmasını buluyoruz.
                            $total_work_time += $value->total_time;

                            ($data_total_arr[2]->total_time != 0) ? $data_total_arr[2]->total_time -= $value->total_time : "";
                            ($data_total_arr[3]->total_time != 0) ? $data_total_arr[3]->total_time -= $value->total_time : "";
                            ($data_total_arr[4]->total_time != 0) ? $data_total_arr[4]->total_time -= $value->total_time : "";
                            ($data_total_arr[5]->total_time != 0) ? $data_total_arr[5]->total_time -= $value->total_time : "";
                            
                            $data_total_arr[1]->total_time = 0;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;
                        }
                        break;
                    case '2':
                        if(!empty($person_free)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free as $item) {
                                if($item == 3 || $item == 4 || $item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }                        
                            $value->total_time = $value->total_time / $pp; // Burada mevcut saat 10 ise ve 2 kişi çalışıyorsa 5 saat olmasını buluyoruz.
                            $total_work_time += $value->total_time;
                            
                            ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";                        
                            ($data_total_arr[3]->total_time != 0) ? $data_total_arr[3]->total_time -= $value->total_time : "";
                            ($data_total_arr[4]->total_time != 0) ? $data_total_arr[4]->total_time -= $value->total_time : "";
                            ($data_total_arr[5]->total_time != 0) ? $data_total_arr[5]->total_time -= $value->total_time : "";
                            
                            $data_total_arr[2]->total_time = 0;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;
                        }
                        break;
                    case '3':
                        if(!empty($person_free)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free as $item) {
                                if($item == 4 || $item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }
                            $value->total_time = $value->total_time / $pp; // Burada mevcut saat 10 ise ve 2 kişi çalışıyorsa 5 saat olmasını buluyoruz.
                            $total_work_time += $value->total_time;

                            ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";                        
                            ($data_total_arr[2]->total_time != 0) ? $data_total_arr[2]->total_time -= $value->total_time : "";
                            ($data_total_arr[4]->total_time != 0) ? $data_total_arr[4]->total_time -= $value->total_time : "";
                            ($data_total_arr[5]->total_time != 0) ? $data_total_arr[5]->total_time -= $value->total_time : "";
                            
                            if(!empty(array_search(2,$person_free))){
                                ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                            }
                            $data_total_arr[3]->total_time = 0;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;
                        }
                        break;
                    case '4':
                        if(!empty($person_free)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free as $item) {
                                if($item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }
                            $value->total_time = $value->total_time / $pp; // Burada mevcut saat 10 ise ve 2 kişi çalışıyorsa 5 saat olmasını buluyoruz.
                            $total_work_time += $value->total_time;

                            ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                            ($data_total_arr[2]->total_time != 0) ? $data_total_arr[2]->total_time -= $value->total_time : "";
                            ($data_total_arr[3]->total_time != 0) ? $data_total_arr[3]->total_time -= $value->total_time : "";
                            ($data_total_arr[5]->total_time != 0) ? $data_total_arr[5]->total_time -= $value->total_time : "";

                            if(!empty(array_search(2,$person_free))){
                                ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                            }
                            if(!empty(array_search(3,$person_free))){
                                if($data_total_arr[1]->total_time > $data_total_arr[2]->total_time){
                                    ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                                }else{
                                    ($data_total_arr[2]->total_time != 0) ? $data_total_arr[2]->total_time -= $value->total_time : "";
                                }
                            }

                            $data_total_arr[4]->total_time = 0;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;
                        }
                        break;
                    case '5':
                        if(!empty($person_free)){
                            $value->total_time = $value->total_time - $total_work_time;
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            
                            $value->total_time = $value->total_time / $pp; // Burada mevcut saat 10 ise ve 2 kişi çalışıyorsa 5 saat olmasını buluyoruz.
                            $total_work_time += $value->total_time;

                            ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                            ($data_total_arr[2]->total_time != 0) ? $data_total_arr[2]->total_time -= $value->total_time : "";
                            ($data_total_arr[3]->total_time != 0) ? $data_total_arr[3]->total_time -= $value->total_time : "";
                            ($data_total_arr[4]->total_time != 0) ? $data_total_arr[4]->total_time -= $value->total_time : "";

                            if(!empty(array_search(2,$person_free))){
                                ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                            }
                            if(!empty(array_search(3,$person_free))){
                                if($data_total_arr[1]->total_time > $data_total_arr[2]->total_time){
                                    ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                                }else{
                                    ($data_total_arr[2]->total_time != 0) ? $data_total_arr[2]->total_time -= $value->total_time : "";
                                }
                            }
                            if(!empty(array_search(4,$person_free))){
                                if($data_total_arr[1]->total_time > $data_total_arr[2]->total_time && $data_total_arr[1]->total_time > $data_total_arr[3]->total_time){
                                    ($data_total_arr[1]->total_time != 0) ? $data_total_arr[1]->total_time -= $value->total_time : "";
                                }elseif($data_total_arr[2]->total_time > $data_total_arr[1]->total_time && $data_total_arr[2]->total_time > $data_total_arr[1]->total_time){
                                    ($data_total_arr[2]->total_time != 0) ? $data_total_arr[2]->total_time -= $value->total_time : "";
                                }else{
                                    ($data_total_arr[3]->total_time != 0) ? $data_total_arr[3]->total_time -= $value->total_time : "";
                                }
                            }

                            $data_total_arr[5]->total_time = 0;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;
                        }
                        break;
                    default:
                        
                        break;
                }
                $person_free[] = $value->level; // Boşa çıkan kişiyi seçip ekliyoruz.
                // echo "::: Ben boştayım :::\n";
                // print_r($person_free);
            }
            // echo "\nTotal_Time : $total_work_time\n\n";
            // print_r($data_total_arr);
            // die;
            $total_work_time = ceil((($total_work_time / 60) / 24) / 7); // Çıkan zamanı haftaya çeviriyoruz.
        }
        
        return view('index', compact('data','group_total_time','total_work_time'));
    }
}