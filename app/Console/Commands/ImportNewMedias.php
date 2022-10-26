<?php

namespace App\Console\Commands;

use App\Category;
use App\Media;
use App\MediaSource;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;

class ImportNewMedias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:medias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import new medias';

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
     * @return mixed
     */
    public function handle()
    {
        //

        $c1 = Category::firstOrCreate(["title"=>"Videos"]);
        $c4 = Category::firstOrCreate(["title"=>"Music"]);

        $medias = Storage::disk('public')->allFiles('./uploads/medias/');
        $now = Carbon::now()->toDateString();
        foreach ($medias as $media) {
            $path_parts = pathinfo($media);
            if($path_parts['extension'] === 'mp4') {
                $sanitized = str_replace('_', ' ', $path_parts['filename']);
                $m1 = Media::firstOrCreate(['title' => $sanitized, 'user_id' => 1]);
                $m1->update(["base_type" => "video", 'poster_source' => '/img/404/poster.png', 'category_id' => $c1->id, 'description' => '', 'user_id' => 1]);
                $ms1 = MediaSource::firstOrCreate(['media_id' => $m1->id, 'title' => 'default', 'source_type' => 'default', 'duration' => '00:02:08', 'source' => $media, 'type' => 'localVideo']);
                echo "created and updated";
                $m1->tag('Video');
                $m1->tag('Import '.$now);
            }

            if($path_parts['extension'] === 'mp3' || $path_parts['extension'] === 'ogg' || $path_parts['extension'] === 'wav'){
                $sanitized = str_replace('_', ' ', $path_parts['filename']);
                $m1 = Media::firstOrCreate(['title' => $sanitized, 'user_id' => 1]);
                $m1->update(['title' => $sanitized, "base_type" => "audio", 'poster_source' => '/img/404/poster.png', 'category_id' => $c4->id, 'description' => '', 'user_id' => 1]);
                $ms1 = MediaSource::firstOrCreate(['media_id' => $m1->id, 'title' => 'default', 'source_type' => 'default', 'duration' => '00:02:08', 'source' => $media, 'type' => 'localAudio']);
                $m1->tag('Audio');
                $m1->tag('Import '.$now);
            }
            echo ".";
            //$m1->tag('Audio');
        }
    }
}
