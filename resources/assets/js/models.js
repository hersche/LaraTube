"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
function getAutoplay() {
    if (localStorage.getItem("autoplay") == "true") {
        //  $("#autoplayBtn").attr("value", "Autoplay enabled");
    }
    else {
        //  $("#autoplayBtn").attr("value", "Autoplay disabled");
    }
}
exports.getAutoplay = getAutoplay;
