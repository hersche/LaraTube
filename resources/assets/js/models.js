var User = /** @class */ (function () {
    function User(id, name, avatar) {
        this.id = id;
        this.name = name;
        this.avatar = avatar;
    }
    return User;
}());
var Media = /** @class */ (function () {
    function Media(title, description, source, poster_source, simpleType, type, user, created_at, created_at_readable, comments, tags) {
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
    return Media;
}());
;
