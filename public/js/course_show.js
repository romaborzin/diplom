$(document).ready(function () {
    $("textarea").val("");
    //Получаем первый collapse в аккордеоне
    var firstChapterId = document.getElementById("accordionChapters").firstChild.lastChild.id;
    var firstApplicationId = document.getElementById("accordionApplications").firstChild.lastChild.id;
    var firstChartId = document.getElementById("accordionCharts").firstChild.lastChild.id;
    $("#" + firstChapterId).collapse('show');
    $("#" + firstApplicationId).collapse('show');
    $("#" + firstChartId).collapse('show');
});