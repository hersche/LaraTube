export class User {
    constructor(id, name, avatar, background, bio, mediaIds, tagString, publicState, admin = false, email = '', created_at = '', updated_at = '') {
        this.id = id;
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
    toJson() {
        return "{id:" + this.id + ",name:'" + this.name + "',avatar:'" + this.avatar + "',background:'" + this.background + "'}";
    }
}
export class Media {
    constructor(id, title, description, source, poster_source, duration, simpleType, techType, type, user, user_id, created_at, updated_at, created_at_readable, comments, tags, myLike, likes, dislikes, tracks, category_id, intro = 0, outro = 0) {
        this.id = id;
        this.title = title;
        this.urlTitle = encodeURIComponent(this.title);
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
        this.myLike = myLike;
        this.likes = likes;
        this.dislikes = dislikes;
        this.tagString = this.tagStringing();
        this.tracks = tracks;
        this.category_id = category_id;
        this.intro = Number(intro);
        this.outro = Number(outro);
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
        return "{title:'" + this.title + "',description:'" + this.description + "',source:'" + this.source;
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
            that.children.push(new Category(value.id, value.title, value.description, value.avatar_source, value.background_source, value.parent_id, value.children));
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
