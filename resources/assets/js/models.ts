export class User{
  id:number;
  name:string;
  avatar:string;
  background:string;
  bio:string;
  mediaIds:any;
  tagString:string;
  publicState:boolean;
  admin:boolean;
  email:string;
  created_at;
  updated_at;
  constructor(id:number,name:string,avatar:string,background:string,bio:string,mediaIds:any,tagString:string,publicState:boolean,admin:boolean=false,email:string='',created_at='',updated_at=''){
    this.id=id;
    this.name = name;
    this.avatar = avatar;
    this.background = background;
    this.bio = bio;
    this.mediaIds = mediaIds;
    this.tagString = tagString;
    this.publicState = publicState;
    this.admin = admin;
    this.email = email;
    this.created_at = created_at;
    this.updated_at = updated_at;

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
  dislikes:number;
  urlTitle:string;
  tracks:any;
  category_id:number;
  category:Category;
  constructor(id:number,title:string,description:string,source:string,poster_source:string,duration:string,simpleType:string,techType:string,type:string,user:any,user_id:any,created_at:string,updated_at:string,created_at_readable:string,comments:any,tags:any,myLike:number,likes:number,dislikes:number,tracks:any,category_id:number){
    this.id=id;
    this.title = title;
    this.urlTitle = encodeURIComponent(this.title)
    this.description = description;
    this.source = source;
    this.poster_source = poster_source;
    this.duration = duration;
    this.type = type;
    this.simpleType = simpleType;
    this.techType = techType;
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

export class Category {
  id:number;
  title:string;
  urlTitle:string;
  description:string;
  avatar:string;
  background:string;
  medias:any;
  parent_id:number;
  children:any;
  constructor(id:number,title:string,description:string,avatar:string,background:string,parent_id:number,children:any){
    this.id=id;
    this.title=title;
    this.urlTitle = encodeURIComponent(this.title)
    this.description=description;
    this.avatar=avatar;
    this.background=background;
    this.medias = []
    this.parent_id=parent_id;
    this.children = [];
    let that = this;
    $.each( children, function( key1, value ) {
      that.children.push(new Category(value.id, value.title, value.description, value.avatar_source,value.background_source,value.parent_id,value.children))
    });
  }
  setMedias(medias){
    let that = this;
    that.medias=[]
    $.each( medias, function( key1, value ) {
      if(value.category_id==that.id){
        if(that.medias.includes(value)==false){
          that.medias.push(value)
        }
      }
    });
  }
}

export class Notification {
  id:number;
  type:string;
  data:any;
  read_at:string
  created_at
  constructor(id:number,type:string,data:any,read_at:string,created_at){
    this.id=id;
    this.type=type;
    this.data=data;
    this.read_at = read_at;
    this.created_at = created_at
  }
}
