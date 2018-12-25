export class User{
  id:number;
  name:string;
  avatar:string;
  background:string;
  bio:string;
  mediaIds:any;
  constructor(id:number,name:string,avatar:string,background:string,bio:string,mediaIds:any){
    this.id=id;
    this.name = name;
    this.avatar = avatar;
    this.background = background;
    this.bio = bio;
    this.mediaIds = mediaIds;
  }
  toJson(){
    return "{id:"+this.id+",name:'"+this.name+"',avatar:'"+this.avatar+"',background:'"+this.background+"'}"
  }
}
export class Media {
  id:number;
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
  tagIds:any;
  created_at:string;
  created_at_readable:string;
  tagString:string;


  constructor(id:number,title:string,description:string,source:string,poster_source:string,simpleType:string,type:string,user:any,user_id:any,created_at:string,created_at_readable:string,comments:any,tags:any,tagIds:any=undefined){
    this.id=id;
    this.title = title;
    this.description = description;
    this.source = source;
    this.poster_source = poster_source;
    this.type = type;
    this.simpleType = simpleType;
    this.user = user;
    this.user_id = user_id;
    this.comments = comments;
    this.tags = tags;
    this.tagIds = tagIds;
    this.created_at = created_at;
    this.created_at_readable = created_at_readable;
    this.tagString = this.tagStringing();
  }
  tagStringing(){
    var theTagString = ""
    $.each(this.tags, function(key,val){
      console.log(val.name)
      theTagString += val.name+" "
    });
    return theTagString;
  }
  toJson(){
    return "{title:'"+this.title+"',description:'"+this.description+"',source:'"+this.source
  }

}

export class Tag {
  id:number;
  name:string;
  slug:string;
  count:number;
  constructor(id:number,name:string,slug:string,count:number){
    this.id=id;
    this.name=name;
    this.slug=slug;
    this.count=count;
  }
}
