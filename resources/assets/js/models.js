var User = /** @class */ (function () {
    function User(id, name, avatar, background, bio, mediaIds) {
        this.id = id;
        this.name = name;
        this.avatar = avatar;
        this.background = background;
        this.bio = bio;
        this.mediaIds = mediaIds;
    }
    User.prototype.toJson = function () {
        return "{id:" + this.id + ",name:'" + this.name + "',avatar:'" + this.avatar + "',background:'" + this.background + "'}";
    };
    return User;
}());
export { User };
var Media = /** @class */ (function () {
    function Media(id, title, description, source, poster_source, simpleType, type, user, user_id, created_at, created_at_readable, comments, tags, tagIds) {
        if (tagIds === void 0) { tagIds = undefined; }
        this.id = id;
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
    Media.prototype.tagStringing = function () {
        var theTagString = "";
        $.each(this.tags, function (key, val) {
            console.log(val.name);
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
