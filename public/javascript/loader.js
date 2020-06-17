var myVar;

function myFunction() {
    myVar = setTimeout(showPage);
}

function showPage() {
    document.getElementById("loader").style.display = "none";
    document.getElementById("myDiv").style.display = "block";
}