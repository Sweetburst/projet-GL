$(document).ready(function () {
    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var arg = {
        resultFunction: function (result) {
            var aChild = document.createElement('li');
            aChild[txt] = result.format + ': ' + result.code;
            document.querySelector('body').appendChild(aChild);
        }
    };
    var decoder = new WebCodeCamJS("canvas").init(arg);
    function decodeLocalImage() {
        decoder.decodeLocalImage();
    }
    //a essayer les 3 ligne
    window.location.href = $('.className').attr('href');
    $('.className').trigger('click');
    document.getElementsByClassName("some-iclass")[0].click();
});
