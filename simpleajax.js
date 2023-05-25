var xhr = createRequest();

function submitForm(dataSource, divID, form) {
    var cname = form.elements['cname'].value;
    var phone = form.elements['phone'].value;
    var unumber = form.elements['unumber'].value;
    var snumber = form.elements['snumber'].value;
    var stname = form.elements['stname'].value;
    var sbname = form.elements['sbname'].value;
    var dsbname = form.elements['dsbname'].value;
    var date = form.elements['date'].value;
    var time = form.elements['time'].value;

    
    if (xhr) {
        var obj = document.getElementById(divID);
        var requestbody = "cname=" + encodeURIComponent(cname) + "&phone=" + encodeURIComponent(phone) +
            "&unumber=" + encodeURIComponent(unumber) + "&snumber=" + encodeURIComponent(snumber) +
            "&stname=" + encodeURIComponent(stname) + "&sbname=" + encodeURIComponent(sbname) +
            "&dsbname=" + encodeURIComponent(dsbname) + "&date=" + encodeURIComponent(date) +
            "&time=" + encodeURIComponent(time);

        xhr.open("POST", dataSource, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                obj.innerHTML = xhr.responseText;
            }
        };

        xhr.send(requestbody);
    }
}
