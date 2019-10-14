function ToNormalDate(date) {
    if (date === "")
        return "";
    else {
        let source = new Date(date);
        let day = source.getDate();
        let month = source.getMonth() + 1;
        let year = source.getFullYear();
        return (day < 10 ? "0" : "") + day + "." + (month < 10 ? "0" : "") + month + "." + year;
    }
}

function ToUnnormalDate(date) {
    let source = date.split(".");
    let day = source[0];
    let month = source[1];
    let year = source[2];
    return year + "-" + month + "-" + day;

}