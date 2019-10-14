var __entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;',
    "\\": '&#x5C;'
};

String.prototype.escapeHTML = function() {
    return String(this).replace(/[&<>"'\/\\]/g, function (s) {
        return __entityMap[s];
    });
};