export class User{
  id:number;
  name:string;
  avatar:string;
  background:string;
  bio:string;
  roles:string;
  mediaIds:any;
  tagString:string;
  publicState:boolean;
  admin:boolean;
  email:string;
  friends:any;
  created_at:any;
  updated_at:any;
  constructor(jData:any){
    this.id=jData.id;
    this.name = jData.name;
    this.avatar = jData.avatar;
    this.background = jData.background;
    this.bio = jData.bio;
    this.mediaIds = jData.mediaIds;
    this.tagString = jData.tagString;
    this.publicState = jData.publicState;
    this.roles = jData.roles;
    this.email = jData.email;
    this.friends = jData.friends;
    this.created_at = jData.created_at;
    this.updated_at = jData.updated_at;

  }
  toJson(){
    return "{id:"+this.id+",name:'"+this.name+"',avatar:'"+this.avatar+"',background:'"+this.background+"'}"
  }
}
export class Media {
  id:number;
  title:string;
  description:string;
  sources:any;
  chapters:any;
  view:any;
  totalView:any;
  poster_source:string;
  duration:string;
  type:string;
  simpleType:string;
  techType:string;
  user_id:number;
  user:any;
  comments:any;
  tags:any;
  tagIds:any;
  created_at:string;
  updated_at:string;
  created_at_readable:string;
  tagString:string;
  myLike:number;
  likes:number;
  baseType:string;
  dislikes:number;
  urlTitle:string;
  tracks:any;
  category_id:number;
  category:Category;
  intro_start:number;
  outro_start:number;
  intro_end:number;
  outro_end:number;
  constructor(id:number,title:string,description:string,sources:any,baseType:string,chapters:any,view:any,totalView:any,poster_source:string,duration:string,user:any,user_id:any,created_at:string,updated_at:string,created_at_readable:string,comments:any,tags:any,myLike:number,likes:number,dislikes:number,tracks:any,category_id:number){
    this.id=id;
    this.baseType = baseType;
    this.title = title;
    this.chapters = chapters;
    this.view = view;
    this.totalView = totalView;
    this.urlTitle = encodeURIComponent(this.title)
    this.description = description;
    this.sources = sources;
    this.poster_source = poster_source;
    this.duration = duration;
    this.user = user;
    this.user_id = user_id;
    this.comments = comments;
    this.tags = tags;
    this.created_at = created_at;
    this.updated_at = updated_at;
    this.created_at_readable = created_at_readable;
    this.myLike=myLike;
    this.likes=likes;
    this.dislikes=dislikes;
    this.tagString = this.tagStringing();
    this.tracks = tracks;
    this.category_id = category_id;
  //  this.category = category;
  }
  tagStringing(){
    var theTagString = ""
    $.each(this.tags, function(key,val){
      theTagString += val.name+" "
    });
    return theTagString;
  }
  toJson(){
    return "{title:'"+this.title+"',description:'"+this.description+"',sources:'"+this.sources
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

export class Category {
  id:number;
  title:string;
  urlTitle:string;
  description:string;
  avatar:string;
  background:string;
  parent_id:number;
  children:any;
  constructor(id:number,title:string,description:string,avatar:string,background:string,parent_id:number,children:any){
    this.id=id;
    this.title=title;
    this.urlTitle = encodeURIComponent(this.title)
    this.description=description;
    this.avatar=avatar;
    this.background=background;
    this.parent_id=parent_id;
    this.children = [];
    let that = this;
    $.each( children, function( key1, value ) {
      that.children.push(new Category(value.id, value.title, value.description, value.avatar,value.background,value.parent_id,value.children))
    });
  }
}

export class Notification {
  id:number;
  type:string;
  data:any;
  read_at:string
  created_at:any
  constructor(id:number,type:string,data:any,read_at:string,created_at:any){
    this.id=id;
    this.type=type;
    this.data=data;
    this.read_at = read_at;
    this.created_at = created_at
  }
}

export class Playlist {
  id:number;
  title:string;
  description:string;
  media_ids:Array<number>
  read_at:string
  created_at:any
  constructor(id:number,title:string,description:string,media_ids:Array<number>,read_at:string,created_at:any){
    this.id=id;
    this.title=title;
    this.description=description;
    this.media_ids = media_ids
    this.read_at = read_at;
    this.created_at = created_at
  }
}
