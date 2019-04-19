export class User {
    constructor(jData) {
        this.id = jData.id;
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
    toJson() {
        return "{id:" + this.id + ",name:'" + this.name + "',avatar:'" + this.avatar + "',background:'" + this.background + "'}";
    }
}
export class Media {
    constructor(id, title, description, sources, baseType, chapters, view, totalView, poster_source, duration, user, user_id, created_at, updated_at, created_at_readable, comments, tags, myLike, likes, dislikes, tracks, category_id) {
        this.id = id;
        this.baseType = baseType;
        this.title = title;
        this.chapters = chapters;
        this.view = view;
        this.totalView = totalView;
        this.urlTitle = encodeURIComponent(this.title);
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
        this.myLike = myLike;
        this.likes = likes;
        this.dislikes = dislikes;
        this.tagString = this.tagStringing();
        this.tracks = tracks;
        this.category_id = category_id;
        //  this.category = category;
    }
    tagStringing() {
        var theTagString = "";
        $.each(this.tags, function (key, val) {
            theTagString += val.name + " ";
        });
        return theTagString;
    }
    toJson() {
        return "{title:'" + this.title + "',description:'" + this.description + "',sources:'" + this.sources;
    }
}
export class Tag {
    constructor(id, name, slug, count) {
        this.id = id;
        this.name = name;
        this.slug = slug;
        this.count = count;
    }
}
export class Category {
    constructor(id, title, description, avatar, background, parent_id, children) {
        this.id = id;
        this.title = title;
        this.urlTitle = encodeURIComponent(this.title);
        this.description = description;
        this.avatar = avatar;
        this.background = background;
        this.parent_id = parent_id;
        this.children = [];
        let that = this;
        $.each(children, function (key1, value) {
            that.children.push(new Category(value.id, value.title, value.description, value.avatar, value.background, value.parent_id, value.children));
        });
    }
}
export class Notification {
    constructor(id, type, data, read_at, created_at) {
        this.id = id;
        this.type = type;
        this.data = data;
        this.read_at = read_at;
        this.created_at = created_at;
    }
}
export class Playlist {
    constructor(id, title, description, media_ids, read_at, created_at) {
        this.id = id;
        this.title = title;
        this.description = description;
        this.media_ids = media_ids;
        this.read_at = read_at;
        this.created_at = created_at;
    }
}
