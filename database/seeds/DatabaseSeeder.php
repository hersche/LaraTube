<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Media;
use App\MediaSource;
use App\MediaView;
use App\MediaChapter;
use App\Like;
use App\Comment;
use App\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $permissions = [
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
        'admin',
        'role-admin',
        'user-admin',
        'video-admin',
        'moderator'
        ];
     $c1 = Category::create(["title"=>"Animations","description"=>"Testdescription filled with
### animations","avatar"=>"","background"=>"","created_at" => "2019-01-17 03:21:52"]);
     $c2 = Category::create(["title"=>"Funk","description"=>"This is the second
### autogenerated
category","avatar"=>"","background"=>""]);
     $c21 = Category::create(["title"=>"Walulis","parent_id"=>$c2->id,"description"=>"This is the sub of
### Funk","avatar"=>"","background"=>""]);
     $c3 = Category::create(["title"=>"CCC","description"=>"CCC-Stuff","avatar"=>"","background"=>""]);
     $c31 = Category::create(["title"=>"35C3","parent_id"=>$c3->id,"description"=>"For
### 35C3","avatar"=>"","background"=>""]);
     $c4 = Category::create(["title"=>"Music","description"=>"This is the 4 autogenerated category","avatar"=>"","background"=>""]);
     $u = User::create(['name' => 'admin',"username"=>"admin",'avatar'=>'testing/user/avatars/1.png','public' => true,'bio' => 'Hi, i am a test-user. My e-mail is admin@admin.admin and my password is

      ## adminadmin
       - feel free to try, but remember: this is a work in progress and the database gets dropped from time to time!','email' => 'admin@admin.admin']);
     $u1 = User::create(['name' => 'bla',"username"=>"bla",'avatar'=>'testing/user/avatars/2.png','public' => true,'bio' => 'Hi, i am a test-user. My e-mail is

## bla@bla.bla

and my password is

## blabla
         - feel free to try, but remember: this is a work in progress and the database gets dropped from time to time!','email' => 'bla@bla.bla']);
     $u2 = User::create(['name' => 'ni',"username"=>"ni",'avatar'=>'testing/user/avatars/3.png','public' => true,'bio' => 'Hi, i am a

### test-user
. My e-mail is ni@ni.ni and my password is ninini - feel free to try, but remember:
this is a work in progress and the database gets dropped from time to time!','email' => 'ni@ni.ni']);
     $u3 = User::create(['name' => 'na',"username"=>"na",'avatar'=>'testing/user/avatars/4.png','public' => true,'bio' => 'Hi, i am a test-user. My e-mail is na@na.na and my password is nanana - feel free to try, but remember: this is a work in progress and the database gets dropped from time to time!','email' => 'na@na.na']);
     $u->tag('Movie');
     $u1->tag('User');
     $u2->tag('Audio');
     $u2->tag('Torrent');
     $u->befriend($u1);
     $u->befriend($u3);
     $u3->acceptFriendRequest($u);
     $u2->befriend($u3);
     $u2->befriend($u1);
     $u3->acceptFriendRequest($u2);
     $u1->acceptFriendRequest($u2);
     $m1 = Media::create(['title' => 'Monplaisir - 10 - Staring at the void between me and the wall',"base_type"=>"audio","created_at" => "2019-01-21 03:21:52",'poster_source' => 'testing/media/posters/mon.png','category_id' => $c4->id, 'description' => 'Found at freemusicarchive.org . Nicer than noise only :)','user_id' => 2]);
     $m1->tag('Audio');

     $ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','source_type'=>'default','duration' =>'00:02:08','type'=>'default','source' => 'testing/media/medias/Monplaisir_-_10_-_Staring_at_the_void_between_me_and_the_wall.mp3', 'type' => 'localAudio']);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>27,'user_id'=>1]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>10,'user_id'=>2]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>43,'user_id'=>3]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>9,'user_id'=>4]);
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $co1 = Comment::create(['media_id' => $m1->id,"created_at" => "2019-01-22 03:21:52",'user_id' => $u3->id, 'body'=>'A autogenerated comment']);
     $co1 = Comment::create(['media_id' => $m1->id,"created_at" => "2019-01-23 03:21:52",'user_id' => $u1->id, 'body'=>'And another autogenerated comment']);
     $cso1 = Comment::create(['media_id' => $m1->id,'user_id' => $u3->id,"created_at" => "2019-01-24 06:21:52", 'parent_id' => $co1->id,'body'=>'A autogenerated sub-comment']);
     $m1 = Media::create(['title' => 'Monplaisir - 08 - It sounds so true',"base_type"=>"audio",'poster_source' => 'testing/media/posters/mon.png','category_id' => $c4->id,'description' => 'Found at freemusicarchive.org . Nicer than noise only :)','user_id' => 2]);
     $ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','source_type'=>'default','type'=>'default', 'source' => 'testing/media/medias/Monplaisir_-_08_-_It_sounds_so_true.mp3', 'type' => 'localAudio']);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Audio');
     $m1->tag('Noisy');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Eine kurze Geschichte des CCC - die 1980er',"base_type"=>"video",'poster_source' => 'testing/media/posters/6.png','category_id' => $c3->id,'description' => 'Direct stream from
## youtube','user_id' => 2]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','source_type'=>'default','type'=>'default','duration' =>'00:14:06', 'source' => 'testing/media/medias/Monplaisir_-_08_-_It_sounds_so_true.mp3', 'type' => 'localAudio']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Youtube');
     $m1->tag('Doku');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Raggatek Mix 2012 (Herbalistek)',"base_type"=>"video",'poster_source' => 'testing/media/posters/4.png','category_id' => $c3->id,'description' => 'Direct stream from
## youtube','user_id' => 2]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:14:06','source_type'=>'default','type'=>'default', 'source' => 'EjwoINqxwdc', 'type' => 'youtube']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Youtube');
     $m1->tag('Tekk');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Psyche Corporation - 04 - The Crime',"base_type"=>"audio","created_at" => "2019-01-13 03:21:52",'poster_source' => 'testing/media/posters/psych.png','category_id' => $c4->id,'description' => 'Found at freemusicarchive.org
#### Nicer than noise only :)','user_id' => 3]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','source_type'=>'default','duration' =>'00:04:11','type'=>'default', 'source' => 'testing/media/medias/Psyche_Corporation_-_04_-_The_Crime.mp3', 'type' => 'localAudio']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Audio');
     $m1 = Media::create(['title' => 'Psyche Corporation - 03 - Wonderland',"base_type"=>"audio","created_at" => "2018-04-15 03:21:52",'poster_source' => 'testing/media/posters/psych.png','category_id' => $c4->id,'description' => 'Found at freemusicarchive.org .
# Nicer than noise only :)
with intro=10','user_id' => 3]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','source_type'=>'default','duration' =>'00:03:25','type'=>'default', 'source' => 'testing/media/medias/Psyche_Corporation_-_03_-_Wonderland.mp3', 'type' => 'localAudio']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Audio');
     $m1 = Media::create(['title' => 'LOCAL Audio test 1',"base_type"=>"audio",'poster_source' => 'testing/media/posters/4.png','category_id' => $c4->id,'description' => 'A automatic generated testaudio, streamed from
## local
with intro=10','user_id' => 3]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:00:45','source_type'=>'default','type'=>'default', 'source' => 'testing/media/medias/1.mp3', 'type' => 'localAudio']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Audio');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1->tag('Noisy');
     $m1 = Media::create(['title' => 'LaraTube gourced',"base_type"=>"video",'poster_source' => 'testing/media/posters/gource.png','category_id' => $c4->id,'description' => 'This video was generated with
     gource -1280x720 -a 0.01 -s 0.1 --stop-at-end  -o - | ffmpeg -y -r 60 -f image2pipe -vcodec ppm -i - -vcodec libx264 -preset ultrafast -pix_fmt yuv420p -crf 35 -threads 0 -bf 0 gource.mp4','user_id' => 3]);
     $ms1 = MediaSource::create(['media_id'=>$m1->id,'duration' =>'00:00:28','title'=>'default','source_type'=>'default','type'=>'default', 'source' => 'testing/media/medias/gource.mp4', 'type' => 'localVideo']);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Video');
     $m1->tag('Gource');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>-1]);
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u3->id, 'body'=>'A
## autogenerated
comment']);
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u1->id, 'body'=>'And another autogenerated comment']);
     $cso1 = Comment::create(['media_id' => $m1->id,'user_id' => $u3->id, 'parent_id' => $co1->id,'body'=>'A autogenerated sub-comment']);
     $m1->tag('Audio');
     $m1->tag('Test');
     $m1 = Media::create(['title' => 'Big bucks bunny test',"base_type"=>"video","created_at" => "2017-08-17 03:21:52",'poster_source' => 'testing/media/posters/3.png','category_id' => $c1->id,'description' => 'A automatic generated
## testvideo
, streamed from a github-file.','user_id' => 1]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:00:30','source_type'=>'default','type'=>'default', 'source' => 'https://raw.githubusercontent.com/bower-media-samples/big-buck-bunny-1080p-30s/master/video.mp4', 'type' => 'directVideo']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $co1 = Comment::create(['media_id' => $m1->id,"created_at" => "2017-11-17 03:21:52", 'user_id' => $u1->id, 'body'=>'A autogenerated comment']);
     $co1 = Comment::create(['media_id' => $m1->id,"created_at" => "2017-10-08 03:21:52",'user_id' => $u2->id, 'body'=>'And another autogenerated comment']);
     $cso1 = Comment::create(['media_id' => $m1->id,"created_at" => "2018-03-17 06:21:52",'user_id' => $u1->id, 'parent_id' => $co1->id,'body'=>'A autogenerated sub-comment']);
     $m1->tag('Animation');
     $m1->tag('Movie');
     $m1 = Media::create(['title' => 'What is peertube',"base_type"=>"video","created_at" => "2018-08-17 03:21:52","updated_at" => "2018-09-17 03:21:52",'category_id' => 0,'description' => 'Via webtorrent from
## peertube.video .','user_id' => 1]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:01:54','source_type'=>'default','type'=>'default', 'source' => 'magnet:?xs=https%3A%2F%2Fframatube.org%2Fstatic%2Ftorrents%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.torrent&xt=urn:btih:dc84b692c4002fec0cae873df0dc7f5d67fc09db&dn=What+is+PeerTube%3F&tr=wss%3A%2F%2Fframatube.org%2Ftracker%2Fsocket&tr=https%3A%2F%2Fframatube.org%2Ftracker%2Fannounce&ws=https%3A%2F%2Fframatube.org%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fpeertube.social%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fvideo.blueline.mg%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Ftube.bootlicker.party%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Ftube.tape.cx%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fpeertube2.cpy.re%2Fstatic%2Fredundancy%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fvideos.tcit.fr%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Ftubercul.es%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fwatching.cypherpunk.observer%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fpeertube.video%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4', 'type' => 'torrentVideo']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Explain');
     $m1->tag('Torrent');
     $m1->tag('Movie');
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u1->id, 'body'=>'A autogenerated comment']);
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u2->id, 'body'=>'And another autogenerated comment']);
     $cso1 = Comment::create(['media_id' => $m1->id,'user_id' => $u3->id, 'parent_id' => $co1->id,'body'=>'A autogenerated sub-comment']);
     $m1 = Media::create(['title' => 'Big bucks torrent',"created_at" => "2019-01-02 08:30:52","updated_at" => "2019-01-02 08:30:52",'poster_source' => 'testing/media/posters/3.png','category_id' => $c1->id,'description' => 'Via webtorrent from peertube.video
  #### With intro=30','user_id' => 1]);

  $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
  $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
  $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
  $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Big');
     $m1->tag('Torrent');
     $m1->tag('Movie');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => '35C3 ChaosWest - wirtschaft hacken! (Youtube-test)',"created_at" => "2017-01-17 03:21:52","updated_at" => "2017-01-17 03:21:52",'poster_source' => 'testing/media/posters/6.png','category_id' => $c31->id,'description' => 'Via youtube','user_id' => 1]);
       $ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:41:30', 'source' => 'Xch63n9VTyQ', 'type' => 'youtube']);
     $m1->tag('35C3');
     $m1->tag('Torrent');
     $m1->tag('Movie');

     $m1 = Media::create(['title' => 'Sintel Trailer',"created_at" => "2018-11-21 12:45:52",'poster_source' => 'testing/media/posters/5.png', 'category_id' => $c1->id,'description' => 'Via webtorrent from
## peertube.video
with intro=10 and outro=40','user_id' => 2]);
  $ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:00:52', 'source' => 'magnet:?xs=https%3A%2F%2Fpeertube.cpy.re%2Fstatic%2Ftorrents%2Fe2651856-4809-408a-99d4-b85b01fefb09-1080.torrent&xt=urn:btih:891e6eb6b30412afc9a52a3270de17edddcce71a&dn=Sintel+trailer&tr=wss%3A%2F%2Fpeertube.cpy.re%2Ftracker%2Fsocket&tr=https%3A%2F%2Fpeertube.cpy.re%2Ftracker%2Fannounce&ws=https%3A%2F%2Fpeertube.cpy.re%2Fstatic%2Fwebseed%2Fe2651856-4809-408a-99d4-b85b01fefb09-1080.mp4', 'type' => 'torrentVideo']);
     $m1->tag('Torrent');
     $m1->tag('Movie');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u1->id, 'body'=>'A autogenerated comment']);
     $co1 = Comment::create(['media_id' => $m1->id, 'user_id' => $u1->id,'body'=>'And another autogenerated comment']);
     $cso1 = Comment::create(['media_id' => $m1->id,'user_id' => $u1->id, 'parent_id' => $co1->id,'body'=>'A autogenerated sub-comment']);

     $m1 = Media::create(['title' => 'Caminandes 3: Llamigos',"created_at" => "2019-12-07 09:15:52",'poster_source' => 'testing/media/posters/2.png','category_id' => $c1->id,'description' => "In this episode of the Caminandes cartoon series we get to know our hero Koro even better!

It's winter in Patagonia, food is getting scarce. Koro the Llama engages with Oti the pesky penguin in an epic fight over that last tasty berry.",'user_id' => 2]);
       $ms1 = MediaSource::create(['media_id'=>$m1->id,'duration' =>'00:02:30','title'=>'default','duration' =>'00:00:52', 'source' => 'magnet:?xs=https%3A%2F%2Fvideo.blender.org%2Fstatic%2Ftorrents%2F23f3ef79-15dc-44c5-aa45-cf92e78a4509-1080.torrent&xt=urn:btih:ae1d6947ea6544ae6ad53d1517805315d9ba6b3a&dn=Caminandes+3%3A+Llamigos+-+Funny+3D+Animated+Short&tr=wss%3A%2F%2Fvideo.blender.org%2Ftracker%2Fsocket&tr=https%3A%2F%2Fvideo.blender.org%2Ftracker%2Fannounce&ws=https%3A%2F%2Fvideo.blender.org%2Fstatic%2Fwebseed%2F23f3ef79-15dc-44c5-aa45-cf92e78a4509-1080.mp4', 'type' => 'torrentVideo']);
     $m1->tag('Torrent');
     $m1->tag('Movie');
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u1->id, 'body'=>'A autogenerated comment']);
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u->id, 'body'=>'And another autogenerated comment']);
     $cso1 = Comment::create(['media_id' => $m1->id,'user_id' => $u3->id, 'parent_id' => $co1->id,'body'=>'A autogenerated sub-comment']);
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u2->id, 'body'=>'A autogenerated comment']);
     $co1 = Comment::create(['media_id' => $m1->id,'user_id' => $u1->id, 'body'=>'And another autogenerated comment']);
     $cso1 = Comment::create(['media_id' => $m1->id,'user_id' => $u2->id, 'parent_id' => $co1->id,'body'=>'A autogenerated sub-comment']);
     $m1 = Media::create(['title' => 'Tears of Steel','description' => "This is the fourth Open Movie by Blender Foundation, re-rendered and re-graded in 4k resolution.

(This is the HD scaled down version).",'user_id' => 4]);
$ms1 = MediaSource::create(['media_id'=>$m1->id,'duration' =>'00:02:30','title'=>'default','source' => 'magnet:?xs=https%3A%2F%2Fvidcommons.org%2Fstatic%2Ftorrents%2F29b9b400-e31b-4299-8b2e-d941ee563e4a-800.torrent&xt=urn:btih:e1a0bde31b98754471018a04c7b63e87ca35dfdf&dn=Tears+of+Steel&tr=wss%3A%2F%2Fvidcommons.org%2Ftracker%2Fsocket&tr=https%3A%2F%2Fvidcommons.org%2Ftracker%2Fannounce&ws=https%3A%2F%2Fvidcommons.org%2Fstatic%2Fwebseed%2F29b9b400-e31b-4299-8b2e-d941ee563e4a-800.mp4', 'type' => 'torrentVideo']);

     $m1->tag('Torrent');
     $m1->tag('Movie');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Elephants Dream',"created_at" => "2018-09-29 03:21:52",'poster_source' => 'testing/media/posters/7.png','category_id' => $c1->id,'description' => "The story of two strange characters exploring a capricious and seemingly infinite machine.
The elder, Proog, acts as a tour-guide and protector, happily showing off the sights and dangers of the machine to his initially curious but increasingly skeptical protege Emo.

As their journey unfolds we discover signs that the machine is not all Proog thinks it is, and his guiding takes on a more desperate aspect.",'user_id' => 4]);

$ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:10:54','source_type'=>'default','type'=>'default', 'source' => 'magnet:?xs=https%3A%2F%2Fvidcommons.org%2Fstatic%2Ftorrents%2F10b36139-be2c-4bf3-b536-6c8c98eb3db3-1080.torrent&xt=urn:btih:dd6a2a0e0862207407acf1e273fd03cbc3f0be1f&dn=Elephants+Dream&tr=wss%3A%2F%2Fvidcommons.org%2Ftracker%2Fsocket&tr=https%3A%2F%2Fvidcommons.org%2Ftracker%2Fannounce&ws=https%3A%2F%2Fvidcommons.org%2Fstatic%2Fwebseed%2F10b36139-be2c-4bf3-b536-6c8c98eb3db3-1080.mp4', 'type' => 'torrentVideo']);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
$mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);
     $m1->tag('Torrent');
     $m1->tag('Movie');

     $m1 = Media::create(['title' => 'Cosmos Laundromat - First Cycle','poster_source' => 'testing/media/posters/8.png','category_id' => $c1->id,'description' => "On a desolate island, suicidal sheep Franck meets his fate in a quirky salesman, who offers him the gift of a lifetime. Little does he know that he can only handle so much lifetime.",'user_id' => 3]);
     $ms1 = MediaSource::create(['media_id'=>$m1->id,'title'=>'default','duration' =>'00:12:11','source_type'=>'default','type'=>'default', 'source' => 'magnet:?xs=https%3A%2F%2Fvidcommons.org%2Fstatic%2Ftorrents%2F64e9f31c-6211-4754-902d-2561db080c7a-804.torrent&xt=urn:btih:6c64a4e8d4983beef06c01dcb8432991454366b3&dn=Cosmos+Laundromat+-+First+Cycle&tr=wss%3A%2F%2Fvidcommons.org%2Ftracker%2Fsocket&tr=https%3A%2F%2Fvidcommons.org%2Ftracker%2Fannounce&ws=https%3A%2F%2Fvidcommons.org%2Fstatic%2Fwebseed%2F64e9f31c-6211-4754-902d-2561db080c7a-804.mp4', 'type' => 'torrentVideo']);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>3,'user_id'=>1]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>44,'user_id'=>2]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>100,'user_id'=>3]);
     $mv1 = MediaView::create(['media_id'=>$m1->id,'seconds'=>90,'user_id'=>4]);

     $m1->tag('Torrent');
     $m1->tag('Movie');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>-1]);
  /*   $m1 = Media::create(['title' => 'Walulis - Youtube review 2018',"created_at" => "2019-01-01 00:00:00",'category_id' => $c21->id, 'source' => 'magnet:?xs=https%3A%2F%2Fpeertube.video%2Fstatic%2Ftorrents%2Fea2da85a-0cd7-4e6d-aaf5-ff47592853a7-1080.torrent&xt=urn:btih:cffcacbc092ba06a85f07b0143de58e0f69bfc2a&dn=Rewind+2018%3A+Das+schlechteste+YouTube+Video+ever!%3F+%7C+WALULIS&tr=wss%3A%2F%2Fpeertube.video%3A443%2Ftracker%2Fsocket&tr=https%3A%2F%2Fpeertube.video%2Ftracker%2Fannounce&ws=https%3A%2F%2Fpeertube.video%2Fstatic%2Fwebseed%2Fea2da85a-0cd7-4e6d-aaf5-ff47592853a7-1080.mp4', 'type' => 'torrentVideo','description' => 'Via webtorrent from peertube.video .','user_id' => 1]);
     $m1->tag('Walulis');
     $m1->tag('Torrent');
     $m1->tag('Movie');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Hackerethik - eine Einführung','duration' =>'00:40:57',"created_at" => "2018-12-27 07:21:52","updated_at" => "2018-12-27 07:21:52",'poster_source' => 'testing/media/posters/6.png','category_id' => $c31->id, 'source' => 'https://cdn.media.ccc.de/congress/2018/h264-sd/35c3-10011-deu-eng-fra-Hackerethik_-_eine_Einfuehrung_sd.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from https://media.ccc.de','user_id' => 4]);
     $m1->tag('35C3');
     $m1->tag('Doku');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Freude ist nur ein Mangel an information',"created_at" => "2018-12-27 03:21:52",'poster_source' => 'testing/media/posters/6.png','category_id' => $c31->id, 'source' => 'https://cdn.media.ccc.de/congress/2018/h264-sd/35c3-9506-deu-eng-gsw-Freude_ist_nur_ein_Mangel_an_Information_sd.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from https://media.ccc.de','user_id' => 4]);
     $m1->tag('35C3');
     $m1->tag('Doku');
     $m1 = Media::create(['title' => 'Big bucks bunny test 2','poster_source' => 'testing/media/posters/3.png', 'source' => 'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_2mb.mp4','category_id' => $c1->id, 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from
- https://www.sample-videos.com','user_id' => 2]);
     $m1->tag('Big');
     $m1->tag('SampleVideos');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Audio test', 'source' => 'https://www.sample-videos.com/audio/mp3/wave.mp3', 'type' => 'directAudio','description' => 'A automatic generated testaudio, streamed from https://www.sample-videos.com.','user_id' => 3]);
     $m1->tag('Audio');
     $m1->tag('SampleVideos');
     $m1 = Media::create(['title' => 'Big test','poster_source' => 'testing/media/posters/3.png', 'source' => 'https://raw.githubusercontent.com/bower-media-samples/big-buck-bunny-1080p-30s/master/video.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from a
- github-file.','user_id' => 3]);
    Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>1]);
    Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>-1]);
    Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>-1]);
     $m1->tag('Movie');
     $m1->tag('Github');
     $m1 = Media::create(['title' => 'Ocean test',"created_at" => "2017-05-11 11:11:12",'poster_source' => 'testing/media/posters/1.png', 'source' => 'http://vjs.zencdn.net/v/oceans.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from http://vjs.zencdn.net/v/oceans.mp4','user_id' => 1]);
     $m1->tag('Movie');
     $m1->tag('ZENCDN');
     $m1->tag('Nature');
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
     $m1 = Media::create(['title' => 'Big bucks test',"created_at" => "2017-08-11 11:11:12", 'poster_source' => 'testing/media/posters/3.png','source' => 'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_2mb.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from https://www.sample-videos.com','user_id' => 2]);
     $m1->tag('Movie');
     $m1->tag('Animation');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>-1]);
     $m1 = Media::create(['title' => 'ANOTHER Ocean test',"created_at" => "2017-09-11 11:11:12",'category_id' => $c4->id,'poster_source' => 'testing/media/posters/1.png', 'source' => 'http://vjs.zencdn.net/v/oceans.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from http://vjs.zencdn.net/v/oceans.mp4','user_id' => 3]);
     $m1->tag('Movie');
     $m1->tag('ZENCDN');
     Like::create(['user_id'=>$u->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u1->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u2->id,'media_id'=>$m1->id,'count'=>-1]);
     Like::create(['user_id'=>$u3->id,'media_id'=>$m1->id,'count'=>1]);
*/
     //$u->assignRole($permissions);
     $role = config('roles.models.role')::where('name', '=', 'Admin')->first();
     // $u->attachRole($role);
     // $u1->attachRole($role);
     // $u2->attachRole($role);
     // $u3->attachRole($role);
     /*$u->syncPermissions($permissions);
     $u->save();
     $u1->syncPermissions($permissions);
     $u1->save();
     $u2->syncPermissions($permissions);
     $u2->save();
     $u3->syncPermissions($permissions);
     $u3->save();*/
    }
}
