class User{
  id:number;
  name:string;
  avatar:string;
  constructor(id:number,name:string,avatar:string){
    this.id=id;
    this.name = name;
    this.avatar = avatar;
  }
}
class Media {
  title:string;
  description:string;
  source:string;
  poster_source:string;
  type:string;
  simpleType:string;
  user_id:number;
  user:any;
  comments:any;
  tags:any;
  created_at:string;
  created_at_readable:string;


  constructor(title:string,description:string,source:string,poster_source:string,simpleType:string,type:string,user:any,created_at:string,created_at_readable:string,comments:any,tags:any){
    this.title = title;
    this.description = description;
    this.source = source;
    this.poster_source = poster_source;
    this.type = type;
    this.simpleType = simpleType;
    this.user = user;
    this.comments = comments;
    this.tags = tags;
    this.created_at = created_at;
    this.created_at_readable = created_at_readable;
  }
/*

@if ($media->type=="localVideo" || $media->type=="directVideo"|| $media->type=="torrentVideo")
  <video class="col-12" id="player" poster="{{ url($media->poster()) }}" playsinline controls>
    @if ($media->type=="localVideo" || $media->type=="directVideo")
      <source src="{{ url($media->source) }}" type="video/mp4">
    @endif
  </video>

*/

};
