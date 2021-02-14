<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WorkListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mdc_api:worklist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WorkList Api get services';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Provider listesinin alındığı yer        
        $provider = array(
            'provider_1' => 'https://www.mediaclick.com.tr/api/5d47f24c330000623fa3ebfa.json',
            'provider_2' => 'https://www.mediaclick.com.tr/api/5d47f235330000623fa3ebf7.json'
        );

        // ==== Aşağıdaki Yapıda 2 farklı json modeline göre otomatik olarak veriler db'ye ekleme yapılıyor eğer istenir ise aynı yapıda yukarıya array içine json eklenip servis çalıştırılabilir. ====
        foreach ($provider as $key => $value) {
            $response = json_decode(file_get_contents($value),true);
            foreach ($response as $key => $value) {
                if(count($value) == 3){
                    $data = array(
                        "title"         => $value["id"],
                        "level"         => $value["zorluk"],
                        "duration"      => $value["sure"],
                        "created_at"    => date("Y-m-d H:i:s"),
                        "updated_at"    => date("Y-m-d H:i:s"),
                    );
                    if(DB::table('work_list')->insert($data)){
                        echo "Success : Kayıt başarılı. => Title : " . $data['title'] . "| Level : " . $data['level'] . "| duration : ".$data['duration']."\n\n";
                    }else{
                        echo "Error : Kayıt yapılamadı. => Title : $value->id\n\n";
                    }
                }else{
                    $title = array_keys($value)[0];
                    $data = array(
                        "title" => $title,
                        "level" => $value[$title]["level"],
                        "duration" => $value[$title]["estimated_duration"],
                        "created_at"    => date("Y-m-d H:i:s"),
                        "updated_at"    => date("Y-m-d H:i:s"),
                    );
                    if(DB::table('work_list')->insert($data)){
                        echo "Success : Kayıt başarılı. => Title : " . $data['title'] . "| Level : " . $data['level'] . "| duration : ".$data['duration']."\n\n";
                    }else{
                        echo "Error : Kayıt yapılamadı. => Title : $value->id\n\n";
                    }
                }
            }
        }

        // ==== Aşağıdaki Yapıda developer yeni bir yapı ekleyerek veri çekip db ekleyebilir. ====

        /*$response = json_decode(file_get_contents($provider["provider_1"]),true);

        foreach ($response as $key => $value) {
            if(count($value) == 3){
                $data = array(
                    "title"         => $value["id"],
                    "level"         => $value["zorluk"],
                    "duration"      => $value["sure"],
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s"),
                );
                if(DB::table('work_list')->insert($data)){
                    echo "Success : Kayıt başarılı. => Title : " . $data['title'] . "| Level : " . $data['level'] . "| duration : ".$data['duration']."\n\n";
                }else{
                    echo "Error : Kayıt yapılamadı. => Title : $value->id\n\n";
                }
            }
        }
            
        $response = json_decode(file_get_contents($provider["provider_2"]),true);

        foreach ($response as $key => $value) {
            $title = array_keys($value)[0];
            $data = array(
                "title" => $title,
                "level" => $value[$title]["level"],
                "duration" => $value[$title]["estimated_duration"],
                "created_at"    => date("Y-m-d H:i:s"),
                "updated_at"    => date("Y-m-d H:i:s"),
            );
            if(DB::table('work_list')->insert($data)){
                echo "Success : Kayıt başarılı. => Title : " . $data['title'] . "| Level : " . $data['level'] . "| duration : ".$data['duration']."\n\n";
            }else{
                echo "Error : Kayıt yapılamadı. => Title : $value->id\n\n";
            }
        }*/
    }
}
