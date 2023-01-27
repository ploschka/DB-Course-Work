import './styles/report.scss';

let $ = require('jquery');

$(function() {
    let b = $('#form_button')
    let a = $('#form_month')
    let c = $('#reportPlace')
    b.on('click', function()
    {
        // let val = JSON.parse(a.val())
        // b.text(a.val())
        
        let val = JSON.parse(String(a.val()))
        console.log(String(a.val()))

        let xhr = new XMLHttpRequest();
        let url = '__report/'.concat(val.year).concat('/').concat(val.month)
        xhr.open('GET', url, true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                let resp = xhr.responseText
                c.html(resp)
            }
        }

        xhr.send()
    })
})
