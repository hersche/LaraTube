var User = /** @class */ (function () {
    function User(id, name, avatar, background, bio, mediaIds, tagString, publicState, admin, email, created_at, updated_at) {
        if (admin === void 0) { admin = false; }
        if (email === void 0) { email = ''; }
        if (created_at === void 0) { created_at = ''; }
        if (updated_at === void 0) { updated_at = ''; }
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
    User.prototype.toJson = function () {
        return "{id:" + this.id + ",name:'" + this.name + "',avatar:'" + this.avatar + "',background:'" + this.background + "'}";
    };
    return User;
}());
export { User };
var Media = /** @class */ (function () {
    function Media(id, title, description, source, poster_source, duration, simpleType, techType, type, user, user_id, created_at, updated_at, created_at_readable, comments, tags, myLike, likes, dislikes, tracks, category_id) {
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
    }
    Media.prototype.tagStringing = function () {
        var theTagString = "";
        $.each(this.tags, function (key, val) {
            theTagString += val.name + " ";
        });
        return theTagString;
    };
    Media.prototype.toJson = function () {
        return "{title:'" + this.title + "',description:'" + this.description + "',source:'" + this.source;
    };
    return Media;
}());
export { Media };
var Tag = /** @class */ (function () {
    function Tag(id, name, slug, count) {
        this.id = id;
        this.name = name;
        this.slug = slug;
        this.count = count;
    }
    return Tag;
}());
export { Tag };
var Category = /** @class */ (function () {
    function Category(id, title, description, avatar, background, parent_id, childs) {
        this.id = id;
        this.title = title;
        this.description = description;
        this.avatar = avatar;
        this.background = background;
        this.medias = [];
        this.parent_id = parent_id;
        this.children = [];
        var that = this;
        $.each(childs, function (key1, value) {
            that.children.push(new Category(value.id, value.title, value.description, value.avatar_source, value.background_source, value.parent_id, value.children));
        });
    }
    Category.prototype.setMedias = function (medias) {
        var that = this;
        that.medias = [];
        $.each(medias, function (key1, value) {
            if (value.category_id == that.id) {
                if (that.medias.includes(value) == false) {
                    that.medias.push(value);
                }
            }
        });
    };
    return Category;
}());
export { Category };
var Notification = /** @class */ (function () {
    function Notification(id, type, data, read_at, created_at) {
        this.id = id;
        this.type = type;
        this.data = data;
        this.read_at = read_at;
        this.created_at = created_at;
    }
    return Notification;
}());
export { Notification };
