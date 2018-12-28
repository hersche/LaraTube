export class MediaSorter {
  sortBy:string;
  constructor(){
    this.sortBy=localStorage.getItem("choosenSort")
    if(this.sortBy==""||this.sortBy==undefined){
      this.sortBy="created_at"
      localStorage.setItem("choosenSort","created_at")
    }
  }

  sort(medias){
    if(this.sortBy=="title"){
      medias.sort(MediaSorter.byTitle)
    } else if(this.sortBy=="title_reverse"){
      medias.sort(MediaSorter.byTitleReverse)
    } else if(this.sortBy=="created_at"){
      medias.sort(MediaSorter.byCreatedAt)
    }  else if(this.sortBy=="created_at_reverse"){
      medias.sort(MediaSorter.byCreatedAtReverse)
    } else if(this.sortBy=="type"){
      medias.sort(MediaSorter.byType)
    } else if(this.sortBy=="type_reverse"){
      medias.sort(MediaSorter.byTypeReverse)
    } else if(this.sortBy=="simpleType"){
      medias.sort(MediaSorter.byType)
    } else if(this.sortBy=="simpleType_reverse"){
      medias.sort(MediaSorter.byTypeReverse)
    } else if(this.sortBy=="updated_at"){
      medias.sort(MediaSorter.byUpdatedAt)
    } else if(this.sortBy=="updated_at_reverse"){
      medias.sort(MediaSorter.byUpdatedAtReverse)
    }else if(this.sortBy=="comments"){
      medias.sort(MediaSorter.byUpdatedAt)
    } else if(this.sortBy=="comments_reverse"){
      medias.sort(MediaSorter.byUpdatedAtReverse)
    }
    else if(this.sortBy=="likes"){
      medias.sort(MediaSorter.byLikes)
    } else if(this.sortBy=="likes_reverse"){
      medias.sort(MediaSorter.byLikesReverse)
    }
    else if(this.sortBy=="dislikes"){
      medias.sort(MediaSorter.byDislikes)
    } else if(this.sortBy=="dislikes_reverse"){
      medias.sort(MediaSorter.byDislikesReverse)
    }
    return medias
  }

  static byType(a,b) {
    if (a.type < b.type)
      return -1;
    if (a.type > b.type)
      return 1;
    return 0;
  }

  static byTypeReverse(a,b) {
    if (a.type < b.type)
      return 1;
    if (a.type > b.type)
      return -1;
    return 0;
  }

  static byComments(a,b) {
    if (a.comments.length < b.comments.length)
      return -1;
    if (a.comments.length > b.comments.length)
      return 1;
    return 0;
  }

  static byCommentsReverse(a,b) {
    if (a.comments.length < b.comments.length)
      return 1;
    if (a.comments.length > b.comments.length)
      return -1;
    return 0;
  }

  static bySimpleType(a,b) {
    if (a.simpleType < b.simpleType)
      return -1;
    if (a.simpleType > b.simpleType)
      return 1;
    return 0;
  }

  static bySimpleTypeReverse(a,b) {
    if (a.simpleType < b.simpleType)
      return 1;
    if (a.simpleType > b.simpleType)
      return -1;
    return 0;
  }

  static byTitle(a,b) {
    if (a.title < b.title)
      return -1;
    if (a.title > b.title)
      return 1;
    return 0;
  }

  static byTitleReverse(a,b) {
    if (a.title < b.title)
      return 1;
    if (a.title > b.title)
      return -1;
    return 0;
  }

  static byLikes(a,b) {
    if (a.likes < b.likes)
      return -1;
    if (a.likes > b.likes)
      return 1;
    return 0;
  }

  static byLikesReverse(a,b) {
    if (a.likes < b.likes)
      return 1;
    if (a.likes > b.likes)
      return -1;
    return 0;
  }

  static byDislikes(a,b) {
    if (a.dislikes < b.dislikes)
      return -1;
    if (a.dislikes > b.dislikes)
      return 1;
    return 0;
  }

  static byDislikesReverse(a,b) {
    if (a.dislikes < b.dislikes)
      return 1;
    if (a.dislikes > b.dislikes)
      return -1;
    return 0;
  }


  static byCreatedAt(a,b) {
    if (a.created_at.date < b.created_at.date)
      return 1;
    if (a.created_at.date > b.created_at.date)
      return -1;
    return 0;
  }
  static byCreatedAtComments(a,b) {
    if (a.created_at < b.created_at)
      return 1;
    if (a.created_at > b.created_at)
      return -1;
    return 0;
  }
  static byUpdatedAtReverse(a,b) {
    if (a.updated_at.date < b.updated_at.date)
      return -1;
    if (a.updated_at.date > b.updated_at.date)
      return 1;
    return 0;
  }


  static byUpdatedAt(a,b) {
    if (a.updated_at.date < b.updated_at.date)
      return 1;
    if (a.updated_at.date > b.updated_at.date)
      return -1;
    return 0;
  }
  static byCreatedAtReverse(a,b) {
    if (a.created_at.date < b.created_at.date)
      return -1;
    if (a.created_at.date > b.created_at.date)
      return 1;
    return 0;
  }

}


export class Search{
  mediaResult:any;
  tagResult:any;
  userResult:any;
  search:string;
  constructor(search:string,medias,tags,users){
    this.search = search;
    this.tagResult=[]
    this.userResult=[]
    this.mediaResult=[]
    if(search!=""){
    var mediaTitle = $("#theLiveSearchMediaTitle").is(':checked')
    var mediaDescription = $("#theLiveSearchMediaDescription").is(':checked')
    var tagsEnabled = $("#theLiveSearchTags").is(':checked')
    let that = this;
    if($("#theLiveSearchUsers").is(':checked')){
      $.each( users, function( key, value ) {

        if(value.name.toLowerCase().indexOf(that.search.toLowerCase()) > -1){
          if(that.userResult.includes(value)==false){
            that.userResult.push(value);
          }
        }
        if(value.bio!=null){
        if(value.bio.toLowerCase().indexOf(that.search.toLowerCase()) > -1){
          if(that.userResult.includes(value)==false){
            that.userResult.push(value);
          }
        }
      }

      });
    }
    if(mediaTitle||mediaDescription) {
      $.each( medias, function( key, value ) {
        if(mediaTitle){
        if(value.title.toLowerCase().indexOf(that.search.toLowerCase()) > -1){
          if(that.mediaResult.includes(value)==false){
            that.mediaResult.push(value);
          }
        }
      }
      if(mediaDescription){
        if(value.description.toLowerCase().indexOf(search.toLowerCase()) > -1){
          if(that.mediaResult.includes(value)==false){
            that.mediaResult.push(value);
          }
        }
      }
      var tms = new MediaSorter();
      that.mediaResult = tms.sort(that.mediaResult)
    /*  if(tagsEnabled){
        if(that.mediaResult.includes(value)==false){
          that.mediaResult.push(value);
        }
      }*/
      });
    }
    if($("#theLiveSearchMedias").is(':checked')){
      this.userResult=medias
    }
  }
  }

}
