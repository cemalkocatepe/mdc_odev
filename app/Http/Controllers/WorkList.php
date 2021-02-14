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
            $todo_list_data = array(
                "1" => array(),
                "2" => array(),
                "3" => array(),
                "4" => array(),
                "5" => array(),
            );

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

                            $data_count = DB::table('work_list')->where("level" , 1)->count();
                            $limit = round($data_count / $pp);
                            $kk_1 = 0;
                            for ($i=1; $i < $pp; $i++) {                            
                                $data_level = DB::table('work_list')->where("level" , 1)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free[$kk_1] == 2 || $person_free[$kk_1] == 3 || $person_free[$kk_1] == 4 || $person_free[$kk_1] == 5){
                                    $todo_list_data[$person_free[$kk_1]] = array_merge($todo_list_data[$person_free[$kk_1]], $data_level);
                                }
                                $kk_1++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 1)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[1] = $data_level;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;

                            $data_level = DB::table('work_list')->where("level" , 1)->get()->toarray();
                            $todo_list_data[1] = $data_level;
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

                            $data_count = DB::table('work_list')->where("level" , 2)->count();                        
                            $limit = round($data_count / $pp);
                            $kk_2 = 0;
                            for ($i=1; $i < $pp; $i++) {                            
                                $data_level = DB::table('work_list')->where("level" , 2)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free[$kk_2] == 3 || $person_free[$kk_2] == 4 || $person_free[$kk_2] == 5){
                                    $todo_list_data[$person_free[$kk_2]] = array_merge($todo_list_data[$person_free[$kk_2]], $data_level);
                                }
                                $kk_2++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 2)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[2] = $data_level;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;

                            $data_level = DB::table('work_list')->where("level" , 2)->get()->toarray();
                            $todo_list_data[2] = $data_level;
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

                            $data_count = DB::table('work_list')->where("level" , 3)->count();
                            $limit = round($data_count / $pp);
                            $kk_3 = 0;
                            for ($i=1; $i < $pp; $i++) {
                                $data_level = DB::table('work_list')->where("level" , 3)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free[$kk_3] == 4 || $person_free[$kk_3] == 5){
                                    $todo_list_data[$person_free[$kk_3]] = array_merge($todo_list_data[$person_free[$kk_3]], $data_level);
                                }
                                $kk_3++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 3)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[3] = $data_level;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;

                            $data_level = DB::table('work_list')->where("level" , 3)->get()->toarray();
                            $todo_list_data[3] = $data_level;
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

                            $data_count = DB::table('work_list')->where("level" , 4)->count();
                            $limit = round($data_count / $pp);
                            $kk_4 = 0;
                            for ($i=1; $i < $pp; $i++) {
                                $data_level = DB::table('work_list')->where("level" , 4)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free[$kk_4] == 5){
                                    $todo_list_data[$person_free[$kk_4]] = array_merge($todo_list_data[$person_free[$kk_4]], $data_level);
                                }
                                $kk_4++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 4)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[4] = $data_level;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;

                            $data_level = DB::table('work_list')->where("level" , 4)->get()->toarray();
                            $todo_list_data[4] = $data_level;
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

                            $data_level = DB::table('work_list')->where("level" , 5)->get()->toarray();
                            $todo_list_data[5] = $data_level;
                        }else{
                            $total_work_time += $value->total_time;

                            $data_total_arr[1]->total_time -= $value->total_time;
                            $data_total_arr[2]->total_time -= $value->total_time;
                            $data_total_arr[3]->total_time -= $value->total_time;
                            $data_total_arr[4]->total_time -= $value->total_time;
                            $data_total_arr[5]->total_time -= $value->total_time;

                            $data_level = DB::table('work_list')->where("level" , 5)->get()->toarray();
                            $todo_list_data[5] = $data_level;
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

        /* Burada ZAMANA göre liste oluşturmam gerekiyordu sanırım ancak çözemedim Görkem Hocam Aşağıdaki yapı yanlış olabilir.*/
        /*if(!empty($person_free)){
            $person_free_2 = array();
            $todo_list_data = array(
                "1" => array(),
                "2" => array(),
                "3" => array(),
                "4" => array(),
                "5" => array(),
            );
            foreach ($person_free as $key => $value) {
                switch ($value) {
                    case '1':
                        if(!empty($person_free_2)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free_2 as $item) {
                                if($item == 2 || $item == 3 || $item == 4 || $item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }                        
                            $data_count = DB::table('work_list')->where("level" , 1)->count();
                            $limit = round($data_count / $pp);
                            $kk_1 = 0;
                            for ($i=1; $i < $pp; $i++) {                            
                                $data_level = DB::table('work_list')->where("level" , 1)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free_2[$kk_1] == 2 || $person_free_2[$kk_1] == 3 || $person_free_2[$kk_1] == 4 || $person_free_2[$kk_1] == 5){
                                    $todo_list_data[$person_free_2[$kk_1]] = array_merge($todo_list_data[$person_free_2[$kk_1]], $data_level);
                                }
                                $kk_1++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 1)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[1] = $data_level;
                        }else{
                            $data_level = DB::table('work_list')->where("level" , 1)->get()->toarray();
                            $todo_list_data[1] = $data_level;
                        }
                        break;
                    case '2':
                        if(!empty($person_free_2)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free_2 as $item) {
                                if($item == 3 || $item == 4 || $item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }                        
                            $data_count = DB::table('work_list')->where("level" , 2)->count();                        
                            $limit = round($data_count / $pp);
                            $kk_2 = 0;
                            for ($i=1; $i < $pp; $i++) {                            
                                $data_level = DB::table('work_list')->where("level" , 2)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free_2[$kk_2] == 3 || $person_free_2[$kk_2] == 4 || $person_free_2[$kk_2] == 5){
                                    $todo_list_data[$person_free_2[$kk_2]] = array_merge($todo_list_data[$person_free_2[$kk_2]], $data_level);
                                }
                                $kk_2++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 2)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[2] = $data_level;
                        }else{
                            $data_level = DB::table('work_list')->where("level" , 2)->get()->toarray();
                            $todo_list_data[2] = $data_level;
                        }
                        break;
                    case '3':
                        if(!empty($person_free_2)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free_2 as $item) {
                                if($item == 4 || $item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }                        
                            $data_count = DB::table('work_list')->where("level" , 3)->count();
                            $limit = round($data_count / $pp);
                            $kk_3 = 0;
                            for ($i=1; $i < $pp; $i++) {
                                $data_level = DB::table('work_list')->where("level" , 3)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free_2[$kk_3] == 4 || $person_free_2[$kk_3] == 5){
                                    $todo_list_data[$person_free_2[$kk_3]] = array_merge($todo_list_data[$person_free_2[$kk_3]], $data_level);
                                }
                                $kk_3++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 3)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[3] = $data_level;
                        }else{
                            $data_level = DB::table('work_list')->where("level" , 3)->get()->toarray();
                            $todo_list_data[3] = $data_level;
                        }
                        break;
                    case '4':
                        if(!empty($person_free_2)){
                            $pp = 1; // Burada seçili seviyedeki kişinin kendisini tanımlıyoruz.
                            foreach ($person_free_2 as $item) {
                                if($item == 5){
                                    $pp += 1; // Burada boşta olan kişinin seviyeye göre çalışma izni var ise ekliyoruz.
                                }
                            }                        
                            $data_count = DB::table('work_list')->where("level" , 4)->count();
                            $limit = round($data_count / $pp);
                            $kk_4 = 0;
                            for ($i=1; $i < $pp; $i++) {
                                $data_level = DB::table('work_list')->where("level" , 4)->offset($limit * $i)->limit($limit)->get()->toarray();
                                if($person_free_2[$kk_4] == 5){
                                    $todo_list_data[$person_free_2[$kk_4]] = array_merge($todo_list_data[$person_free_2[$kk_4]], $data_level);
                                }
                                $kk_4++;
                            }
                            $data_level = DB::table('work_list')->where("level" , 4)->offset($limit * 0)->limit($limit)->get()->toarray();
                            $todo_list_data[4] = $data_level;
                        }else{
                            $data_level = DB::table('work_list')->where("level" , 4)->get()->toarray();
                            $todo_list_data[4] = $data_level;
                        }
                        break;
                    case '5':
                        $data_level = DB::table('work_list')->where("level" , 5)->get()->toarray();
                        $todo_list_data[5] = $data_level;
                        break;                    
                    default:
                        # code...
                        break;
                }
                $person_free_2[] = $value;
            }
        }*/
        
        
        return view('index', compact('data','group_total_time','total_work_time','todo_list_data'));
    }
}