<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Media;

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


     $u = User::create(['name' => 'admin','public' => true,'bio' => 'Hi, i am a test-user. My e-mail is admin@admin.admin and my password is adminadmin - feel free to try, but remember: this is a work in progress and the database gets dropped from time to time!','email' => 'admin@admin.admin','password' => Hash::make('adminadmin')]);
     $u1 = User::create(['name' => 'bla','public' => true,'bio' => 'Hi, i am a test-user. My e-mail is bla@bla.bla and my password is blabla - feel free to try, but remember: this is a work in progress and the database gets dropped from time to time!','email' => 'bla@bla.bla','password' => Hash::make('blabla')]);
     $u2 = User::create(['name' => 'ni','public' => true,'bio' => 'Hi, i am a test-user. My e-mail is ni@ni.ni and my password is ninini - feel free to try, but remember: this is a work in progress and the database gets dropped from time to time!','email' => 'ni@ni.ni','password' => Hash::make('ninini')]);
     $u3 = User::create(['name' => 'na','public' => true,'bio' => 'Hi, i am a test-user. My e-mail is na@na.na and my password is nanana - feel free to try, but remember: this is a work in progress and the database gets dropped from time to time!','email' => 'na@na.na','password' => Hash::make('nanana')]);
     $u->tag('Movie');
     $u->tag('thing');
     $u1->tag('Some');
     $u1->tag('thing');
     $u2->tag('Test');
     $u2->tag('else');
     $u3->tag('Gardening');
     $u->befriend($u1);
     $u->befriend($u3);
     $u3->acceptFriendRequest($u);
     $u2->befriend($u3);
     $u2->befriend($u1);
     $u3->acceptFriendRequest($u2);
     $u1->acceptFriendRequest($u2);
     $m1 = Media::create(['title' => 'Audio test 1', 'source' => 'https://www.sample-videos.com/audio/mp3/wave.mp3', 'type' => 'directAudio','description' => 'A automatic generated testaudio, streamed from https://www.sample-videos.com.','user_id' => 3]);
     $m1->tag('Gardening');
     $m1->tag('Audio');
     $m1->tag('Test');
     $m1 = Media::create(['title' => 'Big bucks bunny test', 'source' => 'https://raw.githubusercontent.com/bower-media-samples/big-buck-bunny-1080p-30s/master/video.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from a github-file.','user_id' => 1]);
     $m1->tag('Some');
     $m1->tag('thing');
     $m1->tag('Movie');
     $m1 = Media::create(['title' => 'What is peertube', 'source' => 'magnet:?xs=https%3A%2F%2Fframatube.org%2Fstatic%2Ftorrents%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.torrent&xt=urn:btih:dc84b692c4002fec0cae873df0dc7f5d67fc09db&dn=What+is+PeerTube%3F&tr=wss%3A%2F%2Fframatube.org%2Ftracker%2Fsocket&tr=https%3A%2F%2Fframatube.org%2Ftracker%2Fannounce&ws=https%3A%2F%2Fframatube.org%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fpeertube.social%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fvideo.blueline.mg%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Ftube.bootlicker.party%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Ftube.tape.cx%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fpeertube2.cpy.re%2Fstatic%2Fredundancy%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fvideos.tcit.fr%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Ftubercul.es%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fwatching.cypherpunk.observer%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4&ws=https%3A%2F%2Fpeertube.video%2Fstatic%2Fwebseed%2F9c9de5e8-0a1e-484a-b099-e80766180a6d-1080.mp4', 'type' => 'torrentVideo','description' => 'Via webtorrent from peertube.video .','user_id' => 1]);
     $m1->tag('Explain');
     $m1->tag('Torrent');
     $m1->tag('Movie');
     $m1 = Media::create(['title' => 'Big bucks torrent', 'source' => 'magnet:?xs=https%3A%2F%2Fpeertube2.cpy.re%2Fstatic%2Ftorrents%2F5a6133b8-3e0c-40dc-b859-69d0540c3fe5-1080.torrent&xt=urn:btih:dad4bdc58864f5e54df4805b9e5467e55422e2c9&dn=Big+Buck+Bunny&tr=wss%3A%2F%2Fpeertube2.cpy.re%2Ftracker%2Fsocket&tr=https%3A%2F%2Fpeertube2.cpy.re%2Ftracker%2Fannounce&ws=https%3A%2F%2Fpeertube2.cpy.re%2Fstatic%2Fwebseed%2F5a6133b8-3e0c-40dc-b859-69d0540c3fe5-1080.mp4&ws=https%3A%2F%2Fvideos.tcit.fr%2Fstatic%2Fwebseed%2F5a6133b8-3e0c-40dc-b859-69d0540c3fe5-1080.mp4&ws=https%3A%2F%2Fpeertube.social%2Fstatic%2Fwebseed%2F5a6133b8-3e0c-40dc-b859-69d0540c3fe5-1080.mp4', 'type' => 'torrentVideo','description' => 'Via webtorrent from peertube.video .','user_id' => 1]);
     $m1->tag('Big');
     $m1->tag('Torrent');
     $m1->tag('Movie');
     $m1 = Media::create(['title' => 'Walulis - Youtube review 2018', 'source' => 'magnet:?xs=https%3A%2F%2Fpeertube.video%2Fstatic%2Ftorrents%2Fea2da85a-0cd7-4e6d-aaf5-ff47592853a7-1080.torrent&xt=urn:btih:cffcacbc092ba06a85f07b0143de58e0f69bfc2a&dn=Rewind+2018%3A+Das+schlechteste+YouTube+Video+ever!%3F+%7C+WALULIS&tr=wss%3A%2F%2Fpeertube.video%3A443%2Ftracker%2Fsocket&tr=https%3A%2F%2Fpeertube.video%2Ftracker%2Fannounce&ws=https%3A%2F%2Fpeertube.video%2Fstatic%2Fwebseed%2Fea2da85a-0cd7-4e6d-aaf5-ff47592853a7-1080.mp4', 'type' => 'torrentVideo','description' => 'Via webtorrent from peertube.video .','user_id' => 1]);
     $m1->tag('Walulis');
     $m1->tag('Torrent');
     $m1->tag('Movie');
     $m1 = Media::create(['title' => 'Hackerethik - eine Einführung', 'source' => 'https://cdn.media.ccc.de/congress/2018/h264-sd/35c3-10011-deu-eng-fra-Hackerethik_-_eine_Einfuehrung_sd.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from https://media.ccc.de','user_id' => 4]);
     $m1->tag('35C3');
     $m1->tag('Doku');
     $m1 = Media::create(['title' => 'Freude ist nur ein Mangel an information', 'source' => 'https://cdn.media.ccc.de/congress/2018/h264-sd/35c3-9506-deu-eng-gsw-Freude_ist_nur_ein_Mangel_an_Information_sd.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from https://media.ccc.de','user_id' => 4]);
     $m1->tag('35C3');
     $m1->tag('Doku');
     $m1 = Media::create(['title' => 'Big bucks bunny test 2', 'source' => 'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_2mb.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from https://www.sample-videos.com','user_id' => 2]);
     $m1->tag('Big');
     $m1->tag('SampleVideos');
     $m1 = Media::create(['title' => 'Audio test 3', 'source' => 'https://www.sample-videos.com/audio/mp3/wave.mp3', 'type' => 'directAudio','description' => 'A automatic generated testaudio, streamed from https://www.sample-videos.com.','user_id' => 3]);
     $m1->tag('Audio');
     $m1->tag('SampleVideos');
     $m1 = Media::create(['title' => 'Big test', 'source' => 'https://raw.githubusercontent.com/bower-media-samples/big-buck-bunny-1080p-30s/master/video.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from a github-file.','user_id' => 3]);
     $m1->tag('Movie');
     $m1->tag('Github');
     $m1 = Media::create(['title' => 'Big bucks test', 'source' => 'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_2mb.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from https://www.sample-videos.com','user_id' => 2]);
     $m1->tag('Some');
     $m1->tag('Movie');
     $m1->tag('Animation');
     $m1 = Media::create(['title' => 'Ocean test', 'source' => 'http://vjs.zencdn.net/v/oceans.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from http://vjs.zencdn.net/v/oceans.mp4','user_id' => 1]);
     $m1->tag('Movie');
     $m1->tag('ZENCDN');
     $m1->tag('Nature');
     $m1 = Media::create(['title' => 'Another Ocean test', 'source' => 'http://vjs.zencdn.net/v/oceans.mp4', 'type' => 'directVideo','description' => 'A automatic generated testvideo, streamed from http://vjs.zencdn.net/v/oceans.mp4','user_id' => 1]);
     $m1->tag('Movie');
     $m1->tag('ZENCDN');
     $m1->tag('Some');
     $m1 = Media::create(['title' => 'Audio test 2', 'source' => 'https://www.sample-videos.com/audio/mp3/wave.mp3', 'type' => 'directAudio','description' => 'A automatic generated testaudio, streamed from https://www.sample-videos.com.','user_id' => 3]);
     $m1->tag('Some');
     $m1->tag('Audio');
     $m1->tag('SampleVideos');

     //$u->assignRole($permissions);
     foreach ($permissions as $permission) {

          Permission::create(['name' => $permission]);

     }
     $u->syncPermissions($permissions);
     $u->save();
     $u1->syncPermissions($permissions);
     $u1->save();
     $u2->syncPermissions($permissions);
     $u2->save();
     $u3->syncPermissions($permissions);
     $u3->save();
    }
}
