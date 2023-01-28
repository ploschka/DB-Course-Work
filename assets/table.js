require('datatables.net-dt');
require('datatables.net-buttons-dt');
require('datatables.net-select-dt');

var $ = require('jquery');
var dt = require('datatables.net');

import './styles/datatable.css'

$(function () {
    let selectedRows = [];
    let table = $('#datatable').DataTable({
        dom: 'Blfrtip',
        select: true,
        language: {
            "emptyTable": "Таблица пуста",
            "info": "Позиции с _START_ по _END_ из _TOTAL_",
            "infoEmpty": "Позиции с 0 по 0 из 0",
            "infoFiltered": "(отфильтровано из _MAX_ позиций)",
            "lengthMenu": "Показать _MENU_ записей",
            "search": "Поиск:",
            "zeroRecords": "Подходящих записей не найдено",
            "infoPostFix": " ",
            "paginate": {
                "first": "<<",
                "last": ">>",
                "next": ">",
                "previous": "<"
            },
            select:
            {
                rows:
                {
                    _: "Выбрано записей: %d"
                }
            },
        },
        buttons: [
            {
                text: "Добавить",
                action: function () {
                    window.location = './add';
                }
            },
            {
                text: "Изменить",
                action: function () {
                    let children = table.rows(selectedRows).nodes().to$().children();
                    let params = new URLSearchParams();
                    for (let i = 0; i < children.length; i++) {
                        params.append(children[i].getAttribute('data-tag'), children[i].innerHTML)
                        console.log(children[i].getAttribute('data-tag'), children[i].innerHTML)
                    }
                    const url = './update?'.concat(params.toString());
                    console.log(params.toString())
                    window.location = url;
                }
            },
            {
                text: "Удалить",
                action: function () {
                    let arr = []
                    let r = table.rows(selectedRows)
                    let children = r.nodes().to$().children('[data-tag="id"]')
                    for (let i = 0; i < children.length; i++) {
                        arr.push(children[i].innerHTML)
                    }

                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete', true);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4) {
                            let resp = JSON.parse(xhr.responseText)
                            if (resp.done) {
                                r.remove().draw();
                                selectedRows.splice(0, selectedRows.length)
                                table.button(1).disable();
                                table.button(2).disable();
                            }
                        }
                    }

                    let req = JSON.stringify(arr)

                    xhr.send(req)
                },

            }
        ],
        pageLength: 25
    });

    table.button(1).disable();
    table.button(2).disable();

    table.on('select', function (e, dt, type, indexes) {
        if (indexes.length > 1) {
            selectedRows = indexes;
        }
        else {
            selectedRows = selectedRows.concat(indexes);
        }
        table.button(2).enable();

        if (selectedRows.length > 1) {
            table.button(1).disable();
        }
        else {
            table.button(1).enable();
        }
    })

    table.on('deselect', function (e, dt, type, indexes) {
        indexes.forEach(function (e) {
            selectedRows.splice(selectedRows.indexOf(e), 1)
        })

        if (selectedRows.length > 1) {
            table.button(1).disable();
        }
        else {
            table.button(1).enable();
        }

        if (selectedRows.length === 0) {
            table.button(1).disable();
            table.button(2).disable();
        }

    })
});
