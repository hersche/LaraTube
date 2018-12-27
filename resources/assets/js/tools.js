var MediaSorter = /** @class */ (function () {
    function MediaSorter() {
        this.sortBy = localStorage.getItem("choosenSort");
        if (this.sortBy == "" || this.sortBy == undefined) {
            this.sortBy = "created_at";
            localStorage.setItem("choosenSort", "created_at");
        }
    }
    MediaSorter.prototype.sort = function (medias) {
        if (this.sortBy == "title") {
            medias.sort(MediaSorter.byTitle);
        }
        else if (this.sortBy == "title_reverse") {
            medias.sort(MediaSorter.byTitleReverse);
        }
        else if (this.sortBy == "created_at") {
            medias.sort(MediaSorter.byCreatedAt);
        }
        else if (this.sortBy == "created_at_reverse") {
            medias.sort(MediaSorter.byCreatedAtReverse);
        }
        else if (this.sortBy == "type") {
            medias.sort(MediaSorter.byType);
        }
        else if (this.sortBy == "type_reverse") {
            medias.sort(MediaSorter.byTypeReverse);
        }
        else if (this.sortBy == "simpleType") {
            medias.sort(MediaSorter.byType);
        }
        else if (this.sortBy == "simpleType_reverse") {
            medias.sort(MediaSorter.byTypeReverse);
        }
        else if (this.sortBy == "updated_at") {
            medias.sort(MediaSorter.byUpdatedAt);
        }
        else if (this.sortBy == "updated_at_reverse") {
            medias.sort(MediaSorter.byUpdatedAtReverse);
        }
        return medias;
    };
    MediaSorter.byType = function (a, b) {
        if (a.type < b.type)
            return -1;
        if (a.type > b.type)
            return 1;
        return 0;
    };
    MediaSorter.byTypeReverse = function (a, b) {
        if (a.type < b.type)
            return 1;
        if (a.type > b.type)
            return -1;
        return 0;
    };
    MediaSorter.bySimpleType = function (a, b) {
        if (a.simpleType < b.simpleType)
            return -1;
        if (a.simpleType > b.simpleType)
            return 1;
        return 0;
    };
    MediaSorter.bySimpleTypeReverse = function (a, b) {
        if (a.simpleType < b.simpleType)
            return 1;
        if (a.simpleType > b.simpleType)
            return -1;
        return 0;
    };
    MediaSorter.byTitle = function (a, b) {
        if (a.title < b.title)
            return -1;
        if (a.title > b.title)
            return 1;
        return 0;
    };
    MediaSorter.byTitleReverse = function (a, b) {
        if (a.title < b.title)
            return 1;
        if (a.title > b.title)
            return -1;
        return 0;
    };
    MediaSorter.byCreatedAt = function (a, b) {
        if (a.created_at.date < b.created_at.date)
            return 1;
        if (a.created_at.date > b.created_at.date)
            return -1;
        return 0;
    };
    MediaSorter.byCreatedAtComments = function (a, b) {
        if (a.created_at < b.created_at)
            return 1;
        if (a.created_at > b.created_at)
            return -1;
        return 0;
    };
    MediaSorter.byUpdatedAtReverse = function (a, b) {
        if (a.updated_at.date < b.updated_at.date)
            return -1;
        if (a.updated_at.date > b.updated_at.date)
            return 1;
        return 0;
    };
    MediaSorter.byUpdatedAt = function (a, b) {
        if (a.updated_at.date < b.updated_at.date)
            return 1;
        if (a.updated_at.date > b.updated_at.date)
            return -1;
        return 0;
    };
    MediaSorter.byCreatedAtReverse = function (a, b) {
        if (a.created_at.date < b.created_at.date)
            return -1;
        if (a.created_at.date > b.created_at.date)
            return 1;
        return 0;
    };
    return MediaSorter;
}());
export { MediaSorter };
var Search = /** @class */ (function () {
    function Search(search, medias, tags, users) {
        this.search = search;
        this.tagResult = [];
        this.userResult = [];
        this.mediaResult = [];
        if (search != "") {
            var mediaTitle = $("#theLiveSearchMediaTitle").is(':checked');
            var mediaDescription = $("#theLiveSearchMediaDescription").is(':checked');
            var tagsEnabled = $("#theLiveSearchTags").is(':checked');
            var that_1 = this;
            if ($("#theLiveSearchUsers").is(':checked')) {
                $.each(users, function (key, value) {
                    if (value.name.toLowerCase().indexOf(that_1.search.toLowerCase()) > -1) {
                        if (that_1.userResult.includes(value) == false) {
                            that_1.userResult.push(value);
                        }
                    }
                    if (value.bio.toLowerCase().indexOf(that_1.search.toLowerCase()) > -1) {
                        if (that_1.userResult.includes(value) == false) {
                            that_1.userResult.push(value);
                        }
                    }
                });
            }
            if (mediaTitle || mediaDescription) {
                $.each(medias, function (key, value) {
                    if (mediaTitle) {
                        if (value.title.toLowerCase().indexOf(that_1.search.toLowerCase()) > -1) {
                            if (that_1.mediaResult.includes(value) == false) {
                                that_1.mediaResult.push(value);
                            }
                        }
                    }
                    if (mediaDescription) {
                        if (value.description.toLowerCase().indexOf(search.toLowerCase()) > -1) {
                            if (that_1.mediaResult.includes(value) == false) {
                                that_1.mediaResult.push(value);
                            }
                        }
                    }
                    var tms = new MediaSorter();
                    that_1.mediaResult = tms.sort(that_1.mediaResult);
                    /*  if(tagsEnabled){
                        if(that.mediaResult.includes(value)==false){
                          that.mediaResult.push(value);
                        }
                      }*/
                });
            }
            if ($("#theLiveSearchMedias").is(':checked')) {
                this.userResult = medias;
            }
        }
    }
    return Search;
}());
export { Search };
