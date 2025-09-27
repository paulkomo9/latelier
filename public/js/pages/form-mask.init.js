//var emailSelector=document.getElementById("email");(im=new Inputmask("_@_._")).mask(emailSelector);

var emailSelector = document.getElementById("email");

if (emailSelector) {
    var im = new Inputmask({ alias: "email" });
    im.mask(emailSelector);

}